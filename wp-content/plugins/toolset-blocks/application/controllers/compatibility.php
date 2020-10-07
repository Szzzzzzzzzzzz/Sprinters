<?php

namespace OTGS\Toolset\Views\Controller;

use OTGS\Toolset\Common\Condition\Plugin\UltimateAddonsGutenberg;
use OTGS\Toolset\Views\Controllers\V1\Views as ViewsController;

/**
 * Handles the compatibility between Views and other third-party or OTGS plugins.
 *
 * @since 2.7.0
 * @codeCoverageIgnore
 */
class Compatibility {

	private $compatibility;

	public function __construct( Compatibility\Base $compatibility = null ) {
		$this->compatibility = $compatibility
			? $compatibility
			: null;
	}

	public function initialize() {
		if ( null === $this->compatibility ) {
			$this->initialize_all_integrations();
		} else {
			$this->initialize_single_integration( $this->compatibility );
		}
	}

	private function initialize_all_integrations() {
		/**
		 * @var \OTGS\Toolset\Common\Auryn\Injector
		 */
		$dic = apply_filters( 'toolset_dic', false );

		// We can even add here a check for WPML being installed and properly configured
		$wpml_integration = new Compatibility\Wpml();
		$this->initialize_single_integration( $wpml_integration );

		if ( did_action( 'elementor/loaded' ) ) {
			$elementor_compatibility = new Compatibility\Elementor();
			$this->initialize_single_integration( $elementor_compatibility );
		}

		$the_events_calendar_is_active = new \Toolset_Condition_Plugin_The_Events_Calendar_Active();
		$tribe_events_query_class_exists = new \Toolset_Condition_Plugin_The_Events_Calendar_Tribe_Events_Query_Class_Exists();
		if (
			$the_events_calendar_is_active->is_met() &&
			$tribe_events_query_class_exists->is_met()
		) {
			$the_events_calendar_compatibility = new Compatibility\TheEventsCalendar();
			$this->initialize_single_integration( $the_events_calendar_compatibility );
		}

		$uag_is_active = new UltimateAddonsGutenberg\IsUltimateAddonsGutenbergActive();
		$uag_generate_assets_callable = new UltimateAddonsGutenberg\UAGBGenerateAssetsCallable();
		if ( $uag_is_active->is_met() && $uag_generate_assets_callable->is_met() ) {
			$uag_compatibility = $dic->make(
				Compatibility\UltimateAddonsGutenbergCompatibility::class,
				array(
					':uagb_helper_get_instance' => array(
						'\UAGB_Helper',
						'get_instance',
					),
					':wpv_view_get_instance' => array(
						'\WPV_View_Base',
						'get_instance',
					),
				)
			);
			$this->initialize_single_integration( $uag_compatibility );
		}

		$wc_is_active = new \Toolset_Condition_Woocommerce_Active();
		if ( $wc_is_active->is_met() ) {
			$wc_compatibility = $dic->make( Compatibility\WooCommerceCompatibility::class );
			$this->initialize_single_integration( $wc_compatibility );
		}

		$gutenberg_is_active = new \Toolset_Condition_Plugin_Gutenberg_Active();
		if ( $gutenberg_is_active->is_met() ) {
			$views_editor_blocks = new Compatibility\EditorBlocks\Blocks();
			$this->initialize_single_integration( $views_editor_blocks );

			$block_editor_wpa = $dic->make(
				'\OTGS\Toolset\Views\Controller\Compatibility\BlockEditorWPA',
				array(
					':wpv_wordpress_archive_get_instance' => array(
						'\WPV_WordPress_Archive',
						'get_instance',
					),
					':view_base_get_instance' => array(
						'\WPV_View_Base',
						'get_instance',
					),
					':views_controller' => $dic->make( ViewsController::class ),
				)
			);
			$this->initialize_single_integration( $block_editor_wpa );
		}

		// Since the Divi theme is instantiated after the Compatibility layer between Views and Divi, we need to check
		// the status of the Divi theme every time we want to do something related to that, inside the compatibility class,
		// using the "Toolset_Condition_Theme_Divi_Active::is_met" method.
		$this->initialize_single_integration( new Compatibility\DiviCompatibility() );
	}

	private function initialize_single_integration( Compatibility\Base $compatibility ) {
		$compatibility->initialize();
	}
}
