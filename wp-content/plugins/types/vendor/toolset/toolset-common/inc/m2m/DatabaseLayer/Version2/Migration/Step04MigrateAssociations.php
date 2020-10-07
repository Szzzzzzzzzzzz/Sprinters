<?php

namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration;

use OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns\AssociationTable;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns\ConnectedElementTable;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames;
use OTGS\Toolset\Common\Result\DatabaseError;
use OTGS\Toolset\Common\Result\ResultInterface;
use OTGS\Toolset\Common\Result\ResultSet;
use OTGS\Toolset\Common\Result\SingleResult;
use OTGS\Toolset\Common\Result\Success;

/**
 * Migrate associations to new temporary tables.
 *
 * @since 4.0
 */
class Step04MigrateAssociations extends MigrationStep {

	const STEP_NUMBER = 4;

	const NEXT_STEP = Step05MaintenanceModeOn::class;

	/** @var string Custom property in MigrationStep to store the size of the previous batch. */
	const BATCH_SIZE_KEY = 'batch_size';

	const POST_ID_COLUMNS = [
		AssociationTable::PARENT_ID,
		AssociationTable::CHILD_ID,
		AssociationTable::INTERMEDIARY_ID,
	];


	/** @var \wpdb */
	private $wpdb;


	/** @var TableNames */
	private $table_names;


	/** @var \Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured */
	private $is_wpml_active;


	/** @var int[] Map from post ID to connected element group ID. */
	private $element_group_id_cache = [];


	/** @var null|int */
	private $last_element_group_id = null;


	private $batch_size_helper;


	/**
	 * Step04MigrateAssociations constructor.
	 *
	 * @param \wpdb $wpdb
	 * @param TableNames $table_names
	 * @param \Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured $is_wpml_active
	 * @param BatchSizeHelper $batch_size_helper
	 */
	public function __construct(
		\wpdb $wpdb,
		TableNames $table_names,
		\Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured $is_wpml_active,
		BatchSizeHelper $batch_size_helper
	) {
		$this->wpdb = $wpdb;
		$this->table_names = $table_names;
		$this->is_wpml_active = $is_wpml_active;
		$this->batch_size_helper = $batch_size_helper;
	}


	/**
	 * @inheritDoc
	 */
	public function run( MigrationStateInterface $previous_state ) {
		$this->validate_state( $previous_state );

		$previous_last_association_id = (int) $previous_state->get_progress();
		$batch_size = $this->get_batch_size( $previous_state );

		$final_association_table = $this->table_names->get_full_table_name( TableNames::ASSOCIATIONS );
		$migrated_association_table = $this->table_names->get_full_table_name( MigrationController::TEMPORARY_NEW_ASSOCIATION_TABLE_NAME );

		if ( ! $this->table_exists( $final_association_table ) ) {
			return $this->return_error( sprintf(
				__( 'The final association table "%s" doesn\'t exist but it was expected, , cannot move forward with the migration.', 'wpv-views' ),
				$final_association_table
			), false );
		}

		if ( ! $this->table_exists( $migrated_association_table ) ) {
			return $this->return_error( sprintf(
				__( 'The new association table "%s" doesn\'t exist but it was expected, cannot move forward with the migration.', 'wpv-views' ),
				$migrated_association_table
			), false );
		}

		$old_associations = $this->load_old_associations( $previous_last_association_id, $batch_size );

		if ( empty( $old_associations ) ) {
			// This step is complete, nothing more to process.
			return new MigrationState(
				self::NEXT_STEP,
				null,
				new SingleResult( true, __( 'All associations have been migrated to the new temporary table.', 'wpv-views' ) ),
				$this->get_id(),
				self::STEP_NUMBER,
				self::STEP_NUMBER + 1
			);
		}

		$results = new ResultSet();

		// The transaction is used here to group all the writes into a single commit, for substantial performance
		// increase. We don't rollback anything here, there's no need for rollbacks.
		$this->wpdb->query( 'START TRANSACTION' );
		foreach ( $old_associations as $old_association ) {
			$association_result = $this->migrate_single_association( $old_association );

			// Reduce the warning of errors to warnings for individual associations.
			if ( $association_result->is_success() ) {
				$results->add( $association_result );
			} else {
				$results->add( new SingleResult(
						true, $association_result->get_message(), $association_result->get_code(), true )
				);
			}
		}
		$this->wpdb->query( 'COMMIT' );

		$last_association_id = (int) $old_associations[ count( $old_associations ) - 1 ]['id'];

		$results->add( true, sprintf(
			__( 'Completed batch of %d associations between #%d and #%d.', 'wpv-views' ),
			count( $old_associations ),
			$old_associations[0]['id'],
			$last_association_id
		) );

		$next_state = new MigrationState(
			$this->get_id(),
			$last_association_id,
			$results,
			$this->get_id(),
			self::STEP_NUMBER,
			self::STEP_NUMBER
		);
		$next_state->set_property( self::BATCH_SIZE_KEY, $batch_size );
		$next_state->set_substep_info(
			$previous_state->get_current_substep() + 1,
			$this->get_total_substep_count( $batch_size, $previous_state )
		);


		return $next_state;
	}


	/**
	 * Determine the correct batch size based on the current context.
	 *
	 * @param MigrationStateInterface $current_state
	 *
	 * @return int
	 */
	private function get_batch_size( MigrationStateInterface $current_state ) {
		// Return the size from the migration state if it's stored, so that we stay consistent
		// throughout the whole process.
		if ( $current_state instanceof MigrationState ) {
			$batch_size = (int) $current_state->get_property( self::BATCH_SIZE_KEY );

			if ( $batch_size > 0 ) {
				return $batch_size;
			}
		}

		return $this->batch_size_helper->get_batch_size();
	}


	/**
	 * Fetch the next batch of old associations from the database.
	 *
	 * @param int $last_processed_id
	 * @param int $batch_size
	 *
	 * @return array An array representing the old association row.
	 */
	private function load_old_associations( $last_processed_id, $batch_size ) {
		return $this->wpdb->get_results( $this->wpdb->prepare(
			"SELECT id, relationship_id, parent_id, child_id, intermediary_id
			FROM {$this->table_names->get_full_table_name( TableNames::ASSOCIATIONS )}
			WHERE id > %d
			ORDER BY id ASC
			LIMIT %d",
			$last_processed_id,
			$batch_size
		), ARRAY_A );
	}


	private function get_total_substep_count( $batch_size, MigrationStateInterface $previous_state ) {
		$previous_substep_count = $previous_state->get_substep_count();
		// We also need to ignore value -1 as this is just used to indicate that there are substeps, but we don't know
		// their count yet.
		if ( $previous_substep_count > 0 ) {
			return $previous_substep_count;
		}

		$old_row_count = $this->batch_size_helper->count_old_associations();

		return (int) ceil( $old_row_count / $batch_size );
	}


	/**
	 * Process a single association.
	 *
	 * Translates element IDs into element group IDs from the new "connected elements" table
	 * and stores the row in the new association table.
	 *
	 * @param array $old_association An array representing the old association row.
	 *
	 * @return ResultInterface
	 */
	private function migrate_single_association( $old_association ) {
		$new_association = [
			AssociationTable::RELATIONSHIP_ID => $old_association['relationship_id'],
		];

		foreach ( self::POST_ID_COLUMNS as $column ) {
			/** @var ResultInterface $result */
			list( $result, $element_group_id ) = $this->post_id_to_element_group_id( (int) $old_association[ $column ] );
			if ( ! $result->is_success() ) {
				return new SingleResult( false, sprintf(
					__( 'Error when migrating association #%d: %s', 'wpv-views' ),
					$old_association['id'],
					$result->get_message()
				) );
			}
			$new_association[ $column ] = $element_group_id;
		}

		$inserted = $this->wpdb->insert(
			$this->table_names->get_full_table_name( MigrationController::TEMPORARY_NEW_ASSOCIATION_TABLE_NAME ),
			$new_association,
			'%d'
		);

		if ( $inserted !== 1 ) {
			return new DatabaseError(
				__( 'Insert an association', 'wpv-views' ),
				$this->wpdb,
				$inserted
			);
		}

		return new Success();
	}


	/**
	 * Translate a post ID into an element group ID from the "connected elements" table.
	 *
	 * If the element is not in the table yet, it will be inserted.
	 * Slightly optimized for performance.
	 *
	 * @param int $post_id
	 *
	 * @return array An array of two elements, a ResultInterface and the new group ID.
	 */
	private function post_id_to_element_group_id( $post_id ) {
		if ( 0 === $post_id ) {
			return [ new Success(), 0 ];
		}

		if ( array_key_exists( $post_id, $this->element_group_id_cache ) ) {
			return [ new Success(), $this->element_group_id_cache[ $post_id ] ];
		}

		$group_id_column = ConnectedElementTable::GROUP_ID;
		$element_id_column = ConnectedElementTable::ELEMENT_ID;
		$group_id = (int) $this->wpdb->get_var( $this->wpdb->prepare(
			"SELECT {$group_id_column}
			FROM {$this->table_names->get_full_table_name( TableNames::CONNECTED_ELEMENTS )}
			WHERE {$element_id_column} = %d",
			$post_id
		) );

		if ( 0 === $group_id ) {
			/** @var ResultInterface $result */
			list( $result, $group_id ) = $this->insert_new_connected_element( $post_id );
			if ( ! $result->is_success() ) {
				return [ $result, 0 ];
			}
		}

		$this->element_group_id_cache[ $post_id ] = $group_id;

		return [ new Success(), $group_id ];
	}


	/**
	 * Insert a new connected element record.
	 *
	 * @param int $post_id ID of the post.
	 *
	 * @return array An array of two elements, a ResultInterface and the new group ID.
	 */
	private function insert_new_connected_element( $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			// Prevent inserting records for elements that don't exist.
			return [
				new SingleResult( false, sprintf(
					__( 'Post #%d doesn\'t exist. An association involving it will be skipped.', 'wpcf' ), $post_id
				) ),
				0,
			];
		}

		list( $wpml_trid, $lang_code ) = $this->get_wpml_info( $post_id );

		$connected_element = [
			ConnectedElementTable::ELEMENT_ID => $post_id,
			ConnectedElementTable::DOMAIN => \Toolset_Element_Domain::POSTS,
			ConnectedElementTable::WPML_TRID => $wpml_trid,
			ConnectedElementTable::LANG_CODE => $lang_code,
			ConnectedElementTable::GROUP_ID => $this->get_new_group_id(),
		];

		$inserted = $this->wpdb->insert(
			$this->table_names->get_full_table_name( TableNames::CONNECTED_ELEMENTS ),
			$connected_element,
			[ '%d', '%s', '%d', '%s', '%d' ]
		);

		if ( $inserted !== 1 ) {
			return [
				new DatabaseError(
					__( 'Insert an connected element record', 'wpv-views' ),
					$this->wpdb,
					$inserted
				),
				0,
			];
		}

		return [ new Success(), (int) $this->wpdb->insert_id ];
	}


	/**
	 * Try to obtain a TRID and a language code of a post.
	 *
	 * @param int $post_id ID of the post.
	 *
	 * @return array An array of two elements, TRID and language code. Null is used if the values are not available.
	 */
	private function get_wpml_info( $post_id ) {
		if ( ! $this->is_wpml_active->is_met() ) {
			return [ null, null ];
		}

		$lang_details = (array) apply_filters( 'wpml_element_language_details', null, [
			'element_id' => $post_id,
			'element_type' => get_post_type( $post_id ),
		] );

		return [
			toolset_getarr( $lang_details, 'trid', null ),
			toolset_getarr( $lang_details, 'language_code', null ),
		];
	}


	/**
	 * Produce a new element group ID that hasn't been used yet.
	 *
	 * Asuming that nobody else interacts with the connected elements table, which is safe at this point.
	 *
	 * @return int
	 */
	private function get_new_group_id() {
		if ( null === $this->last_element_group_id ) {
			$group_id_column = ConnectedElementTable::GROUP_ID;
			$this->last_element_group_id = (int) $this->wpdb->get_var(
				"SELECT {$group_id_column}
				FROM {$this->table_names->get_full_table_name( TableNames::CONNECTED_ELEMENTS)}
				ORDER BY {$group_id_column} DESC
				LIMIT 1"
			);
		}

		$this->last_element_group_id ++;

		return $this->last_element_group_id;
	}

}
