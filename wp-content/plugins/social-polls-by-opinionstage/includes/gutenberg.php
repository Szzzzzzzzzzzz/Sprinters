<?php
/**
 * Gutenberg editor integration
 *
 * @package   OpinionStageWordPressPlugin
 */

defined( 'ABSPATH' ) || die(); // block direct access to plugin PHP files.

if ( ! function_exists( 'register_block_type' ) ) {
	return;
}

require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/opinionstage-client-session.php';

add_action( 'init', 'opinionstage_register_gutenberg_assets' );
add_filter( 'block_categories', 'opinionstage_register_gutenberg_categories', 10, 2 );

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @throws Error When index.asset.php is not compiled,
 * use "npm start" (development only) or "npm build"
 * from assets/gutenberg directory in order to compile assets.
 */
function opinionstage_register_gutenberg_assets() {
	$script_asset_path = OPINIONSTAGE_PLUGIN_DIR . 'assets/gutenberg/build/index.asset.php';
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "opinionstage/example-block" block first.'
		);
	}

	$script_asset = require $script_asset_path;

	$index_js = 'assets/gutenberg/build/index.js';
	$index_js_dependencies = $script_asset['dependencies'];
	$index_js_dependencies[] = opinionstage_asset_name( 'content-popup' );
	wp_register_script(
		'opinionstage-gutenberg-block-editor',
		opinionstage_gutenberg_asset_url( $index_js ),
		$index_js_dependencies,
		$script_asset['version'],
		false
	);

	$editor_css = 'assets/gutenberg/build/index.css';
	wp_register_style(
		'opinionstage-gutenberg-block-editor',
		opinionstage_gutenberg_asset_url( $editor_css ),
		array(),
		filemtime( OPINIONSTAGE_PLUGIN_DIR . $editor_css ),
		false
	);

	/* phpcs:disable Squiz.PHP.CommentedOutCode.Found
	// @kucaahbe: we don't need non-editor style, but if we do,
	// here is how it should be like:
	$style_css = 'assets/gutenberg/build/style-index.css';
	wp_register_style(
		'opinionstage-gutenberg-block',
		opinionstage_gutenberg_asset_url( $style_css ),
		array(),
		filemtime( OPINIONSTAGE_PLUGIN_DIR . $style_css ),
		false
	);
	phpcs:enable */

	register_block_type(
		'opinion-stage/block-os-poll',
		array(
			'editor_script' => 'opinionstage-gutenberg-block-editor',
			'editor_style'  => 'opinionstage-gutenberg-block-editor',
		)
	);

	register_block_type(
		'opinion-stage/block-os-survey',
		array(
			'editor_script' => 'opinionstage-gutenberg-block-editor',
			'editor_style'  => 'opinionstage-gutenberg-block-editor',
		)
	);

	register_block_type(
		'opinion-stage/block-os-trivia',
		array(
			'editor_script' => 'opinionstage-gutenberg-block-editor',
			'editor_style'  => 'opinionstage-gutenberg-block-editor',
		)
	);

	register_block_type(
		'opinion-stage/block-os-personality',
		array(
			'editor_script' => 'opinionstage-gutenberg-block-editor',
			'editor_style'  => 'opinionstage-gutenberg-block-editor',
		)
	);

	register_block_type(
		'opinion-stage/block-os-form',
		array(
			'editor_script' => 'opinionstage-gutenberg-block-editor',
			'editor_style'  => 'opinionstage-gutenberg-block-editor',
		)
	);

	register_block_type(
		'opinion-stage/block-os-slideshow',
		array(
			'editor_script' => 'opinionstage-gutenberg-block-editor',
			'editor_style'  => 'opinionstage-gutenberg-block-editor',
		)
	);

	opinionstage_gutenberg_inject_data();
}

/**
 * Adds Gutenberg category for OpinionStage widgets.
 * (use in "block_categories" filter)
 *
 * @param array[] $categories Array of block categories.
 * @param WP_Post $post Post being loaded.
 */
function opinionstage_register_gutenberg_categories( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'opinion-stage',
				'title' => __( 'Interactive Content by OpinionStage', 'opinion-stage' ),
			),
		)
	);
}

/**
 * Adds javascript data (window.OPINIONSTAGE_GUTENBERG_DATA), for use by gutenberg javascript code.
 * FIXME: @kucaahbe: this is weird way of passing data, should be fixed (e.g. with ajax call or so).
 */
function opinionstage_gutenberg_inject_data() {
	wp_localize_script(
		'opinionstage-gutenberg-block-editor',
		'OPINIONSTAGE_GUTENBERG_DATA',
		array(
			'userLoggedIn'       => opinionstage_user_logged_in() ? 'true' : 'false',
			'createNewWidgetUrl' => opinionstage_utm_url( 'api/wp/redirects/widgets/new' ),
			'loginPageUrl'       => get_admin_url( null, 'admin.php?page=' . OPINIONSTAGE_GETTING_STARTED_SLUG ),
			'OswpPluginVersion'  => OPINIONSTAGE_WIDGET_VERSION,
			'OswpClientToken'    => opinionstage_user_access_token(),
			'OswpFetchDataUrl'   => OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API,
			'brandLogoUrl'       => plugin_dir_url( OPINIONSTAGE_PLUGIN_FILE ) . 'admin/images/os-logo.png',
		)
	);
}

/**
 * Returns plugins_url of the Gutenberg asset, specified by path.
 *
 * @param string $asset_path asset path, relative to the plugin's root directory.
 */
function opinionstage_gutenberg_asset_url( $asset_path ) {
	return plugins_url( basename( $asset_path ), OPINIONSTAGE_PLUGIN_DIR . $asset_path );
}
