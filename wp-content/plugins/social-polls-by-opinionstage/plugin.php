<?php
/**
 * Poll, Survey, Form & Quiz Maker by OpinionStage
 *
 * @package   OpinionStageWordPressPlugin
 *
 * @wordpress-plugin
 * Plugin Name: Poll, Survey, Form & Quiz Maker by OpinionStage
 * Plugin URI:  https://www.opinionstage.com
 * Description: Add a highly engaging poll, survey, quiz or contact form builder to your site. You can add the poll, survey, quiz or form to any post/page or to the sidebar.
 * Version:     19.6.41
 * Author:      OpinionStage.com
 * Author URI:  https://www.opinionstage.com
 * Text Domain: social-polls-by-opinionstage
 */

defined( 'ABSPATH' ) || die(); // block direct access to plugin PHP files.

define( 'OPINIONSTAGE_PLUGIN_FILE', __FILE__ );
define( 'OPINIONSTAGE_PLUGIN_DIR', plugin_dir_path( OPINIONSTAGE_PLUGIN_FILE ) );

require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/logging.php';

$opinionstage_settings = array();

// don't even try to load any configuration settings,
// if WordPress is not in debug mode,
// as configuration settings are only for plugin development.
if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
	$opinionstage_dev_cfg_path = plugin_dir_path( __FILE__ ) . 'dev.ini';
	if ( file_exists( $opinionstage_dev_cfg_path ) ) {
		opinionstage_error_log( "loading configuration from file $opinionstage_dev_cfg_path" );
		$opinionstage_settings = parse_ini_file( $opinionstage_dev_cfg_path );
	}
}

define( 'OPINIONSTAGE_WIDGET_VERSION', '19.6.41' );

define( 'OPINIONSTAGE_TEXT_DOMAIN', 'social-polls-by-opinionstage' );

define( 'OPINIONSTAGE_SERVER_BASE', isset( $opinionstage_settings['server_base'] ) ? $opinionstage_settings['server_base'] : 'https://www.opinionstage.com' );
define( 'OPINIONSTAGE_LOGIN_PATH', OPINIONSTAGE_SERVER_BASE . '/integrations/wordpress/new' );
define( 'OPINIONSTAGE_API_PATH', OPINIONSTAGE_SERVER_BASE . '/api/v1' );
define( 'OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API', OPINIONSTAGE_SERVER_BASE . '/api/wp/v1/my/widgets' );
define( 'OPINIONSTAGE_CONTENT_POPUP_SHARED_WIDGETS_API', OPINIONSTAGE_SERVER_BASE . '/api/wp/v1/shared_widgets' );
define( 'OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API_RECENT_UPDATE', OPINIONSTAGE_SERVER_BASE . '/api/wp/v1/my/widgets/recent-update' );
define( 'OPINIONSTAGE_DEACTIVATE_FEEDBACK_API', OPINIONSTAGE_SERVER_BASE . '/api/wp/v1/events' );
define( 'OPINIONSTAGE_MESSAGE_API', 'https://www.opinionstage.com/wp/msg-app/api/index.php' );

define( 'OPINIONSTAGE_WIDGET_API_KEY', 'wp35e8' );
define( 'OPINIONSTAGE_UTM_SOURCE', 'wordpress' );
define( 'OPINIONSTAGE_UTM_CAMPAIGN', 'WPMainPI' );
define( 'OPINIONSTAGE_UTM_MEDIUM', 'link' );
define( 'OPINIONSTAGE_UTM_CONNECT_MEDIUM', 'connect' );

define( 'OPINIONSTAGE_OPTIONS_KEY', 'opinionstage_widget' );

define( 'OPINIONSTAGE_POLL_SHORTCODE', 'socialpoll' );
define( 'OPINIONSTAGE_WIDGET_SHORTCODE', 'os-widget' );
define( 'OPINIONSTAGE_PLACEMENT_SHORTCODE', 'osplacement' );

define( 'OPINIONSTAGE_MENU_SLUG', 'opinionstage-settings' );
define( 'OPINIONSTAGE_PLACEMENT_SLUG', 'opinionstage-my-placements' );
define( 'OPINIONSTAGE_GETTING_STARTED_SLUG', 'opinionstage-getting-started' );
define( 'OPINIONSTAGE_VIEW_ITEM_SLUG', 'opinionstage-view-my-items' );
define( 'OPINIONSTAGE_HELP_RESOURCE_SLUG', 'opinionstage-help-resource' );

define( 'OPINIONSTAGE_LOGIN_CALLBACK_SLUG', 'opinionstage-login-callback' );
define( 'OPINIONSTAGE_DISCONNECT_PAGE', 'opinionstage-disconnect-page' );
define( 'OPINIONSTAGE_CONTENT_LOGIN_CALLBACK_SLUG', 'opinionstage-content-login-callback-page' );

if ( ! version_compare( PHP_VERSION, '5.2', '>=' ) ) {
	add_action( 'admin_notices', 'opinionstage_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
	add_action( 'admin_notices', 'opinionstage_fail_wp_version' );
} else {
	// phpcs:disable Squiz.Commenting.FunctionComment
	function opinionstage_plugin_activated( $plugin ) {
		// Check if active plugin file is plugin.php on plugin activate hook.
		if ( plugin_basename( __FILE__ ) === $plugin ) {
			// phpcs:disable WordPress.Security.EscapeOutput
			exit( wp_safe_redirect( 'admin.php?page=' . OPINIONSTAGE_GETTING_STARTED_SLUG ) );
			// phpcs:enable
		}
	}
	// phpcs:disable Squiz.Commenting.FunctionComment
	function opinionstage_plugin_activate() {
		// all good: delete old file.
		$deprecated_file = plugin_dir_path( __FILE__ ) . 'opinionstage-polls.php';
		if ( file_exists( $deprecated_file ) && is_writable( $deprecated_file ) ) {
			unlink( $deprecated_file );
		}
	}
	register_activation_hook( __FILE__, 'opinionstage_plugin_activate' );
	add_action( 'init', 'opinionstage_plugin_activate' );
	add_action( 'activated_plugin', 'opinionstage_plugin_activated' );
	require_once plugin_dir_path( __FILE__ ) . 'includes/opinionstage-functions.php';

	// Check if another OpinionStage plugin already installed and display warning message.
	if ( opinionstage_check_plugin_available( 'opinionstage_popup' ) ) {
		add_action( 'admin_notices', 'opinionstage_other_plugin_installed_warning' );
	} else {
		require_once plugin_dir_path( __FILE__ ) . 'includes/opinionstage-utility-functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/opinionstage-article-placement-functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/opinionstage-sidebar-widget.php';

		if ( ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || ( defined( 'DOING_AJAX' ) ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'includes/opinionstage-ajax-functions.php';
			require plugin_dir_path( __FILE__ ) . 'public/init.php';
		} else {
			if ( is_admin() ) {
				require plugin_dir_path( __FILE__ ) . 'admin/init.php';
			} else {
				require plugin_dir_path( __FILE__ ) . 'public/init.php';
			}
			require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/gutenberg.php';
		}

		add_action( 'widgets_init', 'opinionstage_init_widget' );
		add_action( 'plugins_loaded', 'opinionstage_init' );
	}
}
/**
 * Opinionstage admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function opinionstage_fail_php_version() {
	/* translators: %s: PHP version */
	$message      = sprintf( esc_html__( 'Poll, Survey, Form & Quiz Maker by OpinionStage requires PHP version %s+, plugin is currently NOT RUNNING.', 'opinionstage' ), '5.2' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Opinionstage admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.5.0
 *
 * @return void
 */
function opinionstage_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( 'Poll, Survey, Form & Quiz Maker by OpinionStage requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'opinionstage' ), '4.7' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
