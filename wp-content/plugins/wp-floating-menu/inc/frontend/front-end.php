<?php
defined('ABSPATH') or die("No script kiddies please!");

global $post, $wpdb;
$wpfm_settings = get_option(WPFM_SETTINGS); /* Setting from option page */
$enable_menu = esc_attr($wpfm_settings['menu_enable_disable']); /* Enable Disable Menu in site */
$enable_menu_on_mobile = esc_attr($wpfm_settings['mobile_menu_enable_disable']); /* Enable Disable Menu for mobile version */
$default_menu = isset($wpfm_settings['menu_list_selected']) ? esc_attr($wpfm_settings['menu_list_selected']) : ''; /* Menu value from option table */
$show_pages_wise_menu = esc_attr($wpfm_settings['menu_show_hide_on']); /* Display hide menu on all page, home page */

if ($default_menu != "default") {
    $pagebar = "show-menu";
} else if ($enable_menu == 0 && $default_menu == "default") {
    $pagebar = "disable";
} else {
    $pagebar = "show-menu";
}
if ($enable_menu == 1) {
    if ($pagebar != 'disable') {
        if (!is_feed()) {
            if (isset($id)) {
                $get_data_from_table = $this->get_menu_data($id);
            } else {
                if ($show_pages_wise_menu == "all-pages") {
                    $disp_class_name = "show-menu";
                    $default_menu = $default_menu;
                    $get_data_from_table = $this->get_menu_data($default_menu);
                } else if ($show_pages_wise_menu == "home-page") {
                    if (is_front_page()) {
                        $default_menu = $default_menu;
                        $disp_class_name = "show-menu";
                        $get_data_from_table = $this->get_menu_data($default_menu);
                    } else {
                        $disp_class_name = "show-no-menu";
                        $get_data_from_table = array();
                    }
                } else {
                    $disp_class_name = "show-no-menu";
                    $get_data_from_table = array();
                    $page_bar_id = '';
                }
            }
            if (isset($enable_menu_on_mobile) && $enable_menu_on_mobile == 0) {
                ?>
                <style type="text/css">
                    @media screen and (max-width:480px){
                        .wpfm-menu-wrapper{display:none; }	
                    }
                </style>
            <?php } ?>
            <div class="wpfm-floating-wh-wrapper" <?php
            if (isset($disp_class_name) && $disp_class_name == 'show-no-menu') {
                echo 'style="display:none;"';
            }
            ?>>
                     <?php
                     if (isset($get_data_from_table[0]) && !empty($get_data_from_table[0])) {
                         $menu_data = $get_data_from_table[0];
                         $menu_structure_detail = unserialize($menu_data->menu_details);
                         $menu_design_detail = unserialize($menu_data->menu_display_setting_details);
                         $menu_position = esc_attr($menu_design_detail['menu_design']['menu_placement']);
                         if ($menu_design_detail['menu_design']['menu_template_style'] == 'custom-template') {
                             $template_id = isset($menu_design_detail['menu_design']['custom_template_type']) ? esc_attr($menu_design_detail['menu_design']['custom_template_type']) : 0;
                             if (!empty($template_id)) {
                                 $get_template_details = $this->get_template_data($template_id);
                             }
                             if (isset($get_template_details[0]) && !empty($get_template_details[0])) {
                                 $template_data = $get_template_details[0];
                                 $template_detail = unserialize($template_data->template_details);

                                 /** custom template design starts from here */
                                 if (isset($template_detail['custom_template']['menu_layout']) && ($template_detail['custom_template']['menu_layout'] == 'template-1') || $template_detail['custom_template']['menu_layout'] == 'template-2' || $template_detail['custom_template']['menu_layout'] == 'template-3' || $template_detail['custom_template']['menu_layout'] == 'template-4') {
                                     ?>
                                <div class="wpfm-menu-wrapper wpfm-<?php echo esc_attr($template_detail['custom_template']['menu_layout']); ?>" data-pos-offset-var="<?php echo isset($wpfm_settings['menu_enable_offset_to_position']) && $wpfm_settings['menu_enable_offset_to_position'] == 1 ? 1 : 0; ?>" data-disable-double-touch = "<?php echo isset($wpfm_settings['menu_disable_double_touch']) && $wpfm_settings['menu_disable_double_touch'] == 1 ? 1 : 0; ?>">
                                    <nav id="wpfm-floating-menu-nav" class="wpfm-menu-nav wpfm wpfm-position-<?php echo esc_attr($menu_position); ?>">
                                        <ul>
                                            <?php
                                            if (!empty($menu_structure_detail)) {
                                                foreach ($menu_structure_detail as $key => $val) {
                                                    if (!empty($val["wpfm_target_link_address"])) {
                                                        $wpfm_href_address = esc_url($val["wpfm_target_link_address"]);
                                                    }
                                                    if (isset($val["wpfm_field_link_target"]) && $val["wpfm_field_link_target"] != '') {
                                                        $wpfm_link_target = 'target="_blank"';
                                                    } else {
                                                        $wpfm_link_target = '';
                                                    }
                                                    if (isset($val["wpfm_tooltip_show"]) && $val["wpfm_tooltip_show"] == '1' && !empty($val["wpfm_menu_tooltip_title"])) {
                                                        $tooltip_title = esc_attr($val["wpfm_menu_tooltip_title"]);
                                                    }
                                                    ?>
                                                    <li class="<?php
                                                    if (!(isset($val["wpfm_title_show"])) || $val["wpfm_title_show"] == '') {
                                                        echo 'wpfm-title-hidden';
                                                    }
                                                    ?> <?php
                                                    if ((isset($val["wpfm_custom_class"])) && $val["wpfm_custom_class"] != '') {
                                                        echo esc_attr($val['wpfm_custom_class']);
                                                    }
                                                    ?>">
                                                        <a title="<?php
                                                        if (!empty($val['wpfm_menu_item_title_attribute'])) {
                                                            echo esc_attr($val['wpfm_menu_item_title_attribute']);
                                                        }
                                                        ?>" class="wpfm-menu-link" href="<?php echo esc_attr($wpfm_href_address); ?>" <?php echo esc_attr($wpfm_link_target); ?> <?php
                                                           if (isset($wpfm_settings['menu_link_add_nofollow']) && $wpfm_settings['menu_link_add_nofollow'] == 1) {
                                                               echo 'rel="nofollow"';
                                                           }
                                                           ?>>
                                                            <?php if (isset($template_detail['custom_template']['menu_layout']) && $template_detail['custom_template']['menu_layout'] == 'template-7') { ?><span class="wpfm-icon-menu-name-wrapper"><?php } ?>
                                                                <span class='wpfm-icon-block'>                
                                                                    <?php
                                                                    if (((isset($val['icon_icon_type']) && $val['icon_icon_type'] == 'default') && (isset($val['icon_picker_settings']) && $val['icon_picker_settings'] != '')) || !isset($val['icon_icon_type']) && (isset($val['icon_picker_settings']) && $val['icon_picker_settings'] != '')) {
                                                                        $v = explode('|', esc_attr($val['icon_picker_settings']));
                                                                        ?>
                                                                        <i <?php
                                                                        if (isset($icon_color)) {
                                                                            echo esc_attr($icon_color);
                                                                        }
                                                                        ?> class="<?php echo $v[0] . ' ' . $v[1]; ?>" aria-hidden="true"></i><?php } else if ((isset($val['icon_icon_type']) && $val['icon_icon_type'] == 'custom') && (isset($val['icon_picker_custom']) && $val['icon_picker_custom'] != '')) {
                                                                        ?> 
                                                                        <i <?php
                                                                        if (isset($icon_color)) {
                                                                            echo esc_attr($icon_color);
                                                                        }
                                                                        ?>class="<?php echo esc_attr($val['icon_picker_custom']); ?>" aria-hidden="true"></i>
                                                                        <?php } ?>      
                                                                </span>
                                                                <?php if (isset($val['wpfm_menu_item_title']) && !empty($val['wpfm_menu_item_title']) && (isset($val["wpfm_title_show"]) && $val["wpfm_title_show"] == '1')) {
                                                                    ?>
                                                                    <span class='name wpfm-menu-name'>
                                                                        <?php echo esc_attr($val['wpfm_menu_item_title']); ?>
                                                                    </span>
                                                                <?php } ?>
                                                                <?php if (isset($template_detail['custom_template']['menu_layout']) && $template_detail['custom_template']['menu_layout'] == 'template-7') { ?></span><?php } ?>           
                                                        </a>
                                                        <?php if (isset($val["wpfm_tooltip_show"]) && $val["wpfm_tooltip_show"] == '1' && !empty($val["wpfm_menu_tooltip_title"]) && $template_detail['custom_template']['menu_layout'] != 'template-7') {
                                                            ?>
                                                            <span class='tooltip wpfm-tootltip-title'>
                                                                <?php
                                                                if (isset($val['wpfm_menu_tooltip_title']) && !empty($val['wpfm_menu_tooltip_title'])) {
                                                                    echo esc_attr($val['wpfm_menu_tooltip_title']);
                                                                }
                                                                ?>
                                                            </span>
                                                        <?php } ?>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>  
                                        </ul>             
                                    </nav>
                                </div>
                            <?php } else if (isset($template_detail['custom_template']['menu_layout']) && $template_detail['custom_template']['menu_layout'] == 'template-5') {
                                ?>
                                <div class="wpfm-menu-wrapper wpfm-<?php echo esc_attr($template_detail['custom_template']['menu_layout']); ?>" data-pos-offset-var="<?php echo isset($wpfm_settings['menu_enable_offset_to_position']) && $wpfm_settings['menu_enable_offset_to_position'] == 1 ? 1 : 0; ?>" data-disable-double-touch = "<?php echo isset($wpfm_settings['menu_disable_double_touch']) && $wpfm_settings['menu_disable_double_touch'] == 1 ? 1 : 0; ?>">
                                    <nav id="wpfm-floating-menu-nav" class="wpfm-menu-nav wpfm wpfm-position-<?php echo esc_attr($menu_position); ?>">    
                                        <ul class="wpfm-nav wpfm-nav-show-hide" style="display:none;">
                                            <?php
                                            if (!empty($menu_structure_detail)) {
                                                foreach ($menu_structure_detail as $key => $val) {
                                                    if (!empty($val["wpfm_target_link_address"])) {
                                                        $wpfm_href_address = esc_url($val["wpfm_target_link_address"]);
                                                    }
                                                    if (isset($val["wpfm_field_link_target"]) && $val["wpfm_field_link_target"] != '') {
                                                        $wpfm_link_target = 'target="_blank"';
                                                    } else {
                                                        $wpfm_link_target = '';
                                                    }
                                                    if (isset($val["wpfm_tooltip_show"]) && $val["wpfm_tooltip_show"] == '1' && !empty($val["wpfm_menu_tooltip_title"])) {
                                                        $tooltip_title = esc_attr($val["wpfm_menu_tooltip_title"]);
                                                    }
                                                    ?>
                                                    <li class="<?php
                                                    if (!(isset($val["wpfm_title_show"])) || $val["wpfm_title_show"] == '') {
                                                        echo 'wpfm-title-hidden';
                                                    }
                                                    ?> <?php
                                                    if ((isset($val["wpfm_custom_class"])) && $val["wpfm_custom_class"] != '') {
                                                        echo esc_attr($val['wpfm_custom_class']);
                                                    }
                                                    ?>">
                                                        <a title="<?php
                                                        if (!empty($val['wpfm_menu_item_title_attribute'])) {
                                                            echo esc_attr($val['wpfm_menu_item_title_attribute']);
                                                        }
                                                        ?>" class="wpfm-menu-link" href="<?php echo esc_attr($wpfm_href_address); ?>" <?php echo esc_attr($wpfm_link_target); ?> <?php
                                                           if (isset($wpfm_settings['menu_link_add_nofollow']) && $wpfm_settings['menu_link_add_nofollow'] == 1) {
                                                               echo 'rel="nofollow"';
                                                           }
                                                           ?>>
                                                            <span class='wpfm-icon-block'>                
                                                                <?php
                                                                if (((isset($val['icon_icon_type']) && $val['icon_icon_type'] == 'default') && (isset($val['icon_picker_settings']) && $val['icon_picker_settings'] != '')) || !isset($val['icon_icon_type']) && (isset($val['icon_picker_settings']) && $val['icon_picker_settings'] != '')) {
                                                                    $v = explode('|', esc_attr($val['icon_picker_settings']));
                                                                    ?>
                                                                    <i <?php
                                                                    if (isset($icon_color)) {
                                                                        echo esc_attr($icon_color);
                                                                    }
                                                                    ?> class="<?php echo $v[0] . ' ' . $v[1]; ?>" aria-hidden="true"></i><?php } else if ((isset($val['icon_icon_type']) && $val['icon_icon_type'] == 'custom') && (isset($val['icon_picker_custom']) && $val['icon_picker_custom'] != '')) {
                                                                    ?> 
                                                                    <i <?php
                                                                    if (isset($icon_color)) {
                                                                        echo esc_attr($icon_color);
                                                                    }
                                                                    ?>class="<?php echo esc_attr($val['icon_picker_custom']); ?>" aria-hidden="true"></i>
                                                                    <?php } ?>      
                                                            </span>

                                                            <?php if (isset($val['wpfm_menu_item_title']) && !empty($val['wpfm_menu_item_title']) && (isset($val["wpfm_title_show"]) && $val["wpfm_title_show"] == '1')) {
                                                                ?>
                                                                <span class='name wpfm-menu-name'>
                                                                    <?php echo esc_attr($val['wpfm_menu_item_title']); ?>
                                                                </span>
                                                            <?php } ?>                          
                                                        </a>
                                                        <?php if (isset($val["wpfm_tooltip_show"]) && $val["wpfm_tooltip_show"] == '1' && !empty($val["wpfm_menu_tooltip_title"])) {
                                                            ?>
                                                            <span class='tooltip wpfm-tootltip-title'>
                                                                <?php
                                                                if (isset($val['wpfm_menu_tooltip_title']) && !empty($val['wpfm_menu_tooltip_title'])) {
                                                                    echo esc_attr($val['wpfm_menu_tooltip_title']);
                                                                }
                                                                ?>
                                                            </span>
                                                        <?php } ?>
                                                    </li>
                                                <?php } ?>
                                                <a class="wpfm-nav-close-trigger wpfm-menu-link" href="javascript:void(0)" id="<?php echo esc_attr($menu_position); ?>">
                                                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                                                </a>       
                                            </ul>
                                            <a class="wpfm-nav-strech-trigger" href="javascript:void(0)" id="<?php echo esc_attr($menu_position); ?>">
                                                <span aria-hidden="true"> <?php
                                                    if ($template_detail['custom_template']['menu_layout'] == 'template-5') {
                                                        if (isset($template_detail['custom_template']['icon_expand']) && !empty($template_detail['custom_template']['icon_expand'])) {
                                                            $v = explode('|', $template_detail['custom_template']['icon_expand']);
                                                            if ($v[1] == 'fa-blank' || $v[1] == 'dashicons-blank' || $v[1] == 'genericon-blank') {
                                                                echo '<span aria-hidden="true"><i class="fa fa-bars"></i></span>';
                                                            } else {
                                                                echo '<span aria-hidden="true"><i class="' . $expand_icon = $v[0] . ' ' . $v[1] . '"></i></span>';
                                                            }
                                                        } else {
                                                            echo '<span aria-hidden="true"><i class="fa fa-bars"></i></span>';
                                                        }
                                                    }
                                                    ?></span>
                                            </a>              
                                        </nav>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <style type="text/css">
                                /* Font Icon Background */
                                .wpfm-template-1 ul li a,.wpfm-template-1 ul li .wpfm-icon-block,.wpfm-template-1 .wpfm-position-right ul li a:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-top-right ul li a:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-bottom-right ul li a:hover .wpfm-icon-block,
                                .wpfm-template-1 .wpfm-position-top-left ul li.wpfm-     title-hidden a:hover .wpfm-icon-block , .wpfm-template-1 .wpfm-position-bottom-left ul li.wpfm-title-hidden a:hover .wpfm-icon-block,.wpfm-template-1 .wpfm-position-left ul li.wpfm-title-hidden a:hover .wpfm-icon-block,
                                .wpfm-template-1 .wpfm-position-top-right ul li.wpfm-title-hidden a:hover .wpfm-icon-block , .wpfm-template-1 .wpfm-position-bottom-right ul li.wpfm-title-hidden a:hover .wpfm-icon-block,.wpfm-template-1 .wpfm-position-right ul li.wpfm-title-hidden a:hover .wpfm-icon-block,
                                .wpfm-template-1 .wpfm-position-left ul li a:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-top-left ul li a:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-bottom-left ul li a:hover .wpfm-icon-block,
                                .wpfm-template-2 .wpfm-menu-nav.wpfm-position-left ul li a, .wpfm-template-2 .wpfm-menu-nav.wpfm-position-top-left ul li  a, .wpfm-template-2 .wpfm-menu-nav.wpfm-position-bottom-left ul li a,
                                .wpfm-template-2 .wpfm-menu-nav.wpfm-position-right ul li a, .wpfm-template-2 .wpfm-menu-nav.wpfm-position-top-right ul li a, .wpfm-template-2 .wpfm-menu-nav.wpfm-position-bottom-right ul li a,
                                .wpfm-template-3 .wpfm-menu-nav.wpfm-position-left ul > li > a span.wpfm-icon-block, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-top-left ul > li > a span.wpfm-icon-block, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-bottom-left ul > li > a span.wpfm-icon-block,
                                .wpfm-template-3 .wpfm-menu-nav.wpfm-position-left ul li a:hover, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-top-left ul li a:hover, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-bottom-left ul li a:hover,
                                .wpfm-template-3 .wpfm-menu-nav.wpfm-position-right ul > li > a span.wpfm-icon-block,.wpfm-template-3 .wpfm-menu-nav.wpfm-position-right ul li > a:hover,
                                .wpfm-template-4 ul li .wpfm-icon-block
                                { 
                                    background: <?php echo esc_attr($template_detail['custom_template']['wpfm_icon_bg_color']); ?>;                    
                                }

                                /* Menu Background for template 5 */
                                .wpfm-template-5 ul,.wpfm-template-5 .wpfm-nav-strech-trigger span
                                {
                                    background: <?php echo esc_attr($template_detail['custom_template']['menu_bg_color']); ?>;
                                }

                                /* For Title Color, Font Family */   
                                .wpfm-template-1 .wpfm-menu-nav.wpfm-position-right ul li > a:hover > span.wpfm-menu-name, .wpfm-template-1 .wpfm-menu-nav.wpfm-position-top-right ul li > a:hover > span.wpfm-menu-name,
                                .wpfm-template-1 .wpfm-menu-nav.wpfm-position-bottom-right ul li > a:hover > span.wpfm-menu-name,
                                .wpfm-template-1 ul li .wpfm-menu-name,
                                .wpfm-template-2 .wpfm-menu-nav ul li a span.wpfm-menu-name,
                                .wpfm-template-3 .wpfm-menu-nav.wpfm-position-left ul li a span.wpfm-menu-name,.wpfm-template-3 .wpfm-menu-nav.wpfm-position-right ul li a span.wpfm-menu-name,
                                .wpfm-template-4 ul li .wpfm-menu-name,.wpfm-template-3 .wpfm-menu-nav ul li a span.wpfm-menu-name, .wpfm-template-3 .wpfm-menu-nav ul li a span.wpfm-menu-name,
                                .wpfm-template-5 .wpfm-menu-nav ul li a span.wpfm-menu-name
                                { 
                                    color:<?php echo esc_attr($template_detail['custom_template']['icon_title_font_color']); ?>;
                                    font-family:<?php echo esc_attr($template_detail['custom_template']['icon_title_text_font']); ?>; 
                                }
                                /* For Title Font Size */  
                                .wpfm-template-1 a span.wpfm-menu-name,
                                .wpfm-template-2 .wpfm-menu-nav ul li a span.wpfm-menu-name,
                                .wpfm-template-3 .wpfm-menu-nav ul li a span.wpfm-menu-name,.wpfm-template-3 .wpfm-menu-nav ul li a span.wpfm-menu-name,
                                .wpfm-template-4 ul li .wpfm-menu-name,
                                .wpfm-template-5 .wpfm-menu-nav ul li a span.wpfm-menu-name
                                { 
                                    font-size:<?php echo esc_attr($template_detail['custom_template']['wpfm_icon_title_font_size']); ?>px;
                                }
                                /* For Icon Size */   
                                .wpfm-template-1 .wpfm-menu-nav.wpfm-position-right ul li > a .wpfm-icon-block i,
                                .wpfm-template-2 .wpfm-menu-nav ul li a span i,
                                .wpfm-template-3 .wpfm-menu-nav ul li a span i,
                                .wpfm-template-4 .wpfm-menu-nav ul li a span i,
                                .wpfm-template-5 .wpfm-menu-nav ul li a span.wpfm-icon-block i
                                {
                                    font-size:<?php echo esc_attr($template_detail['custom_template']['icon_size']); ?>px;
                                }

                                /* Title Background Color For Template 4 */
                                .wpfm-template-4 .wpfm-menu-nav ul li >a:hover >span.wpfm-menu-name
                                { 
                                    background:<?php echo isset($template_detail['custom_template']['icon_title_bg_color']) && !empty($template_detail['custom_template']['icon_title_bg_color']) ? $template_detail['custom_template']['icon_title_bg_color']:''; ?>;  
                                }
                                /* For Icon margin */  
                                .wpfm-template-1 ul li, .wpfm-template-2 ul li, .wpfm-template-3 ul li, .wpfm-template-4 ul li,
                                .wpfm-template-4 ul li a,
                                .wpfm-template-5 .wpfm-menu-nav ul li a
                                {
                                    margin-bottom:<?php echo isset($template_detail['custom_template']['icon_margin']) && !empty($template_detail['custom_template']['icon_margin']) ? $template_detail['custom_template']['icon_margin'] : ''; ?>px;
                                }
                                /** Tooltip Title */
                                .wpfm-template-1 ul li > .wpfm-tootltip-title, 
                                .wpfm-template-2 ul li > .wpfm-tootltip-title, 
                                .wpfm-template-3 ul li > .wpfm-tootltip-title, 
                                .wpfm-template-4 ul li > .wpfm-tootltip-title,
                                .wpfm-template-8.wpfm-position-right .wpfm-tootltip-title, .wpfm-template-8.wpfm-position-top-right .wpfm-tootltip-title,.wpfm-template-8.wpfm-position-bottom-right .wpfm-tootltip-title,
                                .wpfm-template-5 .wpfm-menu-nav ul li > span.wpfm-tootltip-title
                                {
                                    color: <?php echo esc_attr($template_detail['custom_template']['icon_tooltip_font_color']); ?>;
                                    background: <?php echo esc_attr($template_detail['custom_template']['icon_tooltip_bg_color']); ?>;
                                    font-size: <?php echo esc_attr($template_detail['custom_template']['wpfm_tooltip_font_size']); ?>px;
                                    font-family: <?php echo esc_attr($template_detail['custom_template']['icon_tooltip_text_font']); ?>;
                                    text-transform: <?php echo esc_attr($template_detail['custom_template']['tt_title_text_transform']); ?>;
                                }

                                /* Border color for tooltip text arrow */
                                .wpfm-template-1 .wpfm-position-right ul li > .wpfm-tootltip-title:after, .wpfm-template-1 .wpfm-position-top-right ul li > .wpfm-tootltip-title:after, .wpfm-template-1 .wpfm-position-bottom-right ul li > .wpfm-tootltip-title:after,
                                .wpfm-template-2 .wpfm-position-right ul li > .wpfm-tootltip-title:after, .wpfm-template-2 .wpfm-position-top-right ul li > .wpfm-tootltip-title:after, .wpfm-template-2 .wpfm-position-bottom-right ul li > .wpfm-tootltip-title:after, 
                                .wpfm-template-3 .wpfm-position-right ul li > .wpfm-tootltip-title:after, .wpfm-template-3 .wpfm-position-top-right ul li > .wpfm-tootltip-title:after, .wpfm-template-3 .wpfm-position-bottom-right ul li > .wpfm-tootltip-title:after,                
                                .wpfm-template-4 .wpfm-position-right ul li > .wpfm-tootltip-title:after, .wpfm-template-4 .wpfm-position-top-right ul li > .wpfm-tootltip-title:after,                 
                                .wpfm-template-4 .wpfm-position-bottom-right ul li > .wpfm-tootltip-title:after,
                                .wpfm-template-5 .wpfm-position-right ul li  span.wpfm-tootltip-title:before, .wpfm-template-5 .wpfm-position-top-right ul li  span.wpfm-tootltip-title:before, .wpfm-template-5 .wpfm-menu-nav.wpfm-position-bottom-right ul li  span.wpfm-tootltip-title:before
                                {
                                    border-color:transparent transparent transparent <?php
                                    if (!empty($template_detail['custom_template']['icon_tooltip_bg_color'])) {
                                        echo ($template_detail['custom_template']['icon_tooltip_bg_color']);
                                    } else {
                                        echo '#222';
                                    }
                                    ?>;
                                }
                                .wpfm-template-1 .wpfm-position-left ul li > .wpfm-tootltip-title:after, .wpfm-template-1 .wpfm-position-top-left ul li > .wpfm-tootltip-title:after, .wpfm-template-1 .wpfm-position-bottom-left ul li > .wpfm-tootltip-title:after, .wpfm-template-2 .wpfm-position-left ul li > .wpfm-tootltip-title:after,
                                .wpfm-template-2 .wpfm-position-top-left ul li > .wpfm-tootltip-title:after, .wpfm-template-2 .wpfm-position-bottom-left ul li > .wpfm-tootltip-title:after, .wpfm-template-3 .wpfm-position-left ul li > .wpfm-tootltip-title:after, .wpfm-template-3 .wpfm-position-top-left ul li > .wpfm-tootltip-title:after, 
                                .wpfm-template-3 .wpfm-position-bottom-left ul li > .wpfm-tootltip-title:after,
                                .wpfm-template-3 .wpfm-position-left ul li > .wpfm-tootltip-title:after, .wpfm-template-3 .wpfm-position-top-left ul li > .wpfm-tootltip-title:after, .wpfm-template-3 .wpfm-position-bottom-left ul li > .wpfm-tootltip-title:after,
                                .wpfm-template-4 .wpfm-position-left ul li > .wpfm-tootltip-title:after, .wpfm-template-4 .wpfm-position-top-left ul li > .wpfm-tootltip-title:after, .wpfm-template-4 .wpfm-position-bottom-left ul li > .wpfm-tootltip-title:after,
                                .wpfm-template-5 .wpfm-position-left ul li  span.wpfm-tootltip-title:before, .wpfm-template-5 .wpfm-position-top-left ul li  span.wpfm-tootltip-title:before, .wpfm-template-5 .wpfm-menu-nav.wpfm-position-bottom-left ul li  span.wpfm-tootltip-title:before
                                {
                                    border-color:transparent <?php
                                    if (!empty($template_detail['custom_template']['icon_tooltip_bg_color'])) {
                                        echo ($template_detail['custom_template']['icon_tooltip_bg_color']);
                                    } else {
                                        echo '#222';
                                    }
                                    ?> transparent transparent;  
                                }

                                /* active hover color for inline navigation and sticky menu */
                                .wpfm-template-1 ul li a.wpfm-active-nav, .wpfm-template-1 ul li a.wpfm-active-nav .wpfm-icon-block, .wpfm-template-1 .wpfm-position-left ul li a.wpfm-active-nav:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-top-left ul li a.wpfm-active-nav:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-bottom-left ul li a.wpfm-active-nav:hover .wpfm-icon-block,
                                .wpfm-template-1 .wpfm-position-right ul li a.wpfm-active-nav:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-top-right ul li a.wpfm-active-nav:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-bottom-right ul li a.wpfm-active-nav:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-left ul li.wpfm-title-hidden a.wpfm-active-nav:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-top-left ul li.wpfm-title-hidden a.wpfm-active-nav:hover .wpfm-icon-block, .wpfm-template-1 .wpfm-position-bottom-left ul li.wpfm-title-hidden a.wpfm-active-nav:hover .wpfm-icon-block,
                                .wpfm-template-2 .wpfm-menu-nav ul li a.wpfm-active-nav,
                                .wpfm-template-3 .wpfm-menu-nav.wpfm-position-left ul > li > a.wpfm-active-nav span.wpfm-icon-block, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-top-left ul > li > a.wpfm-active-nav span.wpfm-icon-block, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-bottom-left ul > li > a.wpfm-active-nav span.wpfm-icon-block,
                                .wpfm-template-3 .wpfm-menu-nav.wpfm-position-right ul > li > a.wpfm-active-nav span.wpfm-icon-block, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-top-right ul > li > a.wpfm-active-nav span.wpfm-icon-block, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-bottom-right ul > li > a.wpfm-active-nav span.wpfm-icon-block, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-left ul li a.wpfm-active-nav, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-top-left ul li a.wpfm-active-nav, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-bottom-left ul li a.wpfm-active-nav, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-right ul li a.wpfm-active-nav,
                                .wpfm-template-3 .wpfm-menu-nav.wpfm-position-top-right ul li a.wpfm-active-nav, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-bottom-right ul li a.wpfm-active-nav, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-left ul li a.wpfm-active-nav:hover, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-top-left ul li a.wpfm-active-nav:hover, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-bottom-left ul li a.wpfm-active-nav:hover, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-right ul li a.wpfm-active-nav:hover, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-top-right ul li a.wpfm-active-nav:hover, .wpfm-template-3 .wpfm-menu-nav.wpfm-position-bottom-right ul li a.wpfm-active-nav:hover,
                                .wpfm-template-4 .wpfm-position-left ul li a:hover .wpfm-icon-block, .wpfm-template-4 .wpfm-position-top-left ul li a:hover .wpfm-icon-block, .wpfm-template-4 .wpfm-position-bottom-left ul li a:hover .wpfm-icon-block, .wpfm-template-4 .wpfm-position-right ul li a:hover .wpfm-icon-block, .wpfm-template-4 .wpfm-position-top-right ul li a:hover .wpfm-icon-block, .wpfm-template-4 .wpfm-position-bottom-right ul li a:hover .wpfm-icon-block, .wpfm-template-4 .wpfm-position-left ul li a.wpfm-active-nav  .wpfm-icon-block, .wpfm-template-4 .wpfm-position-top-left ul li .wpfm-active-nav .wpfm-icon-block, .wpfm-template-4 .wpfm-position-bottom-left ul li a.wpfm-active-nav  .wpfm-icon-block,
                                .wpfm-template-4 .wpfm-position-right ul li a.wpfm-active-nav  .wpfm-icon-block, .wpfm-template-4 .wpfm-position-top-right ul li a.wpfm-active-nav  .wpfm-icon-block, .wpfm-template-4 .wpfm-position-bottom-right ul li a.wpfm-active-nav  .wpfm-icon-block
                                {
                                    background:<?php echo esc_attr($template_detail['custom_template']['wpfm_icon_acthov_color']); ?>;
                                }

                                /* Active class implementation in template 5 */
                                .wpfm-template-5 .wpfm-menu-nav ul li a:hover span.wpfm-icon-block i, .wpfm-template-5 .wpfm-menu-nav ul li a:hover span.wpfm-menu-name, .wpfm-template-5 .wpfm-menu-nav ul li a.wpfm-active-nav span.wpfm-icon-block i, .wpfm-template-5 .wpfm-menu-nav ul li a.wpfm-active-nav span.wpfm-menu-name
                                {
                                    color:<?php echo esc_attr($template_detail['custom_template']['wpfm_icon_acthov_color']); ?> !important;
                                }
                                .wpfm-template-5 .wpfm-nav-strech-trigger span i{
                                    color:<?php echo esc_attr($template_detail['custom_template']['wpfm_stretch_icon_color']); ?>
                                }

                                .wpfm-template-5 .wpfm-nav-close-trigger span i{
                                    color:<?php echo esc_attr($template_detail['custom_template']['wpfm_close_icon_color']); ?>
                                }
                            </style>
                           
                            <?php
                        }
                    } else {
                        $template = esc_attr($menu_design_detail['menu_design']['template_number']);
                        $menu_position = esc_attr($menu_design_detail['menu_design']['menu_placement']);
                        if (!empty($template) && ($template == 'template-1' || $template == 'template-2' || $template == 'template-3' || $template == 'template-4')) {
                            include('templates/template-1.php');
                        } else if (!empty($template) && $template == 'template-5') {
                            include('templates/template-2.php');
                        } else {
                            echo __('No Such Template. Please Select Valid Template and Try Again.', 'wp-floating-menu');
                        } /* Template if end */
                    } /* Not custom template then implement default template end else */
                } /* Get data from table end if */
                ?>
            </div>
            <?php
        } /* Is feed check end */
    } /* Page not disavled if end */
} /* Enable Menu check end if */
?>