<?php

// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/logging.php';

function opinionstage_utm_query( $query = array() ) {
	$utm_query = array(
		'utm_source'   => OPINIONSTAGE_UTM_SOURCE,
		'utm_campaign' => OPINIONSTAGE_UTM_CAMPAIGN,
		'utm_medium'   => OPINIONSTAGE_UTM_MEDIUM,
		'o'            => OPINIONSTAGE_WIDGET_API_KEY,
	);

	return http_build_query( array_merge( $query, $utm_query ) );
}

function opinionstage_utm_url( $path, $query = array() ) {
	return OPINIONSTAGE_SERVER_BASE . '/' . $path . '?' . opinionstage_utm_query( $query );
}

/**
 * Utility function to create a link with the correct host and all the required information.
 */
function opinionstage_link( $caption, $path, $css_class = '', $query = array() ) {
	$link = opinionstage_utm_url( $path, $query );

	return "<a href='{$link}' target='_blank' class='{$css_class}'>{$caption}</a>";
}

function opinionstage_help_links( $caption, $path, $css_class = '', $style = '', $query_data = array() ) {
	$link = $path . '?' . opinionstage_utm_query( $query_data );

	return "<a href='{$link}' target='_blank' class='{$css_class}' style='{$style}'>{$caption}</a>";
}

function opinionstage_register_javascript_asset( $name, $relative_path, $deps = array(), $in_footer = true ) {
	$registered = wp_register_script(
		opinionstage_asset_name( $name ),
		plugins_url( opinionstage_asset_path() . '/js/' . $relative_path, plugin_dir_path( __FILE__ ) ),
		$deps,
		OPINIONSTAGE_WIDGET_VERSION,
		$in_footer
	);

	if ( ! $registered ) {
		opinionstage_error_log( "javascript asset '$name' registration failed" );
	}
}

function opinionstage_register_css_asset( $name, $relative_path ) {
	wp_register_style(
		opinionstage_asset_name( $name ),
		plugins_url( opinionstage_asset_path() . '/css/' . $relative_path, plugin_dir_path( __FILE__ ) ),
		null,
		OPINIONSTAGE_WIDGET_VERSION
	);
}

function opinionstage_enqueue_js_asset( $name ) {
	wp_enqueue_script( opinionstage_asset_name( $name ) );
}

function opinionstage_enqueue_css_asset( $name ) {
	wp_enqueue_style( opinionstage_asset_name( $name ) );
}

function opinionstage_asset_name( $name ) {
	return 'opinionstage-' . $name;
}

function opinionstage_asset_path() {
	return is_admin() ? 'admin' : 'public';
}

/**
 * Generates a link for editing the flyout placement on Opinion Stage site
 */
function opinionstage_flyout_edit_url( $tab ) {
	$os_options = (array) get_option( OPINIONSTAGE_OPTIONS_KEY );
	return OPINIONSTAGE_SERVER_BASE . '/containers/' . $os_options['fly_id'] . '/edit?selected_tab=' . $tab;
}

/**
 * Generates a link for editing the article placement on Opinion Stage site
 */
function opinionstage_article_placement_edit_url( $tab ) {
	$os_options = (array) get_option( OPINIONSTAGE_OPTIONS_KEY );
	return OPINIONSTAGE_SERVER_BASE . '/containers/' . $os_options['article_placement_id'] . '/edit?selected_tab=' . $tab;
}

/**
 * Generates a link for editing the sidebar placement on Opinion Stage site
 */
function opinionstage_sidebar_placement_edit_url( $tab ) {
	$os_options = (array) get_option( OPINIONSTAGE_OPTIONS_KEY );
	return OPINIONSTAGE_SERVER_BASE . '/containers/' . $os_options['sidebar_placement_id'] . '/edit?selected_tab=' . $tab;
}

function opinionstage_template_poll_link( $css_class, $title = 'USE A TEMPLATE' ) {
	return opinionstage_link( $title, 'dashboard/content/templates?types%5B%5D=poll', $css_class );   // %5B%5D --> []
}

function opinionstage_template_survey_link( $css_class, $title = 'USE A TEMPLATE' ) {
	return opinionstage_link( $title, 'dashboard/content/templates?types%5B%5D=survey', $css_class ); // %5B%5D --> []
}

function opinionstage_template_trivia_link( $css_class, $title = 'USE A TEMPLATE' ) {
	return opinionstage_link( $title, 'dashboard/content/templates?types%5B%5D=trivia_quiz', $css_class );    // %5B%5D --> []
}

function opinionstage_template_personality_quiz_link( $css_class, $title = 'USE A TEMPLATE' ) {
	return opinionstage_link( $title, 'dashboard/content/templates?types%5B%5D=personality_quiz', $css_class );   // %5B%5D --> []
}

function opinionstage_template_slideshow_link( $css_class, $title = 'USE A TEMPLATE' ) {
	return opinionstage_link( $title, 'dashboard/content/templates?types%5B%5D=slideshow', $css_class );  // %5B%5D --> []
}

function opinionstage_template_form_link( $css_class, $title = 'USE A TEMPLATE' ) {
	return opinionstage_link( $title, 'dashboard/content/templates?types%5B%5D=form', $css_class );   // %5B%5D --> []
}

function opinionstage_template_list_link( $css_class, $title = 'USE A TEMPLATE' ) {
	return opinionstage_link( $title, 'dashboard/content/templates?types%5B%5D=list', $css_class );   // %5B%5D --> []
}

function opinionstage_template_story_link( $css_class, $title = 'USE A TEMPLATE' ) {
	return opinionstage_link( $title, 'dashboard/content/templates?types%5B%5D=story', $css_class );  // %5B%5D --> []
}

function opinionstage_create_widget_link( $w_type, $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_link( $title, 'api/wp/redirects/widgets/new', $css_class, array( 'w_type' => $w_type ) );
}

function opinionstage_create_poll_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'poll', $css_class, $title );
}

function opinionstage_create_personality_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'outcome', $css_class, $title );
}

function opinionstage_create_trivia_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'quiz', $css_class, $title );
}

function opinionstage_create_survey_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'survey', $css_class, $title );
}

function opinionstage_create_form_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'contact_form', $css_class, $title );
}

function opinionstage_create_slideshow_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'slideshow', $css_class, $title );
}

/**
 * Generates a to the callback page used to connect the plugin to the Opinion Stage account
 */
function opinionstage_callback_url() {
	return get_admin_url( '', '', 'admin' ) . 'admin.php?page=' . OPINIONSTAGE_LOGIN_CALLBACK_SLUG;
}

/**
 * Take the received data and parse it
 *
 * Returns the newly updated widgets parameters.
 */
function opinionstage_parse_client_data( $raw_data ) {
	$os_options = array(
		'uid'                      => $raw_data['uid'],
		'email'                    => $raw_data['email'],
		'fly_id'                   => $raw_data['fly_id'],
		'article_placement_id'     => $raw_data['article_placement_id'],
		'sidebar_placement_id'     => $raw_data['sidebar_placement_id'],
		'version'                  => OPINIONSTAGE_WIDGET_VERSION,
		'fly_out_active'           => 'false',
		'article_placement_active' => 'false',
		'sidebar_placement_active' => 'false',
		'token'                    => $raw_data['token'],
	);
	$valid_ids  = preg_match( '/^[0-9]+$/', $raw_data['fly_id'] ) && preg_match( '/^[0-9]+$/', $raw_data['article_placement_id'] ) && preg_match( '/^[0-9]+$/', $raw_data['sidebar_placement_id'] );
	if ( $valid_ids ) {
		update_option( OPINIONSTAGE_OPTIONS_KEY, $os_options );
	}
}

function opinionstage_custom_content_popup_callback_url() {
	$protocol      = ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] === 443 ) ? 'https://' : 'http://';
	$url           = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$adminUrlForOs = admin_url( 'admin.php?page="' . OPINIONSTAGE_CONTENT_LOGIN_CALLBACK_SLUG . '"&return_path=', $protocol );
	return $adminUrlForOs . urlencode( $url );
}
