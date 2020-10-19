<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="wpfm-header-title"><?php _e('Add New Navigation Menu','wpfm-floating-menu'); ?></div>   
<div class="form-body" id="wpfm-menu-add-wrapper">
    <div class="wpfm-form-body-left">    
        <form id="wpfm-update-nav-menu" class="wpfm-add-page-fields" method="post" enctype="multipart/form-data">           
            <div class="wpfm-menu-list-field" id="wpfm-custom-link-field">
                <div class="wpfm-field-header" id="wpfm-custom-link-content">
                    <div class="wpfm-drag-icon"></div>
                    <h3 class="wpfm-menu-title"><?php _e('Add Link','wp-floating-menu');?></h3>
                    <span class="wpfm-arrow-down wpfm-arrow"></span>
                </div>
                <div class="wpfm-list-inner-content">
                    <div id="wpfm-custom-link-url" class="wpfm-custom-link">                                
                        <label for="wpfm_custom_link_url" class=""><?php _e('URL','wp-floating-menu');?></label>
                        <div class="wpfm-input-field-wrapper">
                            <input type="url" disabled="disabled" name="wpfm_custom_link_url" id="wpfm-custom-link-url"/>
                        </div>                             
                    </div>
                    <div id="wpfm-custom-link-text" class="wpfm-custom-link">                                
                        <label for="wpfm_custom_link_text" class=""><?php _e('Link Text','wp-floating-menu');?></label>
                        <div class="wpfm-input-field-wrapper">
                            <input type="text" disabled="disabled" name="wpfm_custom_link_text" id="wpfm-custom-link-text"/>
                        </div>                             
                    </div>
                    <p class="button-controls wp-clearfix">
                        <span class="spinner wpfm-view-wrap is-active" style="display:none;"></span> 
                        <span class="wpfm-add-to-menu">
                            <input disabled="disabled" type="button" field-data="<?php _e('Custom Link','wp-floating-menu');?>" submit-field-val="2" class="button-secondary wpfm-submit-add-to-menu"  id="wpfm-submit-posttype-custom-link" value="Add to Menu" name="wpfm_add_post_type_custom_link"/>
                        </span>                
                    </p>
                </div>
            </div><!-- #wpfm-custom-link-field .wpfm-menu-list-field -->  
        </form><!-- .wpfm-add-page-fields -->
    </div><!--wpfm-form-body-left -->

    <div class="wpfm-form-body-right">
        <form action="<?php echo admin_url() . 'admin-post.php' ?>" method='post' class="wpfm-menu-structure">
            <input type="hidden" name="action" value="wpfm_add_menu_field_options" />
               <?php wp_nonce_field( 'wpfm_nonce_add_menu_fields', 'wpfm_add_nonce_add_menu_fields' ); ?>
                <div class="wpfm-upper-body-section">
                    <div class="wpfm-add-new-form">
                        <label for="wpfm_save_menu_name" class="wpfm-input-field-controller"><?php _e('Menu Name','wp-floating-menu');?></label>
                        <div class="wpfm-input-field-wrapper">
                            <input type="text" name="wpfm_save_menu_name" class="wpfm-menu-name" id="wpfm-menu-name-add"/>
                             <div class="menu-name-error" style="color:red; display:none;">
                                <?php _e('Please Enter Menu Name','wp-floating-menu'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wpfm-publishing-action" id="wpfm-save-form-upper">
                        <input type="submit" name="wpfm_add_menu_fields" id="wpfm-add-menu-header" class="button button-primary wpfm-menu-field-add" value="Add Menu"/>
            		</div>
                </div>
        </form><!-- #wpfm-update-nav-menu .wpfm-save-menu -->
    </div><!-- .wpfm-form-body-right -->
</div><!-- .Form-Body -->
