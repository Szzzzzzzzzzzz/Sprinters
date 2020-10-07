<?php

namespace OTGS\Toolset\Views\Controller;

class ContentTemplate {
	private $toolset_assets_manager;

	const SCRIPT_HANDLE = 'ct-block-editor';

	public function __construct( $toolset_assets_manager ) {
		$this->toolset_assets_manager = $toolset_assets_manager;
	}

	public function initialize() {
		add_action( 'admin_init', array( $this, 'register_assets' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_assets' ) );
	}

	public function register_assets() {
		$this->toolset_assets_manager->register_script(
			self::SCRIPT_HANDLE,
			WPV_URL . '/public/js/contentTemplate.js',
			array(),
			WPV_VERSION,
			false
		);
	}

	public function enqueue_assets() {
		do_action( 'toolset_enqueue_scripts', array( self::SCRIPT_HANDLE ) );
	}
}
