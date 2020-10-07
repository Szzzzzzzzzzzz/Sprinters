<?php

namespace ToolsetBlocks\Block\Gallery\Shortcode;

/**
 * Adds Gallery Shortcodes
 *
 * @since 1.2
 */
class Factory {
	/**
	 * Initializes the class
	 */
	public function initialize() {
		$this->add_shortcodes();
	}

	/**
	 * Adds the shortcodes
	 */
	private function add_shortcodes() {
		( new Caption() )->initialize();
		( new AltText() )->initialize();
	}
}
