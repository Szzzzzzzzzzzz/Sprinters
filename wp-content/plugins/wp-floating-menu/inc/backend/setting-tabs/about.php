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
                                <div class="wpfm-title"><?php _e('About Plugin', 'wp-floating-menu'); ?></div>
                                <?php _e('<p><strong>WP Floating Menu</strong> is a FREE WordPress plugin that makes your WordPress website\'s usability high with one page navigator menu and sticky navigation menu.</p>', 'wp-floating-menu'); ?>
                                <?php _e('<p>It includes just the right amount of settings and options for you to get in with the plugin and create floating menu easily and efficiently.
                                            Sleekly designed floating menu on side of your screen helps user navigate the site more quickly, easily increasing user experience on the site.
                                            </p>', 'wp-floating-menu'); ?> 
                            </div>
                            <div class="about-title-content">
                                <div class="wpfm-title"><?php _e('About Author', 'wp-floating-menu'); ?></div>
                                <?php _e('<p><strong>AccessPress Themes</strong> is a venture of Access Keys - who has developed hundreds of Custom WordPress themes and plugins for its clients over the years.</p>', 'wp-floating-menu'); ?>      
                            </div>
                          
                            <div class="about-title-content">
                                <div class="wpfm-title"><?php _e('Get in Touch', 'wp-floating-menu'); ?></div>                       
                                <?php _e('If you have any question/feedback, please get in touch:', 'wp-floating-menu'); ?>
                                <span class="wpfm-enquiries-wrap"><?php _e('<strong>General enquiries:</strong>', 'wp-floating-menu'); ?><a target="_blank" href="mailto:info@accesspressthemes.com"><?php _e('info@accesspressthemes.com', 'wp-floating-menu'); ?></a></span>
                                <span class="wpfm-enquiries-wrap"><?php _e('<strong>Support:</strong><a href="https://accesspressthemes.com/support/">https://accesspressthemes.com/support/</a>', 'wp-floating-menu'); ?></span>
                                <span class="wpfm-enquiries-wrap"><?php _e('<strong>Sales:</strong><a href="mailto:sales@accesspressthemes.com">sales@accesspressthemes.com</a>', 'wp-floating-menu'); ?></span>
                            </div><br/>
                            <div class="about-title-content">
                                <div class="wpfm-title"><?php _e('Connect With Us', 'wp-floating-menu'); ?></div> 
                                <iframe style="border: none; overflow: hidden; width: 100%; height: 206px;" src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FAccessPress-Themes%2F1396595907277967&amp;width=842&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=true&amp;appId=1411139805828592" width="240" height="150" frameborder="0" scrolling="no"></iframe></p>
                                <ul class="wpfm-social-icon">
                                    <li><a href="http://www.pinterest.com/accesspresswp/" target="_blank"><i class="fa fa-pinterest fa-2x"></i></a></li>
                                    <li><a href="https://www.flickr.com/photos/accesspressthemes/" target="_blank"><i class="fa fa-flickr fa-2x"></i></a></li>
                                    <li><a href="https://twitter.com/apthemes" target="_blank"><i class="fa fa-twitter fa-2x"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- .about-desc-wrap -->
                </div><!-- .about-wp-floating-menu-wrapper -->
            </div><!-- .postbox -->
        </div><!-- .post-body-content -->
        <div id="postbox-container-1" class="postbox-container">
            <?php include(WPFM_FILE_ROOT_DIR . 'inc/backend/includes/wpfm-sidebar.php'); ?>
        </div><!-- #postbox-container-1 .postbox-container -->
    </div><!-- #post-body .metabox-holder columns-2 -->
</div><!-- .wpfm-inner-wrapper -->