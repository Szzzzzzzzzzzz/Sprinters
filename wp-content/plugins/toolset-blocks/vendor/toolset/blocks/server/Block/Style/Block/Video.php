<?php

namespace ToolsetBlocks\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\ABlock;


class Video extends ABlock {
	const KEY_STYLES_FOR_VIDEO = 'video';

	/**
	 * @param array $config
	 *
	 * @param bool $force_apply
	 *
	 * @return string
	 */
	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		return parent::get_css( $this->get_css_config(), $force_apply, $responsive_device );
	}

	/**
	 * @param FactoryStyleAttribute $factory
	 */
	public function load_block_specific_style_attributes( FactoryStyleAttribute $factory ) {
		$config = $this->get_block_config();
		// width
		if( isset( $config['width'] ) ) {
			$unit = isset( $config['widthUnit'] ) ? $config['widthUnit'] : '%';
			if( $style = $factory->get_attribute_width( $config['width'], $unit ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_VIDEO );
			}
		}

		// height
		if( isset( $config['height'] ) ) {
			$unit = isset( $config['heightUnit'] ) ? $config['heightUnit'] : 'px';
			if( $style = $factory->get_attribute_height( $config['height'], $unit ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_VIDEO );
			}
		}
	}

	private function get_css_config() {
		return array(
			self::CSS_SELECTOR_ROOT => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'margin', 'padding', 'border', 'border-radius', 'box-shadow', 'display'
				),
				self::KEY_STYLES_FOR_VIDEO => array(
					'width', 'height'
				)
			),
		);
	}
}
