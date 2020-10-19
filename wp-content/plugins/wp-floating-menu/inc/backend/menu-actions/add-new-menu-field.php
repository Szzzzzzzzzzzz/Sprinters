<?php defined('ABSPATH') or die("No script kiddies please!");?>

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
                       <?php include_once('inner-fields/add-menu-field-inner.php');?>                                               
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