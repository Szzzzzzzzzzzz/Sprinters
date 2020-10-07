<?php

namespace OTGS\Toolset\Types\Ajax\Handler;

use Exception;
use OTGS\Toolset\Common\Result\ResultSet;
use OTGS\Toolset\Types\AdminNotice\DatabaseMigrationNoticeController;
use Throwable;
use Toolset_Ajax;
use Toolset_Ajax_Handler_Abstract;

/**
 * Handles the database upgrade AJAX calls that perform the upgrade in batches.
 *
 * @since 3.4
 */
class RelationshipsDatabaseUpgrade extends Toolset_Ajax_Handler_Abstract {


	const ARG_DATABASE_LAYER_MODE = 'database_layer_mode';

	const AJAX_ACTION = 'relationships_database_upgrade';

	const LOG_OUTPUT_SEPARATOR = PHP_EOL . '> ';


	/** @var \OTGS\Toolset\Common\Relationships\API\Factory */
	private $relationships_factory;


	/**
	 * Types_Ajax_Handler_Relationships_Database_Upgrade constructor.
	 *
	 * @param Toolset_Ajax $ajax_manager
	 * @param \OTGS\Toolset\Common\Relationships\API\Factory $relationships_factory
	 */
	public function __construct( Toolset_Ajax $ajax_manager, \OTGS\Toolset\Common\Relationships\API\Factory $relationships_factory ) {
		parent::__construct( $ajax_manager );

		$this->relationships_factory = $relationships_factory;
	}


	/**
	 * @inheritDoc
	 */
	public function process_call( $arguments ) {
		$this->ajax_begin( [
			'nonce' => $this->get_ajax_manager()
				->get_action_js_name( self::AJAX_ACTION ),
		] );

		$this->store_is_upgrading_flag( true );

		$args = toolset_ensarr( json_decode( stripslashes( toolset_getpost( 'args' ) ), true ) );

		// Make sure we keep the initial database layer mode (from the first step) throughout the whole process.
		// This is really important because the value is likely going to change throughout the upgrade.
		// We expect the same args to be passed back on the next call.
		$database_layer_mode = array_key_exists( self::ARG_DATABASE_LAYER_MODE, $args )
			? $args[ self::ARG_DATABASE_LAYER_MODE ]
			: $this->relationships_factory->low_level_gateway()->get_current_database_layer_mode();
		$args[ self::ARG_DATABASE_LAYER_MODE ] = $database_layer_mode;

		$migration_controller = $this->relationships_factory
			->low_level_gateway()
			->get_available_migration_controller( $database_layer_mode );
		if ( ! $migration_controller ) {
			// This probably means there is nothing to upgrade.
			$this->finish_as_failure( 'Unable to obtain the migration controller instance.' );

			return;
		}

		$this->store_is_upgrading_flag();

		try {
			$migration_state_serialized = toolset_getpost( 'migrationState' );
			if ( empty( $migration_state_serialized ) ) {
				// No previous state, which means the process is just starting.
				$migration_state = $migration_controller->get_initial_state();
			} else {
				$migration_state = $migration_controller->unserialize_migration_state( $migration_state_serialized );
			}
			$next_state = $migration_controller->do_next_step( $migration_state );
		} catch ( Exception $e ) {
			$this->finish_as_failure( $e->getMessage() );

			return;
		} catch ( Throwable $t ) {
			$this->finish_as_failure( $t->getMessage() );

			return;
		}

		$result = $next_state->get_result();
		if ( $result instanceof ResultSet ) {
			$message = $result->concat_messages( self::LOG_OUTPUT_SEPARATOR );
		} else {
			$message = $result->get_message();
		}

		$has_next_step = $next_state->can_continue();
		$this->store_is_upgrading_flag( $has_next_step );

		$this->ajax_finish( [
			'previousStepStatus' => $result->is_error() ? 'error' : ( $result->has_warnings() ? 'warning' : 'success' ),
			'previousStepNumber' => $next_state->get_previous_step_number(),
			'nextState' => $next_state->serialize(),
			'hasNextStep' => $has_next_step,
			'nextStepNumber' => $next_state->get_next_step_number(),
			'currentSubstep' => $next_state->get_current_substep(),
			'substepCount' => $next_state->get_substep_count(),
			'message' => $message,
			'args' => $args,
		], true );
	}


	/**
	 * @param string $error_message
	 *
	 * @return string
	 */
	private function prefix_internal_error( $error_message ) {
		return __( 'An internal error has occurred during the database upgrade.', 'wpcf' ) . ' ' . $error_message;
	}


	private function finish_as_failure( $message ) {
		$this->store_is_upgrading_flag( false );

		$this->ajax_finish( [
			'message' => $this->prefix_internal_error( $message ),
		], false );
	}


	private function store_is_upgrading_flag( $is_upgrading = true ) {
		if ( $is_upgrading ) {
			update_option( DatabaseMigrationNoticeController::IS_UPGRADING_OPTION, time() );
		} else {
			delete_option( DatabaseMigrationNoticeController::IS_UPGRADING_OPTION );
		}
	}
}
