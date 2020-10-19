<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<?php
global $wpdb;
$wpfm_menu_fonts = get_option('wpfm_fmenu_fonts');
$table_name = $wpdb->prefix . "wp_floating_menu_custom_templates";
$fid= intval($_GET['id']);
$template_detail = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",array($fid)));
foreach ($template_detail as $row) {
    $template_design_settings = unserialize($row->template_details);
}
$title_fonts = $template_design_settings['custom_template']['icon_title_text_font'];
$tooltip_font = $template_design_settings['custom_template']['icon_tooltip_text_font'];
?>    
<div class="wpfm-display-setting-wrapper">
    <div class="wpfm-header-title"><?php _e('Edit Current Template', 'wp-floating-menu'); ?></div>
    <?php if (isset($_GET['message']) && $_GET['message'] == 1) { ?>
        <div class="wpfm-message notice notice-success is-dismissible">
            <p><?php
                echo __('Template Updated.', 'wp-floating-menu');
                ?>
            </p>
        </div>
    <?php } else if (isset($_GET['message']) && $_GET['message'] == 2) { ?>
        <div class="wpfm-message notice notice-success is-dismissible">
            <p><?php
                echo __('Template wasnot Updated', 'wp-floating-menu');
                ?>
            </p>
        </div>
    <?php } ?>
    <div class="wpfm-setting-body">
        <div class="wpfm-menu-field-wrapper" id="wpfm-template-layout">            
            <div class="wpfm-menu-display-setting" id="wpfm-menu-template-name">
                <label><?php _e('Template Name', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" class="wpfm-custom-template-name" name="wpfm-template-title-name" placeholder="<?php _e('E.g. Custom Template', 'wp-floating-menu'); ?>" value="<?php echo esc_attr($row->template_name); ?>"/>
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
                            <option class="custom-temp-layout" value="template-<?php echo $i; ?>"<?php if (isset($template_design_settings['custom_template']['menu_layout']) && $template_design_settings['custom_template']['menu_layout'] == 'template-' . $i) { ?>selected="selected"<?php } ?>><?php
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
        <div class="layout-5689-hidden-field" id="wpfm-general-layout" 
        <?php
        if (isset($template_design_settings['custom_template']['menu_layout']) && ($template_design_settings['custom_template']['menu_layout'] == 'template-5' || $template_design_settings['custom_template']['menu_layout'] == 'template-6' || $template_design_settings['custom_template']['menu_layout'] == 'template-8' || $template_design_settings['custom_template']['menu_layout'] == 'template-9')) {
            ?>style="display:block;"<?php } else { ?>style="display:none;"<?php } ?>>                
            <div class="wpfm-menu-display-setting" id="wpfm-bg-color-wrap">
                <label><?php _e('Menu Background Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" name="custom_template[menu_bg_color]" id="menu-background-color" class="wpfm-colorpicker-trigger" value="<?php
                    if (isset($template_design_settings['custom_template']['menu_bg_color'])) {
                        echo esc_attr($template_design_settings['custom_template']['menu_bg_color']);
                    }
                    ?>"/>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-stretchable-menu-direction" >
                <label><?php _e('Icon For Expand Trigger', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input class="wpfm-icon-picker" type="hidden" id="wpfm-icon-picker-icon" name="custom_template[icon_expand]" value="<?php echo esc_attr($template_design_settings['custom_template']['icon_expand']); ?>"/>
                    <div id="wpfm-ctemp-icon-div" data-target="#wpfm-icon-picker-icon" class="button icon-picker <?php
                    if (isset($template_design_settings['custom_template']['icon_expand']) && !empty($template_design_settings['custom_template']['icon_expand'])) {
                        $v = explode('|', $template_design_settings['custom_template']['icon_expand']);
                        echo $v[0] . ' ' . $v[1];
                    }
                    ?>"><?php _e('Select Icon', 'wp-floating-menu'); ?></div>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-stretch-menu-icon-color">         
                <label><?php _e('Expand Trigger Icon Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-icon-str-tr-icon-color" name="custom_template[wpfm_stretch_icon_color]" class="wpfm-colorpicker-trigger" value="<?php
                    if (isset($template_design_settings['custom_template']['wpfm_stretch_icon_color'])) {
                        echo esc_attr($template_design_settings['custom_template']['wpfm_stretch_icon_color']);
                    }
                    ?>"/>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-close-menu-icon-color">         
                <label><?php _e('Close Trigger Icon Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-icon-cls-tr-icon-color" name="custom_template[wpfm_close_icon_color]" class="wpfm-colorpicker-trigger" value="<?php
                    if (isset($template_design_settings['custom_template']['wpfm_close_icon_color'])) {
                        echo esc_attr($template_design_settings['custom_template']['wpfm_close_icon_color']);
                    }
                    ?>"/>
                </div>
            </div>
        </div>
        <div class="wpfm-menu-field-wrapper" id="wpfm-general-layout">
            <h3 class="wpfm-menu-field-header"><?php _e('Menu Icon Settings', 'wp-floating-menu'); ?></h3>
            <div class="wpfm-menu-display-setting" id="wpfm-nav12347-icon-color-wrap"<?php if (isset($template_design_settings['custom_template']['menu_layout']) && $template_design_settings['custom_template']['menu_layout'] != 'template-5') { ?>style="display:block;"<?php } else { ?>style="display:none;"<?php } ?>>         
                <label><?php _e('Icon Background Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-icon-bg-color" name="custom_template[wpfm_icon_bg_color]" class="wpfm-colorpicker-trigger" value="<?php
                    if (isset($template_design_settings['custom_template']['wpfm_icon_bg_color'])) {
                        echo esc_attr($template_design_settings['custom_template']['wpfm_icon_bg_color']);
                    }
                    ?>"/>
                </div>
            </div>                    
            <div class="wpfm-menu-display-setting" id="wpfm-nav-icon-size-wrap">        
                <label><?php _e('Icon Size', 'wp-floating-menu'); ?></label>
                <div id="wpfm-font-color" class="wpfm-menu-inner-field">
                    <input type="number" id="wpfm-icon-size" name="custom_template[icon_size]" value="<?php
                    if (isset($template_design_settings['custom_template']['icon_size'])) {
                        echo esc_attr($template_design_settings['custom_template']['icon_size']);
                    }
                    ?>"/>
                </div>
            </div>                    
            <div class="wpfm-menu-display-setting" id="wpfm-nav-icon-margin-wrap">
                <label><?php _e('Menu Icon Margin', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field" id="wpfm-icon-margin">
                    <input type="number" id="wpfm-icon-margin" name="custom_template[icon_margin]" value="<?php
                    if (isset($template_design_settings['custom_template']['icon_margin'])) {
                        echo esc_attr($template_design_settings['custom_template']['icon_margin']);
                    }
                    ?>"/>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-nav-hover-active-icon-color-wrap">         
                <label><?php _e('Icon Hover/Active Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-icon-ah-color" name="custom_template[wpfm_icon_acthov_color]" class="wpfm-colorpicker-trigger" value="<?php
                    if (isset($template_design_settings['custom_template']['wpfm_icon_acthov_color'])) {
                        echo esc_attr($template_design_settings['custom_template']['wpfm_icon_acthov_color']);
                    }
                    ?>"/>
                </div>
            </div>
        </div>
        <div class="wpfm-menu-field-wrapper" id="wpfm-menu-title-text">
            <h3 class="wpfm-menu-field-header"><?php _e('Menu Title Settings', 'wp-floating-menu'); ?></h3>
            <div class="wpfm-menu-display-setting" id="wpfm-menu-color-wrap">
                <label><?php _e('Font Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-title-font-color" name="custom_template[icon_title_font_color]" value="<?php
                    if (isset($template_design_settings['custom_template']['icon_title_font_color'])) {
                        echo esc_attr($template_design_settings['custom_template']['icon_title_font_color']);
                    }
                    ?>"/>
                </div>
            </div>
            <div class="layout-4610-hidden-field" id="wpfm-nav-icon-hover-transform-wrap"
            <?php if (isset($template_design_settings['custom_template']['menu_layout']) && ($template_design_settings['custom_template']['menu_layout'] == 'template-6' || $template_design_settings['custom_template']['menu_layout'] == 'template-10')) {
                ?>style="display:block;"<?php } else { ?>style="display:none;"<?php } ?>>
                <div class="wpfm-menu-display-setting" id="wpfm-menu-title-bg-color-wrap">
                    <label><?php _e('Title Background Color', 'wp-floating-menu'); ?></label>
                    <div class="wpfm-menu-inner-field">
                        <input type="text" id="wpfm-title-bg-color" name="custom_template[icon_title_bg_color]" class="wpfm-colorpicker-trigger" value="<?php
                        if (isset($template_design_settings['custom_template']['icon_title_bg_color'])) {
                            echo esc_attr($template_design_settings['custom_template']['icon_title_bg_color']);
                        }
                        ?>"/>
                    </div>
                </div>
            </div>                    
            <div class="wpfm-menu-display-setting" id="wpfm-font-wrap">
                <label><?php _e('Typography', 'wp-floating-menu'); ?></label>
                <div id="wpfm-typography" class="wpfm-menu-inner-field">
                    <select name="custom_template[icon_title_text_font]" id="wpfm-menu-title-font" class="wpfmfont wpfm-menu-title-font">
                        <option value="default">Default</option>
                        <?php foreach ($wpfm_menu_fonts as $wpfm_menu_font) { ?>
                            <option value="<?php echo esc_attr($wpfm_menu_font); ?>" <?php if (isset($template_design_settings['custom_template']['icon_title_text_font']) && $template_design_settings['custom_template']['icon_title_text_font'] == $wpfm_menu_font) { ?>selected="selected"<?php } ?>><?php echo esc_attr($wpfm_menu_font); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-text-transform">
                <label><?php _e('Text Transform Style', 'wp-floating-menu'); ?></label>
                <div id="wpfm-title-text-transforms" class="wpfm-menu-inner-field">
                    <select name="custom_template[title_text_transform]" id="wpfm-title-text-transform" class="wpfm-font">
                        <option value="uppercase" <?php if (isset($template_design_settings['custom_template']['title_text_transform']) && $template_design_settings['custom_template']['title_text_transform'] == 'uppercase') { ?>selected="selected"<?php } ?>><?php _e('Uppercase', 'wp-floating-menu'); ?></option>
                        <option value="capitalize" <?php if (isset($template_design_settings['custom_template']['title_text_transform']) && $template_design_settings['custom_template']['title_text_transform'] == 'capitalize') { ?>selected="selected"<?php } ?>><?php _e('Capitalize', 'wp-floating-menu'); ?></option>
                        <option value="lowercase" <?php if (isset($template_design_settings['custom_template']['title_text_transform']) && $template_design_settings['custom_template']['title_text_transform'] == 'lowercase') { ?>selected="selected"<?php } ?>><?php _e('Lowercase', 'wp-floating-menu'); ?></option>
                        <option value="none" <?php if (isset($template_design_settings['custom_template']['title_text_transform']) && $template_design_settings['custom_template']['title_text_transform'] == 'none') { ?>selected="selected"<?php } ?>><?php _e('None', 'wp-floating-menu'); ?></option>
                    </select>
                </div>
            </div>                       
            <div class="wpfm-font-demo-wrap">
                <span id="wpfm-font-family" class="wpfm-font-family">The Quick Brown Fox Jumps Over The Lazy Dog. 1234567890</span>
            </div>               
            <div class="wpfm-menu-display-setting" id="wpfm-font-size">
                <label><?php _e('Font Size', 'wp-floating-menu'); ?></label>
                <span class="wpfm-font-size-wrap">
                    <select class="wpfm-select-font" name="custom_template[wpfm_icon_title_font_size]" id="wpfm-title-font-size">
                        <?php
                        $sizes = array('8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '26', '28', '36', '48', '72');
                        foreach ($sizes as $size) {
                            ?>
                            <option value="<?php echo esc_attr($size); ?>" <?php if (isset($template_design_settings['custom_template']['wpfm_icon_title_font_size']) && $template_design_settings['custom_template']['wpfm_icon_title_font_size'] == $size) { ?>selected="selected"<?php } ?>><?php echo esc_attr($size); ?></option>
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
                    <input type="text" id="wpfm-tooltip-font-color" name="custom_template[icon_tooltip_font_color]" value="<?php
                    if (isset($template_design_settings['custom_template']['icon_tooltip_font_color']) && !empty($template_design_settings['custom_template']['icon_tooltip_font_color'])) {
                        echo esc_attr($template_design_settings['custom_template']['icon_tooltip_font_color']);
                    }
                    ?>"/>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-menu-color-wrap">
                <label><?php _e('Background Color', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <input type="text" id="wpfm-tooltip-bg-color" name="custom_template[icon_tooltip_bg_color]" class="wpfm-colorpicker-trigger" value="<?php
                    if (isset($template_design_settings['custom_template']['icon_tooltip_bg_color']) && !empty($template_design_settings['custom_template']['icon_tooltip_bg_color'])) {
                        echo esc_attr($template_design_settings['custom_template']['icon_tooltip_bg_color']);
                    }
                    ?>"/>
                </div>
            </div>                
            <div class="wpfm-menu-display-setting" id="wpfm-tooltip-font-wrap">
                <label><?php _e('Typography', 'wp-floating-menu'); ?></label>
                <div id="wpfm-tooltip-typography" class="wpfm-menu-inner-field">
                    <select name="custom_template[icon_tooltip_text_font]" id="wpfm-menu-tooltip-font" class="wpfmfont">
                        <option value="default">Default</option>
                        <?php foreach ($wpfm_menu_fonts as $wpfm_menu_font) { ?>
                            <option value="<?php echo esc_attr($wpfm_menu_font); ?>" <?php if (isset($template_design_settings['custom_template']['icon_tooltip_text_font']) && $template_design_settings['custom_template']['icon_tooltip_text_font'] == $wpfm_menu_font) { ?>selected="selected"<?php } ?>><?php echo esc_attr($wpfm_menu_font); ?></option>

                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-tt-text-transform">
                <label><?php _e('Text Transform Style', 'wp-floating-menu'); ?></label>
                <div id="wpfm-tt-title-text-transforms" class="wpfm-menu-inner-field">
                    <select name="custom_template[tt_title_text_transform]" id="wpfm-tt-title-text-transform" class="wpfm-font">
                        <option value="uppercase" <?php if (isset($template_design_settings['custom_template']['tt_title_text_transform']) && $template_design_settings['custom_template']['tt_title_text_transform'] == 'uppercase') { ?>selected="selected"<?php } ?>><?php _e('Uppercase', 'wp-floating-menu'); ?></option>
                        <option value="capitalize" <?php if (isset($template_design_settings['custom_template']['tt_title_text_transform']) && $template_design_settings['custom_template']['tt_title_text_transform'] == 'capitalize') { ?>selected="selected"<?php } ?>><?php _e('Capitalize', 'wp-floating-menu'); ?></option>
                        <option value="lowercase" <?php if (isset($template_design_settings['custom_template']['tt_title_text_transform']) && $template_design_settings['custom_template']['tt_title_text_transform'] == 'lowercase') { ?>selected="selected"<?php } ?>><?php _e('Lowercase', 'wp-floating-menu'); ?></option>
                        <option value="none" <?php if (isset($template_design_settings['custom_template']['tt_title_text_transform']) && $template_design_settings['custom_template']['tt_title_text_transform'] == 'none') { ?>selected="selected"<?php } ?>><?php _e('None', 'wp-floating-menu'); ?></option>
                    </select>
                </div>
            </div>
            <div class="wpfm-font-demo-wrap" id="tt-demo-wrap-wpfm">
                <span id="wpfm-tooltip-font-family">The Quick Brown Fox Jumps Over The Lazy Dog. 1234567890</span>
            </div>               
            <div class="wpfm-menu-display-setting" id="wpfm-font-size">
                <label><?php _e('Font Size', 'wp-floating-menu'); ?></label>
                <span class="wpfm-font-size-wrap">
                    <select class="wpfm-select-font" name="custom_template[wpfm_tooltip_font_size]" id="wpfm-tooltip-font-size">
                        <?php
                        $sizes = array('8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '26', '28', '36', '48', '72');
                        foreach ($sizes as $size) {
                            ?>
                            <option value="<?php echo esc_attr($size); ?>" <?php if (isset($template_design_settings['custom_template']['wpfm_tooltip_font_size']) && $template_design_settings['custom_template']['wpfm_tooltip_font_size'] == $size) { ?>selected="selected"<?php } ?>><?php echo esc_attr($size); ?></option>                               
                        <?php } ?>
                    </select>
                </span>
            </div>                                
        </div><!-- #wpfm-nav-tooltip-plain-title-wrap -->
        <input type="hidden" name="current_template_id" value="<?php echo intval($_GET['id']); ?>"/>           
    </div><!-- .wpfm-setting-body -->
</div><!-- .wpfm-display-setting-wrapper -->

<style>
    #wpfm-font-family{
        font-family:<?php
        if (isset($template_design_settings['custom_template']['icon_title_text_font'])) {
            echo esc_attr($template_design_settings['custom_template']['icon_title_text_font']);
        }
        ?>;
        font-size: <?php
        if (isset($template_design_settings['custom_template']['wpfm_icon_title_font_size'])) {
            echo esc_attr($template_design_settings['custom_template']['wpfm_icon_title_font_size']);
        }
        ?>px;
        text-transform: <?php
        if (isset($template_design_settings['custom_template']['title_text_transform'])) {
            echo esc_attr($template_design_settings['custom_template']['title_text_transform']);
        }
        ?>;
        color:<?php
        if (isset($template_design_settings['custom_template']['icon_title_font_color'])) {
            echo esc_attr($template_design_settings['custom_template']['icon_title_font_color']);
        }
        ?>;
    }
    #wpfm-tooltip-font-family{
        font-family:<?php
        if (isset($template_design_settings['custom_template']['icon_tooltip_text_font'])) {
            echo esc_attr($template_design_settings['custom_template']['icon_tooltip_text_font']);
        }
        ?>;
        font-size: <?php
        if (isset($template_design_settings['custom_template']['wpfm_tooltip_font_size'])) {
            echo esc_attr($template_design_settings['custom_template']['wpfm_tooltip_font_size']);
        }
        ?>px;
        text-transform: <?php
        if (isset($template_design_settings['custom_template']['tt_title_text_transform'])) {
            echo esc_attr($template_design_settings['custom_template']['tt_title_text_transform']);
        }
        ?>;
        color:<?php
        if (isset($template_design_settings['custom_template']['icon_tooltip_font_color'])) {
            echo esc_attr($template_design_settings['custom_template']['icon_tooltip_font_color']);
        }
        ?>
    }
    .wpfm-display-setting-wrapper  #tt-demo-wrap-wpfm{
        background:<?php
        if (isset($template_design_settings['custom_template']['icon_tooltip_bg_color'])) {
            echo esc_attr($template_design_settings['custom_template']['icon_tooltip_bg_color']);
        }
        ?>;
    }
</style>