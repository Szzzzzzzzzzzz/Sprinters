<?php

defined('ABSPATH') or die("No script kiddies please!");

global $wpdb;
if (!empty($_GET) && wp_verify_nonce($wpfm_delete_template_nonce, 'wpfm-remove-template-settings-nonce') && current_user_can('manage_options')) {
    $table_name = $wpdb->prefix . "wp_floating_menu_custom_templates";
    if ($_GET['id']) {

        $current_post_id = intval($_GET['id']);
        $wpdb->delete(
                $table_name, array(
            'ID' => $current_post_id
                )
        );
    }
} else {
    die('No script kiddies please!');
}