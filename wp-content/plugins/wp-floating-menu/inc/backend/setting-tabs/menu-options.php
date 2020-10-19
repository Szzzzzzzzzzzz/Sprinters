<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<div class="wpfm-wrapper wpfm-clear">
    <div class="wpfm-head">     
        <?php include(WPFM_FILE_ROOT_DIR . 'inc/backend/includes/wpfm-header.php'); ?> 
    </div> 
    <?php
    $options = get_option(WPFM_SETTINGS);
    ?>
    <div class="wpfm-inner-wrapper" id="poststuff">
        <div id="post-body" class="metabox-holder columns-2 wpfm-menu-option">
            <div id="post-body-content">
                <div class="postbox">
                    <div class="wpfm-menu-option-wrapper clearfix" id="col-container">
                        <div class="inside" id="wpfm-menu-setting-wrapper">
                            <div class="wpfm-header-title wpfm-menu-option-header-title">
                                <?php _e('Menu Options', 'wpfm-floating-menu'); ?>
                            </div>
                            <?php if (isset($_GET['message']) && $_GET['message'] == 1) { ?>
                                <div class="wpfm-message notice notice-success is-dismissible">
                                    <p>
                                        <?php
                                        echo __('Setting Saved.', 'wp-floating-menu');
                                        ?>
                                    </p>
                                </div>
                            <?php } ?> 
                        </div>
                        <?php
                        global $wpdb;
                        $table_name = $wpdb->prefix . "wp_floating_menu_details";
                        $menu_details = $wpdb->get_results("SELECT * FROM $table_name");
                        ?>
                        <form action="<?php echo admin_url() . 'admin-post.php' ?>" method='post' id="wpfm-menu-option-form">     
                            <input type="hidden" name="action" value="wpfm_save_menu_show_options" />
                            <div class="wpfm-menu-option-setting-wrapper">
                                <div class="wpfm-menu-option-setting-field">
                                    <label class="menu-option-label-controller">
                                        <h4><?php _e('Enable/Disable Menu', 'wp-floating-menu'); ?></h4>
                                    </label>
                                    <label class="wpfm-switch">
                                        <input type="checkbox" class="wpfm-checkbox" value="1" name="menu_enable_disable" <?php if (isset($options['menu_enable_disable']) && $options['menu_enable_disable'] == '1') { ?>checked="checked"<?php } ?>/>
                                        <div class="wpfm-slider round"></div>
                                    </label>
                                </div>
                                <div class="wpfm-menu-option-setting-field">
                                    <label class="menu-option-label-controller">
                                        <h4><?php _e('Enable/Disable For Mobile', 'wp-floating-menu'); ?></h4>
                                    </label>
                                    <label class="wpfm-switch">                                        
                                        <input type="checkbox" class="wpfm-checkbox" value="1" name="mobile_menu_enable_disable" <?php if (isset($options['mobile_menu_enable_disable']) && $options['mobile_menu_enable_disable'] == '1') { ?>checked="checked"<?php } ?>/>
                                        <div class="wpfm-slider round"></div>
                                    </label>
                                    <div class="wpfm-tooltip-description">
                                        <label class="wpfm-tt-description">
                                            <?php _e('For screen less than 480px.', 'wp-floating-menu'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="wpfm-menu-option-setting-field">
                                    <label class="menu-option-label-controller">
                                        <h4><?php _e('Disable Search Engine Indexing', 'wp-floating-menu'); ?></h4>
                                    </label>
                                    <label class="wpfm-switch">                                        
                                        <input type="checkbox" class="wpfm-checkbox" value="1" name="menu_link_add_nofollow" <?php if (isset($options['menu_link_add_nofollow']) && $options['menu_link_add_nofollow'] == '1') { ?>checked="checked"<?php } ?>/>
                                        <div class="wpfm-slider round"></div>
                                    </label>
                                    <div class="wpfm-tooltip-description">
                                        <label class="wpfm-tt-description">
                                            <?php _e('If checked, "nofollow" will be added to menu links and willnot be indexed by search engines.', 'wp-floating-menu'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="wpfm-menu-option-setting-field">
                                    <label class="menu-option-label-controller">
                                        <h4><?php _e('Smart Scroll By Offset', 'wp-floating-menu'); ?></h4>
                                    </label>
                                    <label class="wpfm-switch">
                                        <input type="checkbox" class="wpfm-checkbox" value="1" name="menu_enable_offset_to_position" <?php if (isset($options['menu_enable_offset_to_position']) && $options['menu_enable_offset_to_position'] == '1') { ?>checked="checked"<?php } ?>/>
                                        <div class="wpfm-slider round"></div>
                                    </label>
                                    <div class="wpfm-tooltip-description">
                                        <label class="wpfm-tt-description">
                                            <?php _e('If checked, "Offset" will be set instead of "Position". Default: Position. More info in "How To Use"', 'wp-floating-menu'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="wpfm-menu-option-setting-field">
                                    <label class="menu-option-label-controller">
                                        <h4><?php _e('Select Default Menu', 'wp-floating-menu'); ?></h4>
                                    </label>
                                    <div class="menu-option-inner-input">
                                        <select name="menu_list_select" class="wpfm-display-menu-optionss" >
                                            <option value="default" <?php if (isset($options['menu_list_selected']) && $options['menu_list_selected'] == 'default') { ?>selected="selected"<?php } ?>><?php echo _e('Default Menu', 'wp-floating-menu'); ?></option>
                                            <?php foreach ($menu_details as $menu_detail) { ?>                                    
                                            <option value="<?php echo intval($menu_detail->id); ?>" <?php if (isset($options['menu_list_selected']) && $options['menu_list_selected'] == $menu_detail->id) { ?>selected="selected"<?php } ?>><?php echo esc_attr($menu_detail->menu_name); ?></option>                                        
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="wpfm-menu-option-setting-field">
                                    <label class="menu-option-label-controller">
                                        <h4><?php _e('Show Menu On', 'wp-floating-menu'); ?></h4>
                                    </label>
                                    <div class="menu-option-inner-input">
                                        <select name="menu_show_hide_on" class="wpfm-display-menu-option" >
                                            <option value="all-pages"  <?php if (isset($options['menu_show_hide_on']) && $options['menu_show_hide_on'] == 'all-pages') { ?>selected="selected"<?php } ?>><?php _e('Show on all Pages', 'wp-floating-menu'); ?></option>
                                            <option value="home-page"  <?php if (isset($options['menu_show_hide_on']) && $options['menu_show_hide_on'] == 'home-page') { ?>selected="selected"<?php } ?>><?php _e('Home page Only', 'wp-floating-menu'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="wpfm-menu-option-setting-field">
                                    <label class="menu-option-label-controller">
                                        <h4><?php _e('Disable Double Touch', 'wp-floating-menu'); ?></h4>
                                    </label>
                                    <label class="wpfm-switch">                                        
                                        <input type="checkbox" class="wpfm-checkbox" value="1" name="menu_disable_double_touch" <?php if (isset($options['menu_disable_double_touch']) && $options['menu_disable_double_touch'] == '1') { ?>checked="checked"<?php } ?>/>
                                        <div class="wpfm-slider round"></div>
                                    </label>
                                    <div class="wpfm-tooltip-description-free">
                                        <label class="wpfm-tt-description-free" style="font-style: italic;">
                                            <?php _e('Default: Enabled. When double touched on responsive device, first touch show the menu title or tooltip. And second touch wil activate the link click. Please check this setting if you intend to hide both menu title or tooltip.', 'wp-floating-menu'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php wp_nonce_field('wpfm_nonce_save_menu_show_settings', 'wpfm_add_nonce_save_menu_show_settings'); ?>
                            <input type="submit" class="button-primary" name='wpfm_save_menu_show_settings' value="<?php _e('Save Settings', 'wpfm-floating-menu'); ?>" />
                        </form>  
                    </div><!-- .inside #wpfm-menu-setting-wrapper-->
                </div><!--  .wpfm backend wrapper --> 
            </div><!-- .postbox -->
            <div id="postbox-container-1" class="postbox-container">
                <?php include(WPFM_FILE_ROOT_DIR . 'inc/backend/includes/wpfm-sidebar.php'); ?>
            </div> <!-- #postbox-container-1 .postbox-container -->
        </div> <!-- #post-body-content -->
    </div><!-- .metabox-holder columns-2 #post-body -->
</div><!-- .poststuff -->