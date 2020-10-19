<?php defined('ABSPATH') or die("No script kiddies please!");?>
<?php global $post; ?>
<div class="wpfm-wrapper wpfm-clear wrap">
    <div class="wpfm-head">     
        <?php include(WPFM_FILE_ROOT_DIR. 'inc/backend/includes/wpfm-header.php');?> 
    </div>
<div class="wpfm-inner-wrapper" id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <div class="postbox">
                <div class="wpfm-backend-wrapper clearfix" id="col-container">
                    <!-- Add Menu page --> 
                    <div class='wpfm-tab-contents' id='tab-wpfm-add-menu'>   
                       <form action="<?php echo admin_url() . 'admin-post.php' ?>" method='post' class="wpfm-template-setting-form" > 
                            <?php include_once('template-inner-fields/add-template-inner.php');?>
                            <div class="wpfm-actions" id="wpfm-submit">
                            <input type="hidden" name="action" value="wpfm_save_template_options" />
                            <input type="submit" class="button-primary wpfm-template-save-button" name='wpfm_save_template_settings' value="<?php _e( 'Save Template Setting', 'wpfm-floating-menu' ); ?>" />
                            <?php wp_nonce_field( 'wpfm_nonce_save_template_settings', 'wpfm_add_nonce_save_template_settings' ); ?>
                        </div>           
                       </form>                                               
                    </div>   
                </div><!--  .wpfm backend wrapper --> 
            </div><!-- .postbox -->
        </div><!-- #post-body-content -->
        <div id="postbox-container-1" class="postbox-container">
         <?php include(WPFM_FILE_ROOT_DIR. 'inc/backend/includes/wpfm-sidebar.php');?>
        </div> <!-- #postbox-container-1 .postbox-container -->
        </div><!-- .metabox-holder columns-2 #post-body -->
    </div><!-- .poststuff -->
</div>