<?php defined('ABSPATH') or die("No script kiddies please!"); ?>

<div class="wpfm-menu-wrapper wpfm-<?php echo esc_attr($template); ?>" menu-id="<?php echo esc_attr($menu_data->id); ?>" data-pos-offset-var="<?php echo isset($wpfm_settings['menu_enable_offset_to_position']) && $wpfm_settings['menu_enable_offset_to_position'] == 1 ? 1 : 0; ?>" data-disable-double-touch = "<?php echo isset($wpfm_settings['menu_disable_double_touch']) && $wpfm_settings['menu_disable_double_touch'] == 1 ? 1 : 0; ?>">
    <nav id="wpfm-floating-menu-nav" class="wpfm-menu-nav wpfm wpfm-position-<?php echo esc_attr($menu_position); ?>">
        <ul class="wpfm-nav wpfm-nav-show-hide">
            <?php
            if (!empty($menu_structure_detail)) {
                foreach ($menu_structure_detail as $key => $val) {
                    if (!empty($val["wpfm_target_link_address"])) {
                        $wpfm_href_address = esc_attr($val["wpfm_target_link_address"]);
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
                        ?>" class="wpfm-menu-link" href="<?php echo esc_url($wpfm_href_address); ?>" <?php echo esc_attr($wpfm_link_target); ?> <?php
                           if (isset($wpfm_settings['menu_link_add_nofollow']) && $wpfm_settings['menu_link_add_nofollow'] == 1) {
                               echo 'rel="nofollow"';
                           }
                           ?>>

                            <?php if (isset($template) && ($template == 'template-7' || $template == 'template-11' || $template == 'template-12' || $template == 'template-13')) { ?><span class="wpfm-icon-menu-name-wrapper"><?php } ?>
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
                                <?php }
                                ?>            
                                <?php if (isset($template) && ($template == 'template-7' || $template == 'template-11' || $template == 'template-12' || $template == 'template-13')) { ?></span><?php } ?>          
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
                        <?php }
                        ?>                             
                    </li>
                <?php }
            }
            ?>  
        </ul>             
    </nav>
</div>