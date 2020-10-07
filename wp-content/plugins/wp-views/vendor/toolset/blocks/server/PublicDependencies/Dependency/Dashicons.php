<?php

namespace ToolsetBlocks\PublicDependencies\Dependency;

/**
 * Dashicons dependency
 *
 * @since 1.0.0
 */
class Dashicons implements IContent {

	/**
	 * Returns true/false if the current dependency is required for the content
	 *
	 * @param string $content Content of the current post
	 *
	 * @return bool
	 */
	public function is_required_for_content( $content ) {
		if( strpos( $content, '--dashicons' ) !== false ) {
			return true;
		}

		return false;
	}

	/**
	 * Function to load the dependencies
	 */
	public function load_dependencies() {
		wp_enqueue_style( 'dashicons' );
	}
}
