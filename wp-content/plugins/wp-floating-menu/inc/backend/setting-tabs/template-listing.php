<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<?php
    if (isset($_GET['action'], $_GET['id']) && $_GET['action']=='wpfm-edit-template') 
    {
      include(WPFM_FILE_ROOT_DIR. 'inc/backend/template-actions/edit-template.php');
    }else if (isset($_GET['action']) && $_GET['action'] == 'wpfm-add-template') 
    {
      include(WPFM_FILE_ROOT_DIR. 'inc/backend/template-actions/add-new-template.php');       
    } 
    else
    {
?>
<div class="wpfm-wrapper wpfm-clear">
    <div class="wpfm-head">     
        <?php include(WPFM_FILE_ROOT_DIR. 'inc/backend/includes/wpfm-header.php');?> 
    </div> 
     <?php
    $options = get_option( WPFM_SETTINGS );
    if( isset( $_GET['message'] ) && $_GET['message']==1) { ?>
        <div class="wpfm-message notice notice-success is-dismissible">
            <p><?php 
                echo __( 'New Template Added And Saved.', 'wp-floating-menu' ); 
                ?>
            </p>
        </div>
    <?php } else if( isset( $_GET['message'] ) && $_GET['message']==2) { ?>
        <div class="wpfm-message notice notice-success is-dismissible">
            <p><?php 
                echo __( 'No Changes was Made.', 'wp-floating-menu' );
                ?>
            </p>
        </div>
   <?php }else if(isset($_GET['message']) && $_GET['message']==3){ ?>
        <div class="wpfm-message notice notice-success is-dismissible">
            <p>
            <?php 
              echo __( 'Template Deleted Successfully.', 'wp-floating-menu' );
            ?>
            </p>
        </div>
    <?php } ?>   
    <?php 
        global $wpdb;  
        $table_name = $wpdb->prefix . "wp_floating_menu_custom_templates";
        $template_lists = $wpdb->get_results( "SELECT * FROM $table_name");
    ?> 
    <div class="wpfm-inner-wrapper" id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                    <div class="postbox">
                        <div class="wpfm-backend-wrapper clearfix" id="col-container">
                            <div class="inside" id="wpfm-menu-setting-wrapper">
                                <div class="wpfm-header-title">
                                    <div class="wpfm-menu-header-left">
                                        <?php _e('Build Your Own Custom Template','wp-floating-menu'); ?>
                                    </div>
                                    <?php if( count( $template_lists ) > 0 ) { ?>
                                    <div class="wpfm-menu-header-right" style="display:none;">
                                        <a href="javascript:void(0)" class="wpfm-add-new-menu">
                                            <?php _e('Add New Template', 'wp-floating-menu');?>
                                        </a>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="wpfm-menu-header-right">
                                        <a href="<?php echo admin_url() . 'admin.php?page=wpfm-custom-template&action=wpfm-add-template' ?>" class="wpfm-add-new-menu">
                                            <?php _e('Add New Template', 'wp-floating-menu');?>
                                        </a>
                                    </div>
                                    <?php } ?>
                                </div>
                                
                                <table class="wp-list-table widefat fixed posts apct-table">
                                    <thead>
                                		<tr>
                                			<th scope="col" id="name" class="manage-column column-shortcode">
                                				<?php _e( 'Custom Template Title', 'wp-floating-menu' ); ?>
                                			</th>
                                            <th scope="col" id="menu-bar-position" class="manage-column column-shortcode">
                                				<?php _e( 'Template Layout', 'wp-floating-menu' ); ?>
                                            </th>
                                                                                   
                                		</tr>
                                	</thead>
                                    <tfoot>
                                		<tr>
                                			<th scope="col" id="name" class="manage-column column-shortcode">
                                				<?php _e( 'Custom Template Title', 'wp-floating-menu' ); ?>
                                			</th>
                                            <th scope="col" id="menu-bar-position" class="manage-column column-shortcode">
                                				<?php _e( 'Template Layout', 'wp-floating-menu' ); ?>
                                			</th>
                                            
                                		</tr>
                                	</tfoot>
                                 <?php $counter=1;  
                                 if( count( $template_lists ) > 0 ) { 
                                 foreach ($template_lists as $row) {
                                    $template_settings = unserialize($row->template_details);
                                    ?>
                                    <tbody id="the-list">
                                    <tr class="<?php if ( $counter % 2 != 0 ) { ?>alternate<?php } ?>">
                                       <td class="title column-title has-row-actions"> 
                                            <?php echo esc_attr($row->template_name); ?>    
                                            <div id="wpfm-menu-action" class="row-actions" >
                                                <span class="wpfm-menu-edit-entry">
                                                    <a href="<?php echo admin_url('admin.php?page=wpfm-custom-template&action=wpfm-edit-template&id='.$row->id ); ?>" class="wpfm-menu-action-button"><?php _e('Edit |','wp-floating-menu'); ?></a>
                                                    <input type="hidden" name="current_post_id" value="<?php echo intval($row->id) ;?>" /> 
                                                </span>
                                                <span class="wpfm-menu-delete-entry">
                                                    <?php $wpfm_delete_template_nonce = wp_create_nonce( 'wpfm-remove-template-settings-nonce' ); ?>
                                                    <a class="wpfm-btn-wrap" href="<?php echo admin_url() . 'admin-post.php?action=wpfm_menu_delete_template_options&_wpnonce=' . $wpfm_delete_template_nonce.'&id='.$row->id ?>" onclick="return confirm('<?php _e( 'Delete current Template ?', 'wp-floating-menu' ); ?>')"><?php _e( 'Delete', 'wp-floating-menu' ); ?></a>
                                                </span>
                                            </div>
                                       </td>
                                       <td class="title column-title">
                                            <?php if(!empty($template_settings['custom_template']['menu_layout'])){ 
                                                $title_id = explode("-", $template_settings['custom_template']['menu_layout']);
                                                echo 'Template '.$title_id[1]; } else{ echo 'No Template Assigned'; } ?>
                                       </td>
                                                          
                                    </tr>
                                    </tbody>
                                    <?php $counter++; } } else { ?> 
                                    <tbody id="the-list">
                                        <tr class="alternate">
                                            <td class="title column-title"></td> 
                                            <td class="title column-title">
                                                <strong style="text-align:center"><?php _e('No Template To display.','wp-floating-menu'); ?></strong>
                                            </td>
                                            <td class="title column-title"></td> 
                                        </tr>
                                    </tbody>
                                    <?php } ?>
                                </table>
                            </div><!-- .inside #wpfm-menu-setting-wrapper-->
                        </div><!--  .wpfm backend wrapper --> 
                    </div><!-- .postbox -->
            </div> <!-- #post-body-content -->
            <div id="postbox-container-1" class="postbox-container">
             <?php include(WPFM_FILE_ROOT_DIR. 'inc/backend/includes/wpfm-sidebar.php');?>
            </div> <!-- #postbox-container-1 .postbox-container -->
       </div><!-- .metabox-holder columns-2 #post-body -->
    </div><!-- .poststuff -->
</div>
<?php } ?>