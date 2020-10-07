<?php

namespace ToolsetBlocks\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Block\Common;

/**
 * Class FieldsAndText
 */
class FieldsAndText extends Common {
	/**
	 * @return string
	 */
	public function get_css_block_class() {
		return '.tb-fields-and-text';
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		return parent::get_css( $this->get_css_config(), $force_apply, $responsive_device );
	}

	private function get_css_config() {
		return array(
			parent::CSS_SELECTOR_ROOT => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => 'all'
			),
			'p' => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => array(
					'font-size', 'font-family', 'font-style', 'font-weight', 'line-height', 'letter-spacing',
					'text-decoration', 'text-shadow', 'text-transform', 'color'
				)
			),
		);
	}
}
