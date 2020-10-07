<?php

namespace OTGS\Toolset\Views\Controller\Upgrade;

/**
 * Setup initial database
 *
 * @since 3.0
 */
class Setup implements IRoutine {

	/**
	 * @var \OTGS\Toolset\Views\Controller\Upgrade\Setup\DefaultSettingsIn2070000
	 */
	private $default_settings_in_2070000;

	/**
	 * Constructor.
	 *
	 * @param \WPV_Settings $settings
	 */
	public function __construct(
		\OTGS\Toolset\Views\Controller\Upgrade\Setup\DefaultSettingsIn2070000 $default_settings_in_2070000
	) {
		$this->default_settings_in_2070000 = $default_settings_in_2070000;
	}

	/**
	 * Execute database setup
	 *
	 * @param array $args
	 * @since 3.0
	 * @note The routine related to 3.0 has been disabled until we properly define what version of Views/Blocks
	 *     will be offered to existing and new users, and how we set that separation.
	 */
	public function execute_routine( $args = array() ) {// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$this->default_settings_in_2070000->execute_routine();
	}

}
