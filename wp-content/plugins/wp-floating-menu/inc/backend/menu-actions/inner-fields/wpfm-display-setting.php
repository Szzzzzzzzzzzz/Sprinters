<?php defined('ABSPATH') or die("No script kiddies please!"); 
$menu_detail = $this->get_menu_data(esc_attr($_GET['id']));
if (!empty($menu_detail)) {
    foreach ($menu_detail as $row) {
        $id = $_GET['id'];
        $menu_design_settings = unserialize($row->menu_display_setting_details);
    }
}
?>
<div class="wpfm-display-setting-wrapper">
    <div class="wpfm-setting-body">
        <div class="wpfm-menu-field-wrapper" id="wpfm-template-layout">            
            <h4 class="wpfm-menu-field-header-text"><?php _e('Configure how you want to display menu.', 'wp-floating-menu'); ?></h4>
            <div class="wpfm-menu-display-setting" id="wpfm-menu-type-wrap">
                <label class="display-label-controller"><?php _e('Template style', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <select name="menu_design[menu_template_style]" id="wpfm-menu-type">
                        <option value="buildin" <?php if (isset($menu_design_settings['menu_design']['menu_template_style']) && $menu_design_settings['menu_design']['menu_template_style'] == 'buildin') { ?>selected="selected"<?php } ?>><?php _e('Builtin Template', 'wp-floating-menu'); ?></option>
                        <option value="custom-template" <?php if (isset($menu_design_settings['menu_design']['menu_template_style']) && $menu_design_settings['menu_design']['menu_template_style'] == 'custom-template') { ?>selected="selected"<?php } ?>><?php _e('Custom Template', 'wp-floating-menu'); ?></option>
                    </select>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="buildin-temple-listing" <?php if ((isset($menu_design_settings['menu_design']['menu_template_style']) && $menu_design_settings['menu_design']['menu_template_style'] == 'buildin') || $menu_design_settings['menu_design']['menu_template_style'] == '') { ?>style="display: block;" <?php } else { ?>style="display: none;"<?php } ?>>
                <label for="menu_design[template_number]" class="display-label-controller"><?php _e('Inbuilt Template', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <select name="menu_design[template_number]" id="menu-template" class="menu-design template-dropdown" size="1" >
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <option class="wpfm-temp" value="template-<?php echo $i; ?>" <?php if (isset($menu_design_settings['menu_design']['template_number']) && $menu_design_settings['menu_design']['template_number'] == 'template-' . $i) { ?>selected="selected"<?php } ?>><?php
                                _e('Template', 'wp-floating-menu');
                                echo " " . $i;
                                ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="wpfm-template-demo" >
                    <?php for ($cnt = 1; $cnt <= 5; $cnt++) { ?>
                        <div class="wpfm-common" id="wpfm-temp-demo-<?php echo $cnt; ?>" <?php if ($cnt != 1) { ?>style="display:none;"<?php } ?>>
                            <p>Template <?php echo $cnt; ?> Preview</p>
                            <img src="<?php echo esc_attr(WPFM_IMAGE_DIR) . '/template-' . $cnt . '_1.png' ?>"/>
                            <img src="<?php echo esc_attr(WPFM_IMAGE_DIR) . '/template-' . $cnt . '_2.png' ?>"/>
                        </div>
                    <?php } ?> 
                </div>
            </div>

            <div class="wpfm-menu-display-setting" id="wpfm-custom-template-type" <?php if (isset($menu_design_settings['menu_design']['menu_template_style']) && $menu_design_settings['menu_design']['menu_template_style'] == 'custom-template') { ?>style="display: block;" <?php } else { ?>style="display: none;"<?php } ?>>
                <label class="display-label-controller"><?php _e('Custom Templates', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <select name="menu_design[custom_template_type]" id="wpfm-custom-template">
                        <?php
                        $table_name = $wpdb->prefix . "wp_floating_menu_custom_templates";
                        $template_detail = $wpdb->get_results("SELECT * FROM $table_name ");
                        if (count($template_detail) > 0) {
                            foreach ($template_detail as $row) {
                                ?>
                        <option value="<?php echo esc_attr($row->id); ?>" <?php if (isset($menu_design_settings['menu_design']['custom_template_type']) && $menu_design_settings['menu_design']['custom_template_type'] == $row->id) { ?>selected="selected"<?php } ?>><?php echo esc_attr($row->template_name); ?></option>
                                <?php
                            }
                        } else {
                            ?>
                            <option value="null" disabled="disabled"><?php echo __('No Template Added Yet', 'wp-floating-menu'); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="wpfm-menu-display-setting" id="wpfm-menu-placement-wrap">
                <label><?php _e('Menu Bar Position', 'wp-floating-menu'); ?></label>
                <div class="wpfm-menu-inner-field">
                    <select name="menu_design[menu_placement]" id="wpfm-menu-placement">
                        <option value="left" <?php if (isset($menu_design_settings['menu_design']['menu_placement']) && $menu_design_settings['menu_design']['menu_placement'] == 'left') { ?>selected="selected"<?php } ?>><?php _e('Left', 'wp-floating-menu'); ?></option>
                        <option value="right" <?php if (isset($menu_design_settings['menu_design']['menu_placement']) && $menu_design_settings['menu_design']['menu_placement'] == 'right') { ?>selected="selected"<?php } ?>><?php _e('Right', 'wp-floating-menu'); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div><!-- .wpfm-setting-body -->
</div><!-- .wpfm-display-setting-wrapper -->