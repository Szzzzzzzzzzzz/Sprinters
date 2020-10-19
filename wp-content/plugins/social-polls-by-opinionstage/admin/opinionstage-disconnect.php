<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/logging.php';

add_action( 'admin_menu', 'opinionstage_disconnect_account_menu' );
add_action( 'admin_init', 'opinionstage_disconnect_account_action' );

// adds page for post-logout redirect and setup in form of invisible menu page,
// and url: http://wp-host.com/wp-admin/admin.php?page=disconnect-page
function opinionstage_disconnect_account_menu(){
	if (function_exists('add_menu_page')) {
		add_submenu_page(
			null,
			'',
			'',
			'edit_posts',
			OPINIONSTAGE_DISCONNECT_PAGE
		);
	}
}

// performs redirect to plugin settings page, after user logout
function opinionstage_disconnect_account_action() {
	if ( OPINIONSTAGE_DISCONNECT_PAGE === filter_input(INPUT_GET, 'page') && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		delete_option(OPINIONSTAGE_OPTIONS_KEY);

		$redirect_url = get_admin_url(null, 'admin.php?page='.OPINIONSTAGE_GETTING_STARTED_SLUG);

		opinionstage_error_log( 'user logged out, redirect to ' . $redirect_url );
		if ( wp_redirect( $redirect_url, 302 ) ) {
			exit;
		}
	}
}
?>
