<?php defined('ABSPATH') or die("No script kiddies please!");?>

<div class="wpfm-wrapper wpfm-clear wrap">
    <div class="wpfm-head">     
        <?php include(WPFM_FILE_ROOT_DIR. 'inc/backend/includes/wpfm-header.php');?> 
    </div> 
     <?php
    $options = get_option( WPFM_SETTINGS ); ?>
<div class="wpfm-inner-wrapper" id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <div class="postbox" id="wpfm-post-box">
                <div class="wpfm-backend-wrapper clearfix" id="col-container">                      
                    <!-- Edit Display option for menu page -->
                    <form action="<?php echo admin_url() . 'admin-post.php' ?>" method='post' class="wpfm-setting-form" >
                        <div class='wpfm-tab-contents' id='tab-wpfm-display-setting'>                 
                            <?php include( 'template-inner-fields/edit-template-inner.php' ); ?>
                        </div>
                        <div class="wpfm-actions" id="wpfm-submit">
                            <input type="hidden" name="action" value="wpfm_save_edited_template_options" />
                            <input type="submit" class="button-primary wpfm-template-save-edited_button" name='wpfm_save_edited_template_settings' value="<?php _e( 'Save Template Setting', 'wp-floating-menu' ); ?>" />
                            <?php wp_nonce_field( 'wpfm_nonce_save_edited_template_settings', 'wpfm_add_nonce_save_edited_template_settings' ); ?>
                        </div>                       
                    </form>                
                </div><!--  .wpfm backend wrapper --> 
            </div><!-- .postbox -->
        </div><!-- #post-body-content -->
        <div id="postbox-container-1" class="postbox-container">
         <?php include(WPFM_FILE_ROOT_DIR. 'inc/backend/includes/wpfm-sidebar.php');?>
        </div> <!-- #postbox-container-1 .postbox-container -->
        </div><!-- .metabox-holder columns-2 #post-body -->
    </div><!-- .poststuff -->
</div>