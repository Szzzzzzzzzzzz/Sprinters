<?php

// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

add_action( 'wp_ajax_opinionstage_ajax_toggle_flyout', 'opinionstage_ajax_toggle_flyout' );
add_action( 'wp_ajax_opinionstage_ajax_toggle_article_placement', 'opinionstage_ajax_toggle_article_placement' );
add_action( 'wp_ajax_opinionstage_ajax_toggle_sidebar_placement', 'opinionstage_ajax_toggle_sidebar_placement' );
add_action( 'wp_ajax_osa_message_delete', 'opinionstage_message_delete' );
add_action( 'wp_ajax_opinionstage_ajax_item_count', 'opinionstage_ajax_item_count' );


// Toggle the flyout placement activation flag
function opinionstage_ajax_toggle_flyout() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
	$os_options['fly_out_active'] = sanitize_text_field($_POST['activate']);

	update_option(OPINIONSTAGE_OPTIONS_KEY, $os_options);
	wp_die('1');
}
// Toggle the article placement activation flag
function opinionstage_ajax_toggle_article_placement() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
	$os_options['article_placement_active'] = sanitize_text_field($_POST['activate']);

	update_option(OPINIONSTAGE_OPTIONS_KEY, $os_options);
	wp_die('1');
}
// Toggle the sidebar placement activation flag
function opinionstage_ajax_toggle_sidebar_placement() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
	$os_options['sidebar_placement_active'] = sanitize_text_field($_POST['activate']);

	update_option(OPINIONSTAGE_OPTIONS_KEY, $os_options);
	wp_die('1');
}

function opinionstage_message_delete() {
	if(isset($_POST['delete_options_oswp']) && $_POST['delete_options_oswp'] == true){
		$message_title 		= delete_option('oswp_message_title');
		$message_content 	= delete_option('oswp_message_content');
		update_option('oswp_message_activity_time', time());
	}
	wp_die('1');
}

function opinionstage_ajax_item_count() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
	$os_options['item_count'] = intval($_POST['oswp_item_count']);

	update_option(OPINIONSTAGE_OPTIONS_KEY, $os_options);
	wp_die('1');
}

?>