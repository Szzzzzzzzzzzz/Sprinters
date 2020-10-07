<?php
namespace ToolsetBlocks\PublicDependencies\Dependency;

use ToolsetBlocks\Plugin\Constants;

/**
 * Loads frontend JS for blocks that need it
 *
 * @package ToolsetBlocks
 * @since 1.0.0
 */
class Javascript implements IContent {
	/** @var Constants */
	private $constants = null;

	/**
	 * Constructor
	 *
	 * @param Constants $constants Used for phpunit tests.
	 */
	public function __construct( Constants $constants ) {
		$this->constants = $constants;
	}

	/**
	 * @param string $content
	 *
	 * @return bool
	 */
	public function is_required_for_content( $content ) {
		if ( preg_match( '(data-countdown|data-shareurl|tb-progress-data|tb-repeating-field--carousel|tb-repeating-field--masonry|tb-container-parallax|tb-image-slider|tb-gallery)', $content ) === 1 ) {
			return true;
		}

		return false;
	}

	/**
	 * @return mixed
	 */
	public function load_dependencies() {
		wp_enqueue_script(
			'tb-frontend-js',
			$this->constants->constant( 'TB_URL' ) . 'public/js/frontend.js',
			array( 'jquery', 'underscore', 'toolset-common-es-masonry' ),
			$this->constants->constant( 'TB_VER' )
		);
	}

}
