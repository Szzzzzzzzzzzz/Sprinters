<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

function opinionstage_getting_started_load_resources(){
	// load assets here
	wp_enqueue_script( 'oswp_script_sweetalert_min', plugins_url('js/sweetalert.min.js', dirname( __FILE__ )  ), array(), '',true );
}

function opinionstage_getting_started_load_header(){
	// load anything in header here
}

function opinionstage_getting_started_load_footer(){
	// load anything in footer here
}
?>