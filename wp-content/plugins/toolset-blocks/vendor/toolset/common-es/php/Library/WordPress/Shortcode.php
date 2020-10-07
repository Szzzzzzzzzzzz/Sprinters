<?php

namespace ToolsetCommonEs\Library\WordPress;

/**
 * Class Shortcode
 * @package ToolsetCommonEs\Library\WordPress
 */
class Shortcode {
	public function add_shortcode( $tag, $callback ) {
		add_shortcode( $tag, $callback );
	}
}
