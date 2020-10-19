<?php defined('ABSPATH') or die("No script kiddies please!"); ?>

<?php
$submit_field_data = sanitize_text_field($_POST['submit_field_data']);
if ($submit_field_data == 2) {
    $field_data = sanitize_text_field($_POST['custom_link_field_data']);
    $custom_link_name = sanitize_text_field($_POST['custom_link_name']);
    $custom_link_url = sanitize_text_field($_POST['custom_link_url']);
    $page_title = $custom_link_name;
    $page_permalink = $custom_link_url;
}
?>
<ul class="wpfm-menu ui-sortable" id="wpfm-menu-to-edit">
    <li id="wpfm-menu-item" class="wpfm-menu-item">
        <div class="wpfm-menu-item-bar">
            <div class="wpfm-menu-item-handle">
                <span class="wpfm-item-title">
                    <span class="wpfm-menu-item-title"><?php echo esc_attr($page_title); ?></span> 
                </span>
                <span class="wpfm-item-type"><?php echo esc_attr($field_data); ?></span>
                <span class="wpfm-ind-menu-toggle-icon"><i class="fa fa-sort-down"></i></span>                   
            </div>
        </div>
        <div class="wpfm-menu-item-settings" id="wpfm-menu-item-settings" style="display:none">
            <div class="wpfm-description wpfm-description-wide">
                <label class="wpfm-menu-label-controller" for="wpfm_menu_item_title"><?php _e('Navigation Label', 'wp-floating-menu'); ?></label>
                <div class="menu-inner-input-field">
                    <input type="text" id="wpfm-edit-menu-item-item-title" class="wpfm-edit-menu-item-title" name="menu_structure[menu_id][wpfm_menu_item_title]" value="<?php echo esc_attr($page_title); ?>" data-field-name="menu_id" />
                </div>
            </div>
            <div class="wpfm-description wpfm-description-wide" id="wpfm-menu-item-type-field">
                <label><label class="wpfm-menu-label-controller">                  
                        <input type="checkbox" id="wpfm-tooltip-show-hide" value="1" name="menu_structure[menu_id][wpfm_title_show]" data-field-name="menu_id"/>                       					
                        <?php _e('Show Navigation Label', 'wp-floating-menu'); ?></label>
                </label>
            </div>
            <div class="wpfm-description wpfm-description-wide">
                <label class="wpfm-menu-label-controller" for="wpfm_menu_item_title_attribute"><?php _e('Title Attribute', 'wp-floating-menu'); ?></label>
                <div class="menu-inner-input-field">
                    <input type="text" id="wpfm-edit-menu-title-attribute" class="wpfm-edit-menu-item-title-attribute" name="menu_structure[menu_id][wpfm_menu_item_title_attribute]" data-field-name="menu_id" />
                </div>
            </div>
            <div class="wpfm-description wpfm-description-wide">
                <label class="wpfm-menu-tooltip-title" for="wpfm_menu_tooltip_title"><?php _e('Tooltip Title', 'wp-floating-menu'); ?></label>
                <div class="menu-inner-input-field">
                    <input type="text" id="wpfm-edit-menu-title-attribute" class="wpfm-edit-menu-tooltip-title" name="menu_structure[menu_id][wpfm_menu_tooltip_title]" data-field-name="menu_id" />
                </div>
            </div>
            <div class="wpfm-description wpfm-description-wide" id="wpfm-menu-item-type-field">
                <label><label class="wpfm-menu-label-controller">                  
                        <input type="checkbox" id="wpfm-tooltip-show-hide" value="1" name="menu_structure[menu_id][wpfm_tooltip_show]" data-field-name="menu_id"/>                       					
                        <?php _e('Show Tooltip Title', 'wp-floating-menu'); ?></label>
                </label>
            </div>
            <div class="wpfm-description wpfm-description-wide wpfm-field-targer-link">
                <label for="wpfm_target_link_address"><?php _e('Target Link', 'wp-floating-menu'); ?></label>
                <div class="menu-inner-input-field">
                    <input type="text" id="wpfm-edit-menu-item-title" class="wpfm-edit-menu-item-title" name="menu_structure[menu_id][wpfm_target_link_address]"  data-field-name="menu_id" value="<?php echo esc_url($page_permalink); ?>"/>
                </div>
            </div>
            <div class="wpfm-description wpfm-description-wide" id="wpfm-menu-item-type-field">
                <label><label class="wpfm-menu-label-controller">                        
                        <input type="checkbox" class="wpfm-checkbox" id="wpfm-field-link-target" value="1" name="menu_structure[menu_id][wpfm_field_link_target]" data-field-name="menu_id" />                				
                        <?php _e('Open Link in New Tab', 'wp-floating-menu'); ?></label>
                </label>
            </div>

            <div class="wpfm-description wpfm-description-wide wpfm-menu-icon-type-field" id="wpfm-field-add-menu-type">
                <label for="wpfm_edit_menu_icons_type"><?php _e('Menu Icon Type:', 'wp-floating-menu'); ?></label>
                <select name="menu_structure[menu_id][icon_icon_type]"  data-field-name="menu_id" class="wpfm-icon-type">
                    <option value="default"><?php _e('Use Default Icon', 'wp-floating-menu'); ?></option>
                    <option value="custom"><?php _e('Use Custom Icon', 'wp-floating-menu'); ?></option>
                </select>	
            </div>
            <div class="wpfm-description wpfm-description-wide wpfm-menu-icon-field" id="wpfm-field-add-menu-icons">
                <label for="wpfm_edit_menu_icons"><?php _e('Default Icon:', 'wp-floating-menu'); ?></label>
                <span>
                    <input class="wpfm-icon-picker" type="hidden" id="wpfm-icon-picker-icon" name="menu_structure[menu_id][icon_picker_settings]" data-field-name="menu_id"/>
                    <div id="wpfm-menu-icon-div" data-target="#wpfm-icon-picker-icon" class="button icon-picker <?php
                    if (isset($options['icon'])) {
                        $v = explode('|', $options['icon']);
                        echo $v[0] . ' ' . $v[1];
                    }
                    ?>"><?php _e('Select Icon', 'wp-floating-menu'); ?></div>
                </span>
            </div>
            <div class="wpfm-description wpfm-description-wide wpfm-menu-icon-field" id="wpfm-field-add-custom-menu-icons" style="display: none;">
                <label for="icon_picker_custom"><?php _e('Custom Icon:', 'wp-floating-menu'); ?></label>
                <span>
                    <input class="wpfm-icon-custom" type="text" id="wpfm-icon-picker-custom" name="menu_structure[menu_id][icon_picker_custom]" data-field-name="menu_id" placeholder="Eg:- fa fa-bars"/>
                </span>
            </div>

            <div class="wpfm-field-remove">
                <a href="#" class="wpfm-remove-menu"><?php _e('Remove', 'wp-floating-menu'); ?></a>
            </div>            
        </div><!-- .menu-item-settings-->

        <input type="hidden" name="menu_structure[menu_id][item_value]"  data-field-name="menu_id" value="<?php echo esc_attr($page_title); ?>" />
        <input type="hidden" name="menu_structure[menu_id][field_data]"  data-field-name="menu_id" value="<?php echo esc_attr($field_data); ?>" />
    </li>
</ul>