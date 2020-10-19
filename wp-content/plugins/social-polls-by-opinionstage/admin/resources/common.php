<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

function opinionstage_common_load_resources(){
	
	// Register common assets for admin pages
	opinionstage_register_css_asset( 'menu-page', 'menu-page.css' );
	opinionstage_register_css_asset( 'icon-font', 'icon-font.css' );
	opinionstage_register_javascript_asset( 'menu-page', 'menu-page.js', array('jquery') );

	// Load common assets
	opinionstage_enqueue_css_asset('menu-page');
	opinionstage_enqueue_css_asset('icon-font');
	opinionstage_enqueue_js_asset('menu-page'); ?>
<?php }

function opinionstage_common_load_header(){

}
function opinionstage_common_load_footer(){ 

}						
?>