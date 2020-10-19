<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" );

global $wpdb;
if( !empty( $_GET ) && wp_verify_nonce( $wpfm_delete_nonce, 'wpfm-remove-menu-settings-nonce' )){
$table_name = $wpdb->prefix . "wp_floating_menu_details";
if($_GET['id']){		
    $current_post_id=$_GET['id'];
    $wpdb->delete(
		$table_name,
		array(
			'ID' => $current_post_id
			) 
		); 
}
}else{
    die( 'No script kiddies please!' );
}