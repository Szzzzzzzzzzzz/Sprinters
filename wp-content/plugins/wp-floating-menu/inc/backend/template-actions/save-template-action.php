<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" );

global $wpdb;
if(isset($_POST['wpfm_add_nonce_save_template_settings']) && isset($_POST['wpfm_save_template_settings']) && wp_verify_nonce($_POST['wpfm_add_nonce_save_template_settings'], 'wpfm_nonce_save_template_settings')&& current_user_can('manage_options'))     
{
foreach($_POST as $key=>$val)
{
if($key == 'custom_template'){
	$$key = $val;
    } else {
		$$key = sanitize_text_field( $val );
	}
}
    /** Sanitizing each form fields for Menu field added */

	$custom_template_array = array();
    $custom_template_array['custom_template'] = array_map( 'sanitize_text_field', $custom_template );
    $custom_template_name = sanitize_text_field($_POST['wpfm-template-title-name']);
    $table_name = $wpdb->prefix . "wp_floating_menu_custom_templates";    
    $insert = $wpdb->query($wpdb->prepare
        ("INSERT INTO ".$table_name."
        	(template_name, template_details)
        	   VALUES(%s, %s)",
                	array(
                    $custom_template_name,                                                            
                    maybe_serialize($custom_template_array)                                        
	           )
            )
       );
    if($insert){
        wp_redirect( admin_url() . 'admin.php?page=wpfm-custom-template&message=1');
    }else{
        wp_redirect( admin_url() . 'admin.php?page=wpfm-custom-template&message=2');
    }
 }
/** Condition to save current template setting values */
if(isset($_POST['wpfm_add_nonce_save_edited_template_settings']) && isset($_POST['wpfm_save_edited_template_settings']) && wp_verify_nonce($_POST['wpfm_add_nonce_save_edited_template_settings'], 'wpfm_nonce_save_edited_template_settings') && current_user_can('manage_options'))
{
 foreach($_POST as $key=>$val)
{
if ($key == 'custom_template'){
        $$key = $val;
	} else {
		$$key = sanitize_text_field( $val );
	}
}
    $custom_template_array = array();
    $custom_template_array['custom_template'] = array_map( 'sanitize_text_field', $custom_template );
    $custom_template_name = sanitize_text_field($_POST['wpfm-template-title-name']);
    $current_template_id = $_POST['current_template_id'];   
    $table_name = $wpdb->prefix . "wp_floating_menu_custom_templates";    
    $update = $wpdb->update($table_name, 
        array(
            'template_name' =>$custom_template_name,
            'template_details'=>maybe_serialize($custom_template_array)              
            ),
             array( 
                'ID' => $current_template_id            
                ),
                array(
                     '%s',
                     '%s'                     
                      ),
                      array( 
                        '%d' 
                ));
    if($update){
        wp_redirect( admin_url() . 'admin.php?page=wpfm-custom-template&action=wpfm-edit-template&id='.$current_template_id.'&message=1');
    }else{
        wp_redirect( admin_url() . 'admin.php?page=wpfm-custom-template&action=wpfm-edit-template&id='.$current_template_id.'&message=2');
    }   
}else{
  die( 'No script kiddies please!' );
 }