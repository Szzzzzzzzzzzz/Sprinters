<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
$os_client_logged_in = opinionstage_user_logged_in();						

?>
