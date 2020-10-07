<?php
/**
 * Toolset Common ES - Bootstrap
 *
 * This is the root of the dependencies tree. If you need to inject dependencies from other plugins apply a filter
 * only here on the bootstrap.php and use a dependency injection method on the target class to avoid
 * hiding dependencies.
 */

/**
 * Once TB is merged to Views /php/Auryn can be deleted and this function should be removed.
 * @return \ToolsetCommonEs\Library\Auryn\Injector
 */
function toolset_common_es_dic() {
	static $dic;

	if ( null === $dic ) {
		$dic = new \ToolsetCommonEs\Library\Auryn\Injector();
	}

	return $dic;
}

/**
 * This extends toolset_dic for some shares.
 *
 * Currently it also has a fallback for the case TB runs without Toolset Common available.
 * Once TB is merged to Views the use of "toolset_common_es_dic" should be removed from this.
 */
add_filter( 'toolset_common_es_dic', function( /** @noinspection PhpUnusedParameterInspection */ $default ) {
	$dic_common = apply_filters( 'toolset_dic', false );

	// Only delete this line, when TB is merged to Views.
	$dic_common = $dic_common ?: toolset_common_es_dic();

	// Shares
	$dic_common->share( '\ToolsetCommonEs\Utils\ScriptData' );
	$dic_common->share( '\ToolsetCommonEs\Rest\API' );
	$dic_common->share( '\ToolsetCommonEs\Block\Style\Responsive\Devices\Devices' );
	$dic_common->share( '\ToolsetCommonEs\Library\MobileDetect\MobileDetect' );
	$dic_common->share( '\ToolsetCommonEs\Utils\Config\Toolset' );

	// Register Themes to Responsive.
	$responsive_devices = $dic_common->make( '\ToolsetCommonEs\Block\Style\Responsive\Devices\Devices' );
	$responsive_devices->add_theme(
		$dic_common->make( '\ToolsetCommonEs\Block\Style\Responsive\Devices\Themes\TwentyTwenty' )
	);
	$responsive_devices->add_theme(
		$dic_common->make( '\ToolsetCommonEs\Block\Style\Responsive\Devices\Themes\Astra' )
	);

	return $dic_common;
} );

add_action( 'init', function() {
	$dic = apply_filters( 'toolset_common_es_dic', false );

	if( ! $dic ) {
		return;
	}

	// Config
	$config = file_exists( TOOLSET_COMMON_ES_DIR . '/config.php' ) ?
		include TOOLSET_COMMON_ES_DIR . '/config.php' :
		[];

	/** @var \ToolsetCommonEs\Utils\Data\Factory $factory_data */
	$factory_data = $dic->make( '\ToolsetCommonEs\Utils\Data\Factory' );
	$static_data = $factory_data->get_static( $config );

	$dic->define( '\ToolsetCommonEs\Utils\Config\Toolset', [ ':config' => $static_data ] );

	// Blocks Style
	/** @var \ToolsetCommonEs\Block\Style\Block\Factory $block_styles_factory */
	$block_styles_factory = $dic->make( '\ToolsetCommonEs\Block\Style\Block\Factory' );
	$block_factories = apply_filters( 'toolset_common_es_block_factories', array() );

	try {
		foreach( $block_factories as $block_factory ) {
			$block_styles_factory->add_block_factory( $block_factory );
		}
	} catch( \Exception $e ) {
		// This probably means someone injected invalid data on 'toolset_common_es_block_factories' filter.
		// error_log( $e->getMessage() );
	}

	$dic->define( '\ToolsetCommonEs\Block\Style\Loader', array( ':block_factory' => $block_styles_factory ) );
	$dic->make( '\ToolsetCommonEs\Block\Style\Loader' );
}, 2 );

add_action( 'init', function() {
	$dic = apply_filters( 'toolset_common_es_dic', false );

	if( ! $dic ) {
		return;
	}

	/* @var $rest_api \ToolsetCommonEs\Rest\API */
	$rest_api = $dic->make( '\ToolsetCommonEs\Rest\API' );

	// - Add Settings
	$rest_api->add_route( $dic->make( '\ToolsetCommonEs\Rest\Route\Settings' ) );

	// - Shortcode Render
	$rest_api->add_route( $dic->make( '\ToolsetCommonEs\Rest\Route\ShortcodeRender' ) );

	// - Image Resize
	$rest_api->add_route( $dic->make( '\ToolsetCommonEs\Rest\Route\ImageResize' ) );

	// - Responsive
	$rest_api->add_route( $dic->make( '\ToolsetCommonEs\Rest\Route\Responsive' ) );

	// - LayoutsInfo
	$rest_api->add_route( $dic->make( '\ToolsetCommonEs\Rest\Route\LayoutsInfo' ) );

	// - Apply Script Data.
	$rest_api->apply_script_data();
}, 4 );

/* Rest API - Register Routes */
add_action( 'rest_api_init', function() {
	// Backend (is_admin() does not work on rest requests itself, so we also need to load on any rest request)
	if( is_admin() || ( defined( 'REST_REQUEST') && REST_REQUEST ) ) {
		$dic = apply_filters( 'toolset_common_es_dic', false );

		if( ! $dic ) {
			return;
		}

		// Rest API
		$rest_api = $dic->make( '\ToolsetCommonEs\Rest\API' );

		// - Apply Script Data.
		$rest_api->apply_script_data();

		// -> Register routes
		$rest_api->register_routes();
	}
}, 2 );


/* Admin Init */
add_filter( 'admin_init', function() {
	$dic = apply_filters( 'toolset_common_es_dic', false );

	if( ! $dic ) {
		return;
	}

	/** @var ToolsetCommonEs\Utils\ScriptData $script_data */
	$script_data = $dic->make( '\ToolsetCommonEs\Utils\ScriptData' );
	$script_data->add_data( 'settings', get_option( 'toolset_blocks_settings', array() ) );

	$config = file_exists( TOOLSET_COMMON_ES_DIR . '/config.php' ) ?
		include TOOLSET_COMMON_ES_DIR . '/config.php' :
		[];
	$script_data->add_data( 'config', $config );

	/** @var ToolsetCommonEs\Block\Style\Responsive\Devices\Devices $responsive_devices */
	$responsive_devices = $dic->make( '\ToolsetCommonEs\Block\Style\Responsive\Devices\Devices' );
	$devices = $responsive_devices->get();
	$script_data->add_data( 'mediaQuery', [
		'keys' => [
			'desktop' => $responsive_devices::DEVICE_DESKTOP,
			'tablet' => $responsive_devices::DEVICE_TABLET,
			'phone' => $responsive_devices::DEVICE_PHONE
		],
		'devices' => $devices
	] );


	$toolset_settings = $dic->make( '\ToolsetCommonEs\Block\Style\Responsive\ToolsetSettings' );
	add_filter(
		'toolset_filter_toolset_register_settings_general_section',
		array( $toolset_settings, 'callback_toolset_filter_toolset_register_settings_general_section' ),
		10990
	);

	// Compatibility.
	// Backend Editor Style.
	/** @var \ToolsetCommonEs\Compatibility\Location\PostEditPage $location */
	$location = $dic->make( '\ToolsetCommonEs\Compatibility\Location\PostEditPage' );

	// A bit logic here, to prevent unnecessary tree loading.
	if ( $location->is_open() ) {

		/** @var \ToolsetCommonEs\Compatibility\Theme\FactorySettings $theme_factory */
		$theme_factory = $dic->make( '\ToolsetCommonEs\Compatibility\Theme\FactorySettings' );

		/** @var \ToolsetCommonEs\Compatibility\Compatibility $better_block_preview */
		$better_block_preview = $dic->make(
			'\ToolsetCommonEs\Compatibility\Compatibility',
			[
				':id' => 'tces-compatibility-theme-styling',
				':location' => $location,
				'settings' => $theme_factory->get_as_string(),
			]
		);

		// Add selector.
		$better_block_preview->add_selector( $dic->make( '\ToolsetCommonEs\Compatibility\Style\Selector\BlockEditorBlock' ) );

		// Add basic rules: Link, Text, Heading.
		$better_block_preview->add_rule( $dic->make( '\ToolsetCommonEs\Compatibility\Style\Rule\Link' ) );
		$better_block_preview->add_rule( $dic->make( '\ToolsetCommonEs\Compatibility\Style\Rule\Text' ) );
		$better_block_preview->add_rule( $dic->make( '\ToolsetCommonEs\Compatibility\Style\Rule\Heading' ) );

		// 3rd party rules.
		$third_party_rules = apply_filters( 'toolset_common_es_compatibility_style_backend_editor_rule', [] );
		foreach( $third_party_rules as $rule ) {
			if ( $rule instanceof \ToolsetCommonEs\Compatibility\IRule ) {
				$better_block_preview->add_rule( $rule );
			}
		}

		// Being careful with response of the filter.
		if ( is_array( $third_party_rules ) ) {
			foreach ( $third_party_rules as $rule ) {
				if ( $rule instanceof \ToolsetCommonEs\Compatibility\Style\IRule ) {
					$better_block_preview->add_rule( $rule );
				}
			}
		}

		// Apply style rules.
		$better_block_preview->apply_css_rules();
	}
} );

/* Script Data (only needed for admin)*/
add_action( 'admin_print_scripts', function() {
	$dic = apply_filters( 'toolset_common_es_dic', false );

	if ( ! $dic ) {
		return;
	}

	// Print Script Data
	$script_data = $dic->make( '\ToolsetCommonEs\Utils\ScriptData' );
	$script_data->admin_print_scripts();
}, 1 );

