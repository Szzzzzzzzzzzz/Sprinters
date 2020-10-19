<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();
require_once( plugin_dir_path( __FILE__ ).'../includes/opinionstage-client-session.php' );
add_action( 'admin_menu', 'opinionstage_register_menu_page' );
function opinionstage_register_menu_page() {
	if (function_exists('add_menu_page')) {
		$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
		$os_client_logged_in = opinionstage_user_logged_in();
			if ($os_client_logged_in) {
				if ( isset($os_options['item_count']) && $os_options['item_count'] > 0) {
					add_menu_page(
						__('Opinion Stage', OPINIONSTAGE_TEXT_DOMAIN),
						__('Opinion Stage', OPINIONSTAGE_TEXT_DOMAIN),
						'edit_posts',
						OPINIONSTAGE_VIEW_ITEM_SLUG,
						'opinionstage_load_template',
						plugins_url('admin/images/os-icon.png', plugin_dir_path( __FILE__ )),
						'25.234323221'
					);
					add_submenu_page(OPINIONSTAGE_VIEW_ITEM_SLUG, 'View My Items', 'My Items', 'edit_posts', OPINIONSTAGE_VIEW_ITEM_SLUG);
					add_submenu_page(OPINIONSTAGE_VIEW_ITEM_SLUG, 'Create...', 'Create...', 'edit_posts', OPINIONSTAGE_MENU_SLUG, 'opinionstage_load_template');								
					add_submenu_page(OPINIONSTAGE_VIEW_ITEM_SLUG, 'Placements', 'Placements', 'edit_posts', OPINIONSTAGE_PLACEMENT_SLUG , 'opinionstage_load_template' );
					add_submenu_page(OPINIONSTAGE_VIEW_ITEM_SLUG, 'Help Resources', 'Help Resources', 'edit_posts', OPINIONSTAGE_HELP_RESOURCE_SLUG,'opinionstage_load_template' );
				}
				else {
					add_menu_page(
						__('Opinion Stage', OPINIONSTAGE_TEXT_DOMAIN),
						__('Opinion Stage', OPINIONSTAGE_TEXT_DOMAIN),
						'edit_posts',
						OPINIONSTAGE_MENU_SLUG,
						'opinionstage_load_template',
						plugins_url('admin/images/os-icon.png', plugin_dir_path( __FILE__ )),
						'25.234323221'
					);	
					add_submenu_page(OPINIONSTAGE_MENU_SLUG, 'Create...', 'Create...', 'edit_posts', OPINIONSTAGE_MENU_SLUG);
					add_submenu_page(OPINIONSTAGE_MENU_SLUG, 'View My Items', 'My Items', 'edit_posts','opinionstage-view-my-items','opinionstage_load_template');
					add_submenu_page(OPINIONSTAGE_MENU_SLUG, 'Placements', 'Placements', 'edit_posts', OPINIONSTAGE_PLACEMENT_SLUG , 'opinionstage_load_template' );
					add_submenu_page(OPINIONSTAGE_MENU_SLUG, 'Help Resources', 'Help Resources', 'edit_posts', OPINIONSTAGE_HELP_RESOURCE_SLUG,'opinionstage_load_template' );					
				}
		}else{
			add_menu_page(
				__('Opinion Stage', OPINIONSTAGE_TEXT_DOMAIN),
				__('Opinion Stage', OPINIONSTAGE_TEXT_DOMAIN),
				'edit_posts',
				OPINIONSTAGE_GETTING_STARTED_SLUG,
				'opinionstage_load_template',
				plugins_url('admin/images/os-icon.png', plugin_dir_path( __FILE__ )),
				'25.234323221'
			);
			add_submenu_page(OPINIONSTAGE_GETTING_STARTED_SLUG, 'Getting Started', 'Getting Started', 'edit_posts', OPINIONSTAGE_GETTING_STARTED_SLUG,'opinionstage_load_template' );
			add_submenu_page(OPINIONSTAGE_GETTING_STARTED_SLUG, 'Create...', 'Create...', 'edit_posts', OPINIONSTAGE_MENU_SLUG, 'opinionstage_load_template');
			add_submenu_page(OPINIONSTAGE_GETTING_STARTED_SLUG, 'Placements', 'Placements', 'edit_posts', OPINIONSTAGE_PLACEMENT_SLUG , 'opinionstage_load_template' );
		}
	}
}

		function opinionstage_load_template() {
			$OSAPL = OpinionStageAdminPageLoader::getInstance();
			$OSAPL->OSAPL_LoadTemplateFile();
		}
?>