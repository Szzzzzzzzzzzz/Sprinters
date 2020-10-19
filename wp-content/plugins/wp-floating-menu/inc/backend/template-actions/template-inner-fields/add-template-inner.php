<?php defined('ABSPATH') or die("No script kiddies please!"); ?>

<?php $wpfm_menu_fonts = get_option('wpfm_fmenu_fonts'); ?>
<div class="wpfm-display-setting-wrapper">
    <div class="wpfm-header-title"><?php _e('Add New Template', 'wp-floating-menu'); ?></div>
    <div class="wpfm-setting-body">
        <div class="wpfm-menu-field-wrapper" id="wpfm-template-layout">            
            <div class="wpfm-menu-display-setting" id="wpfm-menu-template-name">
                <label><?php _e('Template Name', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" class="wpfm-custom-template-name" name="wpfm-template-title-name" placeholder="<?php _e('E.g. Custom Template', 'wp-floating-menu'); ?>"/>
                </div>
                <div class="template-name-error" style="display: none; color:red;">
                    <p><?php _e('Please Enter Template Name', 'wp-floating-menu'); ?></p>
                </div>
            </div>
            <h3 class="wpfm-menu-field-header"><?php _e('Menu Layout', 'wp-floating-menu'); ?></h3>
            <div class="wpfm-menu-display-setting" id="wpfm-menu-layout">
                <label><?php _e('Select Menu Layout', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <select name="custom_template[menu_layout]" id="menu-template">
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <option class="custom-temp-layout" value="template-<?php echo $i; ?>"><?php
                                _e('Template', 'wp-floating-menu');
                                echo " " . $i;
                                ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="wpfm-template-demo" >
                    <?php for ($cnt = 1; $cnt <= 5; $cnt++) { ?>
                        <div class="wpfm-common" id="wpfm-temp-demo-<?php echo $cnt; ?>" <?php if ($cnt != 1) { ?>style="display:none;"<?php } ?>>
                            <p><?php _e('Template', 'wp-floating-menu'); ?>  <?php echo $cnt; ?>  <?php _e('Preview', 'wp-floating-menu'); ?></p>
                            <img src="<?php echo esc_attr(WPFM_IMAGE_DIR) . '/template-' . $cnt . '_1.png' ?>"/>
                            <img src="<?php echo esc_attr(WPFM_IMAGE_DIR) . '/template-' . $cnt . '_2.png' ?>"/>
                        </div>
                    <?php } ?> 
                </div>
            </div>
        </div>
        <div class="layout-5689-hidden-field" id="wpfm-general-layout" style="display:none;">                
            <div class="wpfm-menu-display-setting" id="wpfm-bg-color-wrap">
                <label><?php _e('Menu Background Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" name="custom_template[menu_bg_color]" id="menu-background-color" class="wpfm-colorpicker-trigger"/>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-stretchable-menu-direction">
                <label><?php _e('Icon For Expand Trigger', 'wp-floating-menu-pro'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input class="wpfm-icon-picker" type="hidden" id="wpfm-icon-picker-icon" name="custom_template[icon_expand]"/>
                    <div id="wpfm-ctemp-icon-div" data-target="#wpfm-icon-picker-icon" class="button icon-picker <?php if (isset($options['icon'])) {
                        $v = explode('|', $options['icon']);
                        echo $v[0] . ' ' . $v[1];
                    } ?>"><?php _e('Select Icon', 'wp-floating-menu-pro'); ?></div>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-stretchable-menu-direction">
                <label><?php _e('Icon For Expand Trigger', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input class="wpfm-icon-picker" type="hidden" id="wpfm-icon-picker-icon" name="custom_template[icon_expand]"/>
                    <div id="wpfm-ctemp-icon-div" data-target="#wpfm-icon-picker-icon" class="button icon-picker <?php
                    if (isset($options['icon'])) {
                        $v = explode('|', $options['icon']);
                        echo $v[0] . ' ' . $v[1];
                    }
                    ?>"><?php _e('Select Icon', 'wp-floating-menu'); ?></div>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-stretch-menu-icon-color">         
                <label><?php _e('Expand Trigger Icon Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-icon-str-tr-icon-color" name="custom_template[wpfm_stretch_icon_color]" class="wpfm-colorpicker-trigger"/>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-close-menu-icon-color">         
                <label><?php _e('Close Trigger Icon Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-icon-cls-tr-icon-color" name="custom_template[wpfm_close_icon_color]" class="wpfm-colorpicker-trigger"/>
                </div>
            </div>
        </div>
        <div class="layout-7-hidden-field" id="wpfm-nav-icon-hover-transform-wrap" style="display:none;">
            <div class="wpfm-menu-display-setting" id="wpfm-bg-color-wrap">
                <label><?php _e('Hover Transform Width', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field" id="wpfm-icon-hover">
                    <input type="number" id="wpfm-icon-hover-transform" name="custom_template[hover_transformation]" value="<?php
                           if (isset($template_design_settings['custom_template']['hover_transformation'])) {
                               echo esc_attr($template_design_settings['custom_template']['hover_transformation']);
                           }
                           ?>"/>
                    <div class="wpfm-tooltips-description">
                        <label class="wpfm-ts-description"><?php _e('Icon transform width on hover(Applicable on template 7 only)', 'wp-floating-menu'); ?></label>
                    </div>  
                </div>
            </div>
        </div> 
        <div class="wpfm-menu-field-wrapper" id="wpfm-general-layout">
            <h3 class="wpfm-menu-field-header"><?php _e('Menu Icon Settings', 'wp-floating-menu'); ?></h3>
            <div class="wpfm-menu-display-setting" id="wpfm-nav12347-icon-color-wrap">         
                <label><?php _e('Icon Background Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-icon-bg-color" name="custom_template[wpfm_icon_bg_color]" class="wpfm-colorpicker-trigger"/>
                </div>
            </div>                    
            <div class="wpfm-menu-display-setting" id="wpfm-nav-icon-size-wrap">        
                <label><?php _e('Icon Size', 'wp-floating-menu'); ?></label>
                <div id="wpfm-font-color" class="wpfm-menu-inner-field">
                    <input type="number" id="wpfm-icon-size" name="custom_template[icon_size]" />
                </div>
            </div>                    
            <div class="wpfm-menu-display-setting" id="wpfm-nav-icon-margin-wrap">
                <label><?php _e('Menu Icon Margin', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field" id="wpfm-icon-margin">
                    <input type="number" id="wpfm-icon-margin" name="custom_template[icon_margin]"/>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-nav-hover-active-icon-color-wrap">         
                <label><?php _e('Icon Hover/Active Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-icon-ah-color" name="custom_template[wpfm_icon_acthov_color]" class="wpfm-colorpicker-trigger"/>
                </div>
            </div>
        </div>
        <div class="wpfm-menu-field-wrapper" id="wpfm-menu-title-text">
            <h3 class="wpfm-menu-field-header"><?php _e('Menu Title Settings', 'wp-floating-menu'); ?></h3>
            <div class="wpfm-menu-display-setting" id="wpfm-menu-color-wrap">
                <label><?php _e('Font Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-title-font-color" name="custom_template[icon_title_font_color]"/>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-font-wrap">
                <label><?php _e('Typography', 'wp-floating-menu'); ?></label>
                <div id="wpfm-typography" class="wpfm-menu-inner-field">
                    <select name="custom_template[icon_title_text_font]" id="wpfm-menu-title-font" class="wpfmfont wpfm-menu-title-font">
                        <option value="default">Default</option>
<?php foreach ($wpfm_menu_fonts as $wpfm_menu_font) { ?>
                        <option value="<?php echo esc_attr($wpfm_menu_font); ?>"><?php echo esc_attr($wpfm_menu_font); ?></option>
<?php } ?>
                    </select>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-text-transform">
                <label><?php _e('Text Transform Style', 'wp-floating-menu'); ?></label>
                <div id="wpfm-title-text-transforms" class="wpfm-menu-inner-field">
                    <select name="custom_template[title_text_transform]" id="wpfm-title-text-transform" class="wpfm-font">
                        <option value="uppercase"><?php _e('Uppercase', 'wp-floating-menu'); ?></option>
                        <option value="capitalize"><?php _e('capitalize', 'wp-floating-menu'); ?></option>
                        <option value="lowercase"><?php _e('lowercase', 'wp-floating-menu'); ?></option>
                        <option value="none"><?php _e('None', 'wp-floating-menu'); ?></option>
                    </select>
                </div>
            </div>                      
            <div class="wpfm-font-demo-wrap">
                <div class="title-font-style" style="display:none;">
                </div>
                <span id="wpfm-font-family"><span id="wpfm-font-family"><?php _e('The Quick Brown Fox Jumps Over The Lazy Dog. 1234567890', 'wp-floating-menu'); ?></span></span>
            </div>               
            <div class="wpfm-menu-display-setting" id="wpfm-font-size">
                <label><?php _e('Font Size', 'wp-floating-menu'); ?></label>
                <span class="wpfm-font-size-wrap">
                    <select class="wpfm-select-font" name="custom_template[wpfm_icon_title_font_size]" id="wpfm-title-font-size">
                        <?php
                        $sizes = array('8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '26', '28', '36', '48', '72');
                        foreach ($sizes as $size) {
                            ?>
                        <option value="<?php echo esc_attr($size); ?>"><?php echo esc_attr($size); ?></option>
<?php } ?>
                    </select>
                </span>
            </div>                                
        </div><!-- .wpfm-menu-title-text -->
        <div class="wpfm-menu-field-wrapper" id="wpfm-menu-title-text">
            <h3 class="wpfm-menu-field-header"><?php _e('Tooltip Text Settings', 'wp-floating-menu'); ?></h3>
            <div class="wpfm-menu-display-setting" id="wpfm-menu-color-wrap">
                <label><?php _e('Font Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-tooltip-font-color" name="custom_template[icon_tooltip_font_color]"/>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-menu-color-wrap">
                <label><?php _e('Background Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-tooltip-bg-color" name="custom_template[icon_tooltip_bg_color]" class="wpfm-colorpicker-trigger"/>
                </div>
            </div>                
            <div class="wpfm-menu-display-setting" id="wpfm-tooltip-font-wrap">
                <label><?php _e('Typography', 'wp-floating-menu'); ?></label>
                <div id="wpfm-tooltip-typography" class="wpfm-menu-inner-field">
                    <select name="custom_template[icon_tooltip_text_font]" id="wpfm-menu-tooltip-font" class="wpfmfont">
                        <option value="default">Default</option>
<?php foreach ($wpfm_menu_fonts as $wpfm_menu_font) { ?>
                        <option value="<?php echo esc_attr($wpfm_menu_font); ?>"><?php echo esc_attr($wpfm_menu_font); ?></option>
<?php } ?>
                    </select>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-tt-text-transform">
                <label><?php _e('Text Transform Style', 'wp-floating-menu'); ?></label>
                <div id="wpfm-tt-title-text-transforms" class="wpfm-menu-inner-field">
                    <select name="custom_template[tt_title_text_transform]" id="wpfm-tt-title-text-transform" class="wpfm-font">
                        <option value="uppercase"><?php _e('Uppercase', 'wp-floating-menu'); ?></option>
                        <option value="capitalize"><?php _e('Capitalize', 'wp-floating-menu'); ?></option>
                        <option value="lowercase"><?php _e('Lowercase', 'wp-floating-menu'); ?></option>
                        <option value="none"><?php _e('None', 'wp-floating-menu'); ?></option>
                    </select>
                </div>
            </div>      
            <div class="wpfm-font-demo-wrap" id="tt-demo-wrap-wpfm">
                <div class="tooltip-font-style" style="display:none;">
                </div>
                <span id="wpfm-tooltip-font-family"><span id="wpfm-font-family"><?php _e('The Quick Brown Fox Jumps Over The Lazy Dog. 1234567890', 'wp-floating-menu'); ?></span></span>
            </div>               
            <div class="wpfm-menu-display-setting" id="wpfm-font-size">
                <label><?php _e('Font Size', 'wp-floating-menu'); ?></label>
                <span class="wpfm-font-size-wrap">
                    <select class="wpfm-select-font" name="custom_template[wpfm_tooltip_font_size]" id="wpfm-tooltip-font-size">
                        <?php
                        $sizes = array('8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '26', '28', '36', '48', '72');
                        foreach ($sizes as $size) {
                            ?>
                        <option value="<?php echo esc_attr($size); ?>"><?php echo esc_attr($size); ?></option>
<?php } ?>
                    </select>
                </span>
            </div>                                
        </div><!-- #wpfm-nav-tooltip-plain-title-wrap -->
    </div><!-- .wpfm-setting-body -->
</div><!-- .wpfm-display-setting-wrapper -->