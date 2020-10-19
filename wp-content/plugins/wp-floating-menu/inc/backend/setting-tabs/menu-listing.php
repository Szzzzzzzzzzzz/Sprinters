<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<?php
    if (isset($_GET['action'], $_GET['id'])) {
        include(WPFM_FILE_ROOT_DIR. 'inc/backend/menu-actions/edit-menu.php');
    }else if (isset($_GET['action']) && $_GET['action'] == 'wpfm-add-menu') 
    {
      include(WPFM_FILE_ROOT_DIR. 'inc/backend/menu-actions/add-new-menu-field.php');       
    } 
     else {
?>
<div class="wpfm-wrapper wpfm-clear">
    <div class="wpfm-head">     
        <?php include(WPFM_FILE_ROOT_DIR. 'inc/backend/includes/wpfm-header.php');?> 
    </div> 
     <?php
    $options = get_option( WPFM_SETTINGS );
    if(isset($_GET['message']) && $_GET['message']==1) { ?>
        <div class="wpfm-message notice notice-success is-dismissible">
            <p>
            <?php 
              echo __( 'Menu Updated.', 'wp-floating-menu' );
            ?>
            </p>
        </div>
    <?php }else if(isset($_GET['message']) && $_GET['message']==2){ ?>
        <div class="wpfm-message notice notice-success is-dismissible">
            <p>
            <?php 
              echo __( 'No Changes Made.', 'wp-floating-menu' );
            ?>
            </p>
        </div>
    <?php }else if(isset($_GET['message']) && $_GET['message']==3){ ?>
        <div class="wpfm-message notice notice-success is-dismissible">
            <p>
            <?php 
              echo __( 'Menu Deleted Successfully.', 'wp-floating-menu' );
            ?>
            </p>
        </div>
    <?php }
    global $wpdb;  
    $table_name = $wpdb->prefix . "wp_floating_menu_details";
    $menu_lists = $wpdb->get_results( "SELECT * FROM $table_name"); ?>
    <div class="wpfm-inner-wrapper" id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                    <div class="postbox">
                        <div class="wpfm-backend-wrapper clearfix" id="col-container">
                            <div class="inside" id="wpfm-menu-setting-wrapper">
                                <div class="wpfm-header-title">
                                    <div class="wpfm-menu-header-left">
                                        <?php _e('Navigation Menu','wp-floating-menu'); ?>
                                    </div>
                                    <?php if( count( $menu_lists ) > 0 ){ ?>
                                    <div class="wpfm-menu-header-right" style="display:none;">
                                        <a href="javascript:void(0)" class="wpfm-add-new-menu">
                                            <?php _e('Add New Menu', 'wp-floating-menu');?></a>
                                    </div>
                                    <?php } else{?>
                                    <div class="wpfm-menu-header-right">
                                        <a href="<?php echo admin_url() . 'admin.php?page=wpfm-admin&action=wpfm-add-menu' ?>" class="wpfm-add-new-menu">
                                            <?php _e('Add New Menu', 'wp-floating-menu');?></a>
                                    </div>
                                    <?php } ?>
                                </div>
                                    <table class="wp-list-table widefat fixed posts wpfm-table">
                                    <thead>
                                		<tr>
                                			<th scope="col" id="name" class="manage-column column-shortcode">
                                				<?php _e( 'Menu Title', 'wp-floating-menu' ); ?>
                                			</th>
                                            <th scope="col" id="menu-bar-position" class="manage-column column-shortcode">
                                				<?php _e( 'Menu Position', 'wp-floating-menu' ); ?>
                                            </th>
                                            <th scope="col" id="menu-status" class="manage-column column-shortcode">
                                				<?php _e( 'Template', 'wp-floating-menu' ); ?>
                                            </th>                                          
                                		</tr>
                                	</thead>
                                    <tfoot>
                                		<tr>
                                			<th scope="col" id="name" class="manage-column column-shortcode">
                                				<?php _e( 'Menu Title', 'wp-floating-menu' ); ?>
                                			</th>
                                            <th scope="col" id="menu-bar-position" class="manage-column column-shortcode">
                                				<?php _e( 'Menu Position', 'wp-floating-menu' ); ?>
                                			</th>
                                            <th scope="col" id="menu-status" class="manage-column column-shortcode">
                                				<?php _e( 'Template', 'wp-floating-menu' ); ?>
                                            </th>   
                                		</tr>
                                	</tfoot>
                                <?php 
                                $counter=1;   
                                if( count( $menu_lists ) > 0 ) {
                                 foreach ($menu_lists as $row) { 
                                    $menu_settings = unserialize($row->menu_display_setting_details); ?>
                                    <tbody id="the-list">
                                    <tr class="<?php if ( $counter % 2 != 0 ) { ?>alternate<?php } ?>">
                                       <td class="title column-title has-row-actions"> 
                                            <?php echo esc_attr($row->menu_name); ?>    
                                            <div id="wpfm-menu-action" class="row-actions" >
                                                <span class="wpfm-menu-edit-entry">
                                                    <a href="<?php echo admin_url('admin.php?page=wpfm-admin&action=wpfm-edit-menu&id='.$row->id ); ?>" class="wpfm-menu-action-button"><?php _e('Edit |','wp-floating-menu'); ?></a>
                                                    <input type="hidden" name="current_post_id" value="<?php echo intval($row->id) ;?>" /> 
                                                </span>
                                                <span class="wpfm-menu-delete-entry">
                                                    <?php $wpfm_delete_nonce = wp_create_nonce( 'wpfm-remove-menu-settings-nonce' ); ?>
                                                    <a class="wpfm-btn-wrap" href="<?php echo admin_url() . 'admin-post.php?action=wpfm_menu_delete_options&_wpnonce=' . $wpfm_delete_nonce.'&id='.$row->id ?>" onclick="return confirm('<?php _e( 'Delete This Menu? There is no undo', 'wp-floating-menu' ); ?>')"><?php _e( 'Delete', 'wp-floating-menu' ); ?></a>
                                                </span>
                                            </div>
                                       </td>
                                       <td class="title column-title">
                                          <?php if(isset($menu_settings['menu_design']['menu_placement']) && $menu_settings['menu_design']['menu_placement']=='left'){  echo __('Left','wp-floating-menu');}
                                                else if(isset($menu_settings['menu_design']['menu_placement']) && $menu_settings['menu_design']['menu_placement']=='right'){echo __('Right','wp-floating-menu');}
                                          ?>
                                       </td>
                                       <td class="title column-title">
                                            <?php if(isset($menu_settings['menu_design']['menu_template_style']) && $menu_settings['menu_design']['menu_template_style'] == 'buildin'){ 
                                                $title_id = explode("-", esc_attr($menu_settings['menu_design']['template_number'])); 
                                                echo 'Template '.$title_id[1]; }
                                            else if(isset($menu_settings['menu_design']['menu_template_style']) && $menu_settings['menu_design']['menu_template_style'] == 'custom-template'){ echo __('Custom Template','wp-floating-menu');}?>
                                       </td>                     
                                    </tr>
                                    </tbody>
                                    <?php $counter++; } } else{ ?> 
                                       <tbody id="the-list">
                                        <tr class="alternate">
                                            <td class="title column-title has-row-actions" ></td> 
                                            <td class="title column-title has-row-actions" >
                                                <strong style="text-align:center"><?php _e('No Menu To Display. Try Adding New Menu','wp-floating-menu'); ?></strong>
                                            </td>
                                            <td class="title column-title has-row-actions" ></td> 
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