<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;
if(isset($_POST['wpfm_add_nonce_add_menu_fields']) && isset($_POST['wpfm_add_menu_fields']) && wp_verify_nonce($_POST['wpfm_add_nonce_add_menu_fields'], 'wpfm_nonce_add_menu_fields') && current_user_can('manage_options'))
{            
$menu_name = sanitize_text_field($_POST['wpfm_save_menu_name']);  
   $table_name = $wpdb->prefix . "wp_floating_menu_details";    
    $insert = $wpdb->query($wpdb->prepare
        ("INSERT INTO ".$table_name."
        	(menu_name)
        	   VALUES(%s)",
                	array(
                    $menu_name
	           )
            )
       );
        if($insert){
        $lastid = $wpdb->insert_id;
        wp_redirect( admin_url() . 'admin.php?page=wpfm-admin&action=wpfm-edit-menu&id='.$lastid.'&message=4');
        }
}else{
  die( 'No script kiddies please!' );
}