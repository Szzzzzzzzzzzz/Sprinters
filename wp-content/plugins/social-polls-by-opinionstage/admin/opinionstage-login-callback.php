<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/logging.php';

add_action( 'admin_menu', 'opinionstage_register_login_callback_page' );
add_action( 'admin_init', 'opinionstage_login_callback' );

// adds page for post-login redirect and setup in form of invisible menu page,
// and url: http://wp-host.com/wp-admin/admin.php?page=OPINIONSTAGE_LOGIN_CALLBACK_SLUG
function opinionstage_register_login_callback_page() {
	if (function_exists('add_menu_page')) {
		add_submenu_page(
			null,
			'',
			'',
			'edit_posts',
			OPINIONSTAGE_LOGIN_CALLBACK_SLUG
		);
	}
}

// performs redirect to plugin settings page, after user logged in
function opinionstage_login_callback() {

	// Make sure user is logged in and have edit posts cap
  	if (is_user_logged_in() && current_user_can('edit_posts')) {

		if ( OPINIONSTAGE_LOGIN_CALLBACK_SLUG == filter_input( INPUT_GET, 'page' ) ) {
			$success = sanitize_text_field($_GET['success']);
			$uid = sanitize_text_field($_GET['uid']);
			$token = sanitize_text_field($_GET['token']);
			$email = sanitize_email($_GET['email']);
			$fly_id = intval($_GET['fly_id']);
			$article_placement_id = intval($_GET['article_placement_id']);
			$sidebar_placement_id = intval($_GET['sidebar_placement_id']);

			opinionstage_uninstall();
			opinionstage_parse_client_data(
				compact(
					'success',
					'uid',
					'token',
					'email',
					'fly_id',
					'article_placement_id',
					'sidebar_placement_id'
				)
			);


			$redirect_url = get_admin_url(null, '', 'admin').'admin.php?page='.OPINIONSTAGE_MENU_SLUG;

			opinionstage_error_log( 'user logged in, redirect to ' . $redirect_url );
			if ( wp_redirect( $redirect_url, 302 ) ) {
				exit;
			}
		}
	}
}
?>
