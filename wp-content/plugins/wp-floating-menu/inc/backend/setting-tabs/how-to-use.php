<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<div class="wpfm-inner-wrapper" id="poststuff">
    <div class="wpfm-head">     
        <?php include(WPFM_FILE_ROOT_DIR . 'inc/backend/includes/wpfm-header.php'); ?> 
    </div> 
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <div class="postbox">
                <div class="about-wp-floating-menu-wrapper clearfix">
                    <div class="about-desc-wrap clearfix">
                        <div class="about-content">           
                            <div class="about-title-content">
                                <div class="wpfm-title"><span class="wpfm-tips-icon"><i class="fa fa-info-circle" aria-hidden="true"></i></span><span class="wpfm-how-section-title"><?php _e('Helpfull Tips', 'wp-floating-menu'); ?></span></div>
                                <?php _e("<p>To display the floating menu in your site, you have to enable menu first on <b>Menu Setting</b> page and select menu you created and select save.</p>", "wp-floating-menu"); ?>      
                                <?php _e("<p>There are 5 available themes to choose from. Or you can also use custom template design.</p>", "wp-floating-menu"); ?> 
                            </div>

                            <div class="about-title-content">
                                <div class="wpfm-title"><?php _e('Available Menu Structure Attributes', 'wp-floating-menu'); ?></div>
                                <p><?php _e('Menu structure attribute contains the fields that you can set while generating menu. Following attribute are available as listed.', 'wp-floating-menu'); ?></p>
                                <i class="fa fa-check"></i><strong><?php _e('Navigation Label', 'wp-floating-menu'); ?> :</strong><p><?php _e('This is the menu title that displays for the menu icon.', 'wp-floating-menu'); ?></p>
                                <i class="fa fa-check"></i><strong><?php _e('Title Attribute', 'wp-floating-menu'); ?> :</strong><p><?php _e(' This is the title attribute for the a tag for seo friendly nav menu.', 'wp-floating-menu'); ?></p>
                                <i class="fa fa-check"></i><strong><?php _e('Show Tooltip Title', 'wp-floating-menu'); ?> :</strong><p><?php _e('Check/Uncheck to show/hide tooltip text for the menu.', 'wp-floating-menu'); ?></p>
                                <i class="fa fa-check"></i><strong><?php _e('Tooltip Title Field', 'wp-floating-menu'); ?> :</strong><p><?php _e(' You can enter tooltip title to display in this field.', 'wp-floating-menu'); ?></p>
                                <i class="fa fa-check"></i><strong><?php _e('Target Link', 'wp-floating-menu'); ?> :</strong><p><?php _e(' You can change target link to load target link within the same page or new tab.', 'wp-floating-menu'); ?></p>
                                <i class="fa fa-check"></i><strong><?php _e('Menu Icon Type', 'wp-floating-menu-pro'); ?> :</strong><?php _e('<p> You can select if you want to choose icon from default iconset or custom icon.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Select Menu Icon', 'wp-floating-menu-pro'); ?> :</strong><?php _e('<p> You can set menu icon to display. Here you can choose thress sets of icons. You can choose either dashicons, font-awesome icons or genericons.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Custom Menu Icon', 'wp-floating-menu-pro'); ?> :</strong><?php _e('<p> If icon you needed is not listed in default icon set gallery, you can set your custom icon here. You can input either dashicons, font-awesome icons or genericons icon value here. For e.g. For dashicon, you can set icon value as "dashicons dashicons-admin-site", if fontawesome, you can set "fa fa-bars" and if <p style="color:red;">vesper icon</p>(Additional icon set, thanks to <a href="https://github.com/kkvesper/vesper-icons" target="_blank">vesper icons</a>), you can set it as "vs vs-ninja" and so on. You can set icon value in custom icon field only among 4 of the icon sets and is restricted beyond that.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Remove', 'wp-floating-menu'); ?> :</strong><p><?php _e(' You can remove the menu field by clicking "remove" link.', 'wp-floating-menu'); ?></p>
                            </div>

                            <div class="about-title-content">
                                <div class="wpfm-title"><?php _e('Custom Template options', 'wp-floating-menu'); ?></div>
                                <p><?php _e("While on custom template design page, you can change the layout of menu by first selecting menu template you want to implement custom design to changing various options in <strong>Custom Template</strong> page. Given Setting will be implemented to only those layouts that have custom template implemented while using <b>Display Setting</b> menu tab in <b>Edit Menu</b> section.", "wp-floating-menu"); ?></p>
                                <i class="fa fa-check"></i><strong><?php _e('Select Menu Layout ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select Buildin custom template list where you want to implement custom design into.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Icon Background Color ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select icon background color for menu icon from here.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Icon Size ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select icon size for menu icon from here.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Icon Margin ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select icon spaces between individual icon from here.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Title Font Color ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select title font color from here.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Title Typography ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select title font family from here.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Title Text Transform Style ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select title text tranform style for font from here. You can select uppercase, lowercase, capitalize or none(same as text input) from here.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Title Font Size ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select title font size from here.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Tooltip Font Color ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select tooltip text font color from here.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Title Font Size ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can choose among available fonts for the tooltip text.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Tooltip Typography ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select tooltip font family from here.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-check"></i><strong><?php _e('Menu Background Color ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>You can select menu background color from here. Option is applicable only to template 5.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-star"></i><strong><?php _e('Note ', 'wp-floating-menu'); ?>:</strong><?php _e('<p>Setting differs according to menu template layout, so all setting maynot be implementable for all templates.</p>', 'wp-floating-menu'); ?>
                                <i class="fa fa-star"></i> <strong style="color:red"><?php _e('Smart Scroll By Offset', 'wp-floating-menu'); ?></strong> <i class="fa fa-star"></i>
                                <p><?php _e('Sometimes, due to either theme or other plugins, <strong>"position"</strong> value doesn\'t seems to be recognized by our plugin for <strong>"One Page Navigator"</strong> feature to scroll and enable <strong>"active class"</strong> effect. This issue has been seen in many of clients. So, since <strong>version 1.3.2</strong>, we have added the option to change the way scroll function is being called in jquery from <strong>"position"</strong> to <strong>"offset"</strong>. Considering facts that all other requirement such as <strong>"Height"</strong> for the section to be scrolled are okay.', 'wp-floating-menu'); ?></p>
                            </div>

                            <p><?php _e('If you wish to view complete documentation, please visit:', 'wp-floating-menu'); ?> <a target='_blank' href='http://accesspressthemes.com/documentation/wp-floating-menu/'><?php _e("HERE", "wp-floating-menu"); ?></a></p> 
                        </div><!--  .about-content --> 
                    </div><!-- .about-desc-wrap -->         
                </div><!-- .about-wp-floating-menu-wrapper -->
            </div><!-- .postbox -->
        </div><!-- .post-body-content -->
        <div id="postbox-container-1" class="postbox-container">
            <?php include(WPFM_FILE_ROOT_DIR . 'inc/backend/includes/wpfm-sidebar.php'); ?>
        </div> <!-- #postbox-container-1 .postbox-container -->
    </div><!-- #post-body .metabox-holder columns-2 -->
</div><!-- .wpfm-inner-wrapper -->