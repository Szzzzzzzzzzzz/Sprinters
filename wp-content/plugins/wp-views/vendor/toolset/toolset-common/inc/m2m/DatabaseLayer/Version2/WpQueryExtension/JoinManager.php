<?php

namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\WpQueryExtension;

use InvalidArgumentException;
use OTGS\Toolset\Common\Relationships\API\RelationshipRole;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns\AssociationTable;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns\ConnectedElementTable;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns\IclTranslationsTable;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames;
use OTGS\Toolset\Common\WPML\WpmlService;

/**
 * Collects requests for JOINs for the toolset_relationships WP_Query extension, made by the Extension class,
 * and produces the JOIN clause on request.
 *
 * An instance of this class is supposed to be attached to the WP_Query object.
 *
 * @since 4.0
 */
class JoinManager {


	/**
	 * @var string[][][][] Unique aliases for the connected elements table with the element to query by,
	 *     indexed by:
	 *         1. relationship slug,
	 *         2. role name to join the table on (to wp_posts.ID) ("role to return")
	 *         3. role name to constrain the results with ("role to query by")
	 *         4. ID of the associated element in the role to query by
	 */
	private $joins = array();

	/** @var UniqueTableAlias */
	private $unique_table_alias;

	/** @var TableNames */
	private $table_names;

	/** @var \Toolset_Relationship_Definition_Repository */
	private $definition_repository;

	/** @var WpmlService */
	private $wpml_service;

	/** @var \wpdb */
	private $wpdb;

	/** @var \Toolset_Element_Factory */
	private $element_factory;


	/**
	 * JoinManager constructor.
	 *
	 * @param UniqueTableAlias $unique_table_alias
	 * @param TableNames $table_names
	 * @param \Toolset_Relationship_Definition_Repository $definition_repository
	 * @param WpmlService $wpml_service
	 * @param \wpdb $wpdb
	 * @param \Toolset_Element_Factory $element_factory
	 */
	public function __construct(
		UniqueTableAlias $unique_table_alias,
		TableNames $table_names,
		\Toolset_Relationship_Definition_Repository $definition_repository,
		WpmlService $wpml_service,
		\wpdb $wpdb,
		\Toolset_Element_Factory $element_factory
	) {
		$this->unique_table_alias = $unique_table_alias;
		$this->table_names = $table_names;
		$this->definition_repository = $definition_repository;
		$this->wpml_service = $wpml_service;
		$this->wpdb = $wpdb;
		$this->element_factory = $element_factory;
	}


	/**
	 * @return string
	 */
	public function get_join_clauses() {
		$results = '';

		foreach ( $this->joins as $relationship_slug => $data_by_relationship_slug ) {
			foreach ( $data_by_relationship_slug as $role_to_return_name => $data_by_role_to_return ) {
				foreach ( $data_by_role_to_return as $role_to_query_by => $data_by_role_to_query ) {
					foreach ( $data_by_role_to_query as $post_to_query_by => $table_alias ) {
						$results .= $this->get_single_join_clause(
							$relationship_slug,
							$role_to_return_name,
							$role_to_query_by,
							$post_to_query_by,
							$table_alias
						);
					}
				}
			}
		}

		return $results;
	}


	/**
	 * Build a JOIN clause for one table alias.
	 *
	 * Note: We need to limit the query by a particular associated element, otherwise we couldn't
	 * work with multiple JOINs in one query. This is not a problem because of how this class is used -
	 * to query only posts that have associations to all the requested elements (always AND, no OR).
	 *
	 * @param string $relationship_slug
	 * @param string $role_to_return_name
	 * @param string $role_to_query_by_name
	 * @param int $element_id_to_query_by
	 * @param string $connected_element_to_query_by_table_alias
	 *
	 * @return string
	 */
	private function get_single_join_clause(
		$relationship_slug,
		$role_to_return_name,
		$role_to_query_by_name,
		$element_id_to_query_by,
		$connected_element_to_query_by_table_alias
	) {
		// Preprocessing
		//
		//
		$relationship_id = (int) $this->get_relationship_id( $relationship_slug );
		$role_to_return = \Toolset_Relationship_Role::role_from_name( $role_to_return_name );
		$role_to_query_by = \Toolset_Relationship_Role::role_from_name( $role_to_query_by_name );
		$is_wpml = $this->wpml_service->is_wpml_active_and_configured();
		try {
			$element_to_query_by = $this->element_factory->get_post( $element_id_to_query_by );
		} catch ( \Toolset_Element_Exception_Element_Doesnt_Exist $e ) {
			$element_to_query_by = null;
		}

		// Shortcuts
		//
		//
		$wp_posts = $this->wpdb->posts;

		$icl_translations = $this->table_names->get_full_table_name( TableNames::ICL_TRANSLATIONS );
		$icl_translations_element_id = IclTranslationsTable::ELEMENT_ID;
		$icl_translations_element_type = IclTranslationsTable::ELEMENT_TYPE;
		$icl_translations_trid = IclTranslationsTable::TRID;

		$connected_elements = $this->table_names->get_full_table_name( TableNames::CONNECTED_ELEMENTS );
		$connected_elements_trid = ConnectedElementTable::WPML_TRID;
		$connected_elements_element_id = ConnectedElementTable::ELEMENT_ID;
		$connected_elements_group_id = ConnectedElementTable::GROUP_ID;
		$connected_elements_domain = ConnectedElementTable::DOMAIN;

		$associations_table = $this->table_names->get_full_table_name( TableNames::ASSOCIATIONS );
		$associations_relationship_id = AssociationTable::RELATIONSHIP_ID;
		$role_to_return_column = AssociationTable::role_to_column( $role_to_return );
		$role_to_query_by_column = AssociationTable::role_to_column( $role_to_query_by );

		$posts_domain = \Toolset_Element_Domain::POSTS;

		// Table aliases
		//
		//
		$icl_element_to_return_alias = $this->unique_table_alias->generate( $icl_translations, true, 'element_to_return' );
		$connected_element_to_return_alias = $this->unique_table_alias->generate( $connected_elements, true, 'element_to_return' );
		$associations_alias = $this->unique_table_alias->generate( $associations_table, true );

		// Build the JOINs
		//
		//

		// First, join the connected elements table with the post from the wp_posts table.
		if ( $is_wpml ) {
			$role_to_return_join =
				"LEFT JOIN {$icl_translations} AS {$icl_element_to_return_alias}
					ON (
						{$icl_element_to_return_alias}.{$icl_translations_element_id} = {$wp_posts}.ID
						AND {$icl_element_to_return_alias}.{$icl_translations_element_type} LIKE 'post_%'
					)
				LEFT JOIN {$connected_elements} AS {$connected_element_to_return_alias}
					ON (
						(
							{$connected_element_to_return_alias}.{$connected_elements_trid} = {$icl_element_to_return_alias}.{$icl_translations_trid}
							OR {$connected_element_to_return_alias}.{$connected_elements_element_id} = {$wp_posts}.ID
						)
						AND {$connected_element_to_return_alias}.{$connected_elements_domain} = '{$posts_domain}'
					)";
		} else {
			$role_to_return_join =
				"LEFT JOIN {$connected_elements} AS {$connected_element_to_return_alias}
					ON (
						{$connected_element_to_return_alias}.{$connected_elements_element_id} = {$wp_posts}.ID
						AND {$connected_element_to_return_alias}.{$connected_elements_domain} = '{$posts_domain}'
					)";
		}

		// Then, add the associations table on the correct role.
		$associations_table_join =
			"LEFT JOIN {$associations_table} AS {$associations_alias}
				ON (
					{$connected_element_to_return_alias}.{$connected_elements_group_id} = {$associations_alias}.{$role_to_return_column}
					AND {$associations_alias}.{$associations_relationship_id} = {$relationship_id}
				)";

		// Finally, join the connected elements table with the element to query by.
		$element_to_query_by_trid = (int) ( $element_to_query_by instanceof \IToolset_Post ? $element_to_query_by->get_trid() : 0 );

		if ( 0 !== $element_to_query_by_trid ) {
			$element_to_query_by_condition = "{$connected_element_to_query_by_table_alias}.{$connected_elements_trid} = {$element_to_query_by_trid}";
		} else {
			$element_to_query_by_condition = "{$connected_element_to_query_by_table_alias}.{$connected_elements_element_id} = {$element_id_to_query_by}";
		}

		$role_to_query_by_join =
			"LEFT JOIN {$connected_elements} AS {$connected_element_to_query_by_table_alias}
				ON (
					{$connected_element_to_query_by_table_alias}.{$connected_elements_group_id} = {$associations_alias}.{$role_to_query_by_column}
					AND {$element_to_query_by_condition}
				)";

		return " {$role_to_return_join} {$associations_table_join} {$role_to_query_by_join} ";
	}


	private function get_relationship_id( $relationship_slug ) {
		$relationship_definition = $this->definition_repository->get_definition( $relationship_slug );
		if ( null === $relationship_definition ) {
			// This should have failed already during the WHERE clause processing and never get to this point.
			throw new InvalidArgumentException( 'Unknown relationship "'
				. sanitize_text_field( $relationship_slug )
				. '".' );
		}

		return $relationship_definition->get_row_id();
	}


	/**
	 * @param string $relationship_slug
	 * @param RelationshipRole $role_to_return
	 * @param RelationshipRole $role_to_query_by
	 * @param int $query_by_element_id
	 *
	 * @return string
	 */
	public function connected_elements_table(
		$relationship_slug,
		RelationshipRole $role_to_return,
		RelationshipRole $role_to_query_by,
		$query_by_element_id
	) {
		$path_to_value = [
			$relationship_slug,
			$role_to_return->get_name(),
			$role_to_query_by->get_name(),
			(int) $query_by_element_id,
		];
		$stored_alias = toolset_getnest( $this->joins, $path_to_value, null );

		if ( null !== $stored_alias ) {
			return $stored_alias;
		}

		$unique_alias = $this->unique_table_alias->generate(
			$this->table_names->get_full_table_name( TableNames::CONNECTED_ELEMENTS ),
			true,
			'query_by'
		);

		$this->joins = \Toolset_Utils::set_nested_value( $this->joins, $path_to_value, $unique_alias );

		return $unique_alias;
	}
}
