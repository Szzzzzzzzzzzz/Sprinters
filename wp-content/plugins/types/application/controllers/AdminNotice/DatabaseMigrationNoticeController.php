<?php

namespace OTGS\Toolset\Types\AdminNotice;

use OTGS\Toolset\Common\Utility\Admin\Notices\Builder as NoticeBuilder;
use OTGS\Toolset\Common\WPML\WpmlService;
use OTGS\Toolset\Types\Condition\IsDatabaseMigrationUnderway;
use OTGS\Toolset\Types\Condition\ShowDatabaseMigrationNotice;
use OTGS\Toolset\Types\Model\Wordpress\Screen;
use Types_Admin_Menu;

/**
 * Handles the displaying of a notice to migrate the relationship datatabase structures to a new version.
 *
 * It's not the case now but it has the potential to be version-agnostic.
 *
 * @since 3.4
 */
class DatabaseMigrationNoticeController {


	const UPGRADE_PROMPT_NOTICE_ID = 'types_relationships_database_migration_notice';

	const WHATS_NEW_POST_URL = 'https://toolset.com/2020/07/what\'s-new-in-types-3-4/';
	const TIPS_FOR_SAFE_UPGRADE_URL = 'https://toolset.com/faq/how-to-safely-update-the-database/';

	const IS_UPGRADING_OPTION = 'types_show_database_upgrade_notice';

	const DURING_UPGRADE_NOTICE_ID = 'types_relationships_database_migration_underway';


	/** @var ShowDatabaseMigrationNotice */
	private $display_condition;


	/** @var IsDatabaseMigrationUnderway */
	private $is_migration_underway_condition;


	/** @var NoticeBuilder */
	private $notice_builder;


	/** @var \Toolset_Constants */
	private $constants;


	/** @var WpmlService */
	private $wpml_service;


	/** @var Screen */
	private $screen;


	/**
	 * DatabaseMigrationNoticeController constructor.
	 *
	 * @param ShowDatabaseMigrationNotice $display_condition
	 * @param IsDatabaseMigrationUnderway $is_migration_underway_condition
	 * @param NoticeBuilder $notice_builder
	 * @param \Toolset_Constants $constants
	 * @param WpmlService $wpml_service
	 * @param Screen $screen
	 */
	public function __construct(
		ShowDatabaseMigrationNotice $display_condition,
		IsDatabaseMigrationUnderway $is_migration_underway_condition,
		NoticeBuilder $notice_builder,
		\Toolset_Constants $constants,
		WpmlService $wpml_service,
		Screen $screen
	) {
		$this->display_condition = $display_condition;
		$this->is_migration_underway_condition = $is_migration_underway_condition;
		$this->notice_builder = $notice_builder;
		$this->constants = $constants;
		$this->wpml_service = $wpml_service;
		$this->screen = $screen;
	}


	/**
	 * Create the notice when and how appropriate.
	 */
	public function initialize() {
		add_action( 'current_screen', function () {
			if ( ! $this->display_condition->is_met() ) {
				return;
			}

			// The notice ID is different on the Relationships page because we wand the notice to be non-dismissible there.
			// Basically, it's a different notice.
			$notice = $this->notice_builder->createNotice(
				self::UPGRADE_PROMPT_NOTICE_ID,
				NoticeBuilder::TYPE_REQUIRED_ACTION
			);

			$notice->set_is_only_for_administrators( true )
				->set_is_dismissible_permanent( false )
				->set_content( $this->build_notice_content() );

			$this->notice_builder->addNotice( $notice );
		} );

		add_action( 'types_rendering_related_content_metabox', function () {
			$this->print_notice_during_migration();
		} );

		add_action( 'types_rendering_repeatable_field_group_metabox', function () {
			$this->print_notice_during_migration();
		} );
	}


	private function print_notice_during_migration() {
		if ( ! $this->is_migration_underway_condition->is_met() ) {
			return;
		}

		$this->notice_builder->createNotice(
			self::DURING_UPGRADE_NOTICE_ID,
			NoticeBuilder::TYPE_WARNING
		)
			->set_is_only_for_administrators( false )
			->set_is_dismissible_permanent( false )
			->set_content( '<p>' . esc_html(
					__( 'Toolset Types is upgrading the database. Changes made to post relationships and repeatable field groups at the moment may not be preserved.', 'wpcf' )
				) . '</p>' )
			->set_inline_mode( true )
			->render();
	}


	private function build_notice_content() {
		return sprintf(
			'<div class="toolset-notice-info"><h2>%s</h2><p class="toolset-list-of-links">%s <a href="%s">%s</a> <a href="%s">%s</a></p></div>',
			esc_html( __( 'Toolset needs to update the database. This will take a few minutes to complete.', 'wpcf' ) ),
			$this->notice_builder->tplButtonPrimary( esc_html( __( 'Update now', 'wpcf' ) ), [
				'href' => esc_url_raw( add_query_arg( [
					'page' => Types_Admin_Menu::PAGE_NAME_DATABASE_UPGRADE,
					'return_to' => urlencode( $_SERVER['REQUEST_URI'] ),
					'return_to_title' => $this->screen->get_admin_page_title(),
				], admin_url( 'admin.php' ) ) ),
			] ),
			self::WHATS_NEW_POST_URL,
			esc_html( __( 'What\'s new', 'wpcf' ) ),
			self::TIPS_FOR_SAFE_UPGRADE_URL,
			esc_html( __( 'Tips for safe database updates', 'wpcf' ) )
		);
	}

}
