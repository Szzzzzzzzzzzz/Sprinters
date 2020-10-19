<?php
defined('ABSPATH') or die('No script kiddies please!');
global $wpdb;
$table_name = $wpdb->prefix . "wp_floating_menu_details";
$fid = intval($_GET['id']);
$menu_detail = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",array($fid)));
foreach ($menu_detail as $rows) {
    $id = intval($_GET['id']);
}
?>

<div class="form-body">
    <div class="wpfm-form-body-left">
        <form id="wpfm-update-nav-menu" class="wpfm-add-page-fields" method="post" enctype="multipart/form-data">
            <div class="wpfm-menu-list-field" id="wpfm-custom-link-field">
                <div class="wpfm-field-header" id="wpfm-custom-link-content">
                    <div class="wpfm-drag-icon"></div>
                    <h3 class="wpfm-menu-title"><?php _e('Add Link', 'wp-floating-menu'); ?></h3>
                    <span class="wpfm-arrow-down wpfm-arrow"></span>
                </div>
                <div class="wpfm-list-inner-content">
                    <div id="wpfm-custom-link-url" class="wpfm-custom-link">                                
                        <label for="wpfm_custom_link_url" class=""><?php _e('URL', 'wp-floating-menu'); ?></label>
                        <div class="wpfm-input-field-wrapper">
                            <input type="url" name="wpfm_custom_link_url" id="wpfm-custom-link-url"/>
                        </div>                             
                    </div>
                    <div id="wpfm-custom-link-text" class="wpfm-custom-link">                                
                        <label for="wpfm_custom_link_text" class=""><?php _e('Link Text', 'wp-floating-menu'); ?></label>
                        <div class="wpfm-input-field-wrapper">
                            <input type="text" name="wpfm_custom_link_text" id="wpfm-custom-link-text"/>
                        </div>                             
                    </div>
                    <p class="button-controls wp-clearfix">
                        <span class="spinner wpfm-view-wrap is-active" style="display:none;"></span> 
                        <span class="wpfm-add-to-menu">
                            <input type="button" field-data="<?php _e('Custom Link', 'wp-floating-menu'); ?>" submit-field-val="2" class="button-secondary wpfm-submit-add-to-menu"  id="wpfm-submit-posttype-custom-link" value="Add to Menu" name="wpfm_add_post_type_custom_link"/>
                        </span>                
                    </p>
                </div>
            </div><!-- #wpfm-custom-link-field .wpfm-menu-list-field -->
        </form><!-- .wpfm-add-page-fields -->
    </div><!--wpfm-form-body-left -->

    <div class="wpfm-form-body-right">
        <div class="wpfm-form-body-right-inner-wrapper">
            <form action="<?php echo admin_url() . 'admin-post.php' ?>" method='post' class="wpfm-menu-structure">
                <div class='wpfm-tab-contents' id='tab-wpfm-add-menu'> 
                    <input type="hidden" name="action" value="wpfm_save_menu_field_options" />
                    <div class="wpfm-upper-body-section">
                        <div class="wpfm-add-new-form">
                            <label for="wpfm_save_menu_name" class="wpfm-input-field-controller"><?php _e('Menu Name', 'wp-floating-menu'); ?></label>
                            <div class="wpfm-input-field-wrapper">
                                <input type="text" name="wpfm_save_menu_name" class="wpfm-menu-name" id="wpfm-menu-name" value="<?php echo esc_attr($rows->menu_name); ?>"/>
                            </div>
                        </div>
                        <div class="wpfm-publishing-action" id="wpfm-save-form-upper">
                            <input type="submit" name="wpfm_save_menu_fields" id="wpfm-save-menu-header" class="button button-primary wpfm-menu-field-save" value="Save Menu"/>
                        </div>
                    </div>
                    <div id="post-body-content" class="wp-clearfix">
                        <h3 class="wpfm-menu-name"><?php _e('Menu Structure', 'wp-floating-menu'); ?></h3>
                        <div class="drag-instructions post-body-plain">
<?php _e('<p class="menu-field-info">Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.</p>', 'wp-floating-menu'); ?> 
                        </div>
                        <div class="wpfm-menu-temp-holder" style="display:none;"></div>
                        <div class="wpfm-sortable-menu-field">
                            <?php
                            $menu_details = maybe_unserialize($rows->menu_details);
                            $keyArray = array();
                            if (!empty($menu_details)) {
                                foreach ($menu_details as $key => $val) {
                                    $keyArray[$key] = $key;
                                    ?>                                
                                    <ul class="wpfm-menu ui-sortable" id="wpfm-menu-to-edit">
                                        <li id="wpfm-menu-item" class="wpfm-menu-item">
                                            <div class="wpfm-menu-item-bar">
                                                <div class="wpfm-menu-item-handle">
                                                    <span class="wpfm-item-title">
                                                        <span class="wpfm-menu-item-title"><?php echo esc_attr($val['wpfm_menu_item_title']); ?></span> 
                                                    </span>
                                                    <span class="wpfm-item-type"><?php echo esc_attr($val['field_data']); ?></span>
                                                    <span class="wpfm-ind-menu-toggle-icon"><i class="fa fa-sort-down"></i></span>                   
                                                </div>
                                            </div>
                                            <div class="wpfm-menu-item-settings" id="wpfm-menu-item-settings" style="display:none">
                                                <div class="wpfm-description wpfm-description-wide">
                                                    <label class="wpfm-menu-label-controller" for="wpfm_menu_item_title"><?php _e('Navigation Label', 'wp-floating-menu'); ?></label>
                                                    <div class="menu-inner-input-field">
                                                        <input type="text" id="wpfm-edit-menu-item-item-title" class="wpfm-edit-menu-item-title" name="menu_structure[<?php echo esc_attr($key); ?>][wpfm_menu_item_title]" data-field-name="menu_id" value="<?php echo esc_attr($val['wpfm_menu_item_title']); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="wpfm-description wpfm-description-wide" id="wpfm-menu-item-type-field">
                                                    <label><label class="wpfm-menu-label-controller">                  
                                                            <input type="checkbox" id="wpfm-tooltip-show-hide" value="1" name="menu_structure[<?php echo esc_attr($key); ?>][wpfm_title_show]" data-field-name="menu_id" <?php
                                                            if (isset($val['wpfm_title_show']) && $val['wpfm_title_show'] == '1') {
                                                                echo 'checked="checked"';
                                                            }
                                                            ?>/>                       					
        <?php _e('Show Navigation Label', 'wp-floating-menu'); ?></label>
                                                    </label>
                                                </div>
                                                <div class="wpfm-description wpfm-description-wide">
                                                    <label class="wpfm-menu-label-controller" for="wpfm_menu_item_title_attribute"><?php _e('Title Attribute', 'wp-floating-menu'); ?></label>
                                                    <div class="menu-inner-input-field">
                                                        <input type="text" id="wpfm-edit-menu-title-attribute" class="wpfm-edit-menu-item-title-attribute" name="menu_structure[<?php echo esc_attr($key); ?>][wpfm_menu_item_title_attribute]" data-field-name="menu_id" value="<?php echo esc_attr($val['wpfm_menu_item_title_attribute']); ?>"/>
                                                    </div>
                                                </div>

                                                <div class="wpfm-description wpfm-description-wide">
                                                    <label class="wpfm-menu-tooltip-title" for="wpfm_menu_tooltip_title"><?php _e('Tooltip Title', 'wp-floating-menu'); ?></label>
                                                    <div class="menu-inner-input-field">
                                                        <input type="text" id="wpfm-edit-menu-title-attribute" class="wpfm-edit-menu-tooltip-title" name="menu_structure[<?php echo esc_attr($key); ?>][wpfm_menu_tooltip_title]" data-field-name="menu_id" value="<?php echo esc_attr($val['wpfm_menu_tooltip_title']); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="wpfm-description wpfm-description-wide" id="wpfm-menu-item-type-field">
                                                    <label><label class="wpfm-menu-label-controller">
                                                            <input type="checkbox" id="wpfm-tooltip-show-hide" value="1" <?php
                                                            if (isset($val['wpfm_tooltip_show']) && $val['wpfm_tooltip_show'] == '1') {
                                                                echo 'checked="checked"';
                                                            }
                                                            ?> name="menu_structure[<?php echo esc_attr($key); ?>][wpfm_tooltip_show]" data-field-name="menu_id"/>
        <?php _e('Show Tooltip Title', 'wp-floating-menu'); ?></label>					
                                                    </label>
                                                </div>

                                                <div class="wpfm-field-targer-link wpfm-description-wide">
                                                    <label for="wpfm_target_link_address"><?php _e('Target Link', 'wp-floating-menu'); ?></label>
                                                    <div class="menu-inner-input-field">
                                                        <input type="text" id="wpfm-edit-menu-item-title" class="wpfm-edit-menu-item-title" name="menu_structure[<?php echo esc_attr($key); ?>][wpfm_target_link_address]"  data-field-name="menu_id" value="<?php echo esc_attr($val['wpfm_target_link_address']); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="wpfm-description wpfm-description-wide" id="wpfm-menu-item-type-field">
                                                    <label><label class="wpfm-menu-label-controller">
                                                            <input type="checkbox" <?php
                                                            if (isset($val['wpfm_field_link_target']) && $val['wpfm_field_link_target'] == '1') {
                                                                echo 'checked="checked"';
                                                            }
                                                            ?> id="wpfm-field-link-target" value="1" name="menu_structure[<?php echo esc_attr($key); ?>][wpfm_field_link_target]" data-field-name="menu_id" />
        <?php _e('Open Link in New Tab', 'wp-floating-menu'); ?></label>					
                                                    </label>
                                                </div>
                                                <div class="wpfm-description wpfm-description-wide wpfm-field-targer-link">
                                                    <label for="wpfm_target_class"><?php _e('Custom Class', 'wp-floating-menu'); ?></label>
                                                    <div class="menu-inner-input-field">
                                                        <input type="text" id="wpfm-edit-menu-item-class" class="wpfm-edit-menu-item-class" name="menu_structure[<?php echo esc_attr($key); ?>][wpfm_custom_class]"  data-field-name="menu_id" value="<?php echo isset($val['wpfm_custom_class']) && !empty($val['wpfm_custom_class']) ? esc_attr($val['wpfm_custom_class']) : ''; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="wpfm-description wpfm-description-wide wpfm-menu-icon-type-field" id="wpfm-field-add-menu-type">
                                                    <label for="wpfm_edit_menu_icons_type"><?php _e('Menu Icon Type:', 'wp-floating-menu-pro'); ?></label>
                                                    <select name="menu_structure[<?php echo esc_attr($key); ?>][icon_icon_type]"  data-field-name="menu_id" class="wpfm-icon-type">
                                                        <option value="default" <?php if (isset($val['icon_icon_type']) && $val['icon_icon_type'] == 'default') { ?>selected="selected"<?php } ?>><?php _e('Use Default Icon', 'wp-floating-menu'); ?></option>
                                                        <option value="custom" <?php if (isset($val['icon_icon_type']) && $val['icon_icon_type'] == 'custom') { ?>selected="selected"<?php } ?>><?php _e('Use Custom Icon', 'wp-floating-menu'); ?></option>
                                                    </select>	
                                                </div>
                                                <div class="wpfm-description wpfm-description-wide wpfm-menu-icon-field" id="wpfm-field-add-menu-icons"
        <?php if (isset($val['icon_icon_type']) && $val['icon_icon_type'] == 'default' || !isset($val['icon_icon_type'])) { ?>style="display:block"<?php } else { ?>style="display:none"<?php } ?>>
                                                    <label for="wpfm_edit_menu_icons"><?php _e('Default Icon:', 'wp-floating-menu'); ?></label>
                                                    <span>
                                                        <input class="wpfm-icon-picker" type="hidden" id="wpfm-icon-picker-icon_<?php echo esc_attr($key); ?>" name="menu_structure[<?php echo esc_attr($key); ?>][icon_picker_settings]" data-field-name="menu_id" value="<?php echo esc_attr($val['icon_picker_settings']); ?>"/>
                                                        <div id="wpfm-menu-icon-div" data-target="#wpfm-icon-picker-icon_<?php echo esc_attr($key); ?>" class="button icon-picker <?php
                                                        if (isset($val['icon_picker_settings']) && !empty($val['icon_picker_settings'])) {
                                                            $v = explode('|', $val['icon_picker_settings']);
                                                            echo $v[0] . ' ' . $v[1];
                                                        }
                                                        ?>"><?php _e('Select Icon', 'wp-floating-menu-pro'); ?></div>
                                                    </span>
                                                </div>
                                                <div class="wpfm-description wpfm-description-wide wpfm-menu-icon-field" id="wpfm-field-add-custom-menu-icons"  <?php if (isset($val['icon_icon_type']) && $val['icon_icon_type'] == 'custom') { ?>style="display:block"<?php } else { ?>style="display:none"<?php } ?>>
                                                    <label for="icon_picker_custom"><?php _e('Custom Icon:', 'wp-floating-menu'); ?></label>
                                                    <span>
                                                        <input class="wpfm-icon-custom" type="text" id="wpfm-icon-picker-custom" name="menu_structure[<?php echo esc_attr($key); ?>][icon_picker_custom]" data-field-name="menu_id" value="<?php
                                                        if (isset($val['icon_picker_custom']) && !empty($val['icon_picker_custom'])) {
                                                            echo esc_attr($val['icon_picker_custom']);
                                                        }
                                                        ?>" placeholder="Eg:- fa fa-bars"/>
                                                    </span>
                                                </div>           
                                                <div class="wpfm-field-remove">
                                                    <a href="#" class="wpfm-remove-menu"><?php _e('Remove', 'wp-floating-menu'); ?></a>
                                                </div>             
                                            </div><!-- .menu-item-settings-->

                                            <input type="hidden" name="menu_structure[<?php echo esc_attr($key); ?>][item_value]"  data-field-name="menu_id" value="<?php echo esc_attr($val['item_value']); ?>" />
                                            <input type="hidden" name="menu_structure[<?php echo esc_attr($key); ?>][field_data]"  data-field-name="menu_id" value="<?php echo esc_attr($val['field_data']); ?>" />
                                        </li>
                                    </ul>
                                    <?php
                                }
                            }
                            $max_key = !empty($keyArray) ? array_keys($keyArray, max($keyArray)) : array('0' => '0');
                            ?>
                            <input type="hidden" name="menu_count" value="<?php echo esc_attr($max_key[0]); ?>" class="wpfm-menu-count"/>
                        </div>
                        <input type="hidden" name="current_menu_id" value="<?php echo intval($_GET['id']); ?>" />
                        <input type="hidden" name="current_menu_name" value="<?php echo esc_attr($rows->menu_name); ?>" />                    
                    </div>
                </div>
                <div class="wpfm-setting-form" >
                    <div class='wpfm-tab-contents' id='tab-wpfm-display-setting' style="display:none">                 
<?php include( 'wpfm-display-setting.php' ); ?>
                    </div>
                </div>
                <div class="wpfm-right-body-footer">
                    <div class="wpfm-publishing-action" id="wpfm-save-form-lower">
                        <input type="submit" name="wpfm_save_menu_fields" id="wpfm-save-menu-footer" class="button button-primary wpfm-menu-field-save" value="Save Menu"/>
                    </div>
<?php wp_nonce_field('wpfm_nonce_save_menu_fields', 'wpfm_add_nonce_save_menu_fields'); ?>
                </div>
            </form><!-- #wpfm-update-nav-menu .wpfm-save-menu -->
        </div><!-- .wpfm-form-body-right-inner-wrapper -->
    </div><!-- .wpfm-form-body-right -->
</div><!-- .Form-Body --> 