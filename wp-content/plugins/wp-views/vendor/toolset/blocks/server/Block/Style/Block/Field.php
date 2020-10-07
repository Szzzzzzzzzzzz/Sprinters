<?php

namespace ToolsetBlocks\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Block\Common;

class Field extends Common {
	public function get_css_block_class() {
		return '.tb-field';
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		return parent::get_css( $this->get_css_config(), $force_apply, $responsive_device );
	}

	private function get_css_config() {
		return array(
			parent::CSS_SELECTOR_ROOT => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => array(
					'font-size', 'font-family', 'font-style', 'font-weight', 'line-height', 'letter-spacing',
					'text-decoration', 'text-shadow', 'text-transform', 'text-align', 'color',
					'background-color', 'border-radius', 'padding', 'margin', 'box-shadow', 'border', 'display',
				),
			),
			'a' => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'color', 'text-decoration'
				)
			),
		);
	}
}
