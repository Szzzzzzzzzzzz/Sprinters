<?php
/**
 * User login-related functions, this is the only legitimate source of user logged in state.
 *
 * @package   OpinionStageWordPressPlugin
 */

defined( 'ABSPATH' ) || die(); // block direct access to plugin PHP files.

/**
 * Function, which answers to question "is user logged in?".
 *
 * @return bool True is user is logged in.
 */
function opinionstage_user_logged_in() {
	$os_options = (array) get_option( OPINIONSTAGE_OPTIONS_KEY );

	return isset( $os_options['uid'] ) && isset( $os_options['email'] );
}

/**
 * Get access to user token, in order to communicate to OpinionStage APIs.
 *
 * @return string|null User token or null if not found.
 */
function opinionstage_user_access_token() {
	$os_options = (array) get_option( OPINIONSTAGE_OPTIONS_KEY );

	if ( isset( $os_options['token'] ) ) {
		return $os_options['token'];
	} else {
		return null;
	}
}
