<?php

namespace ToolsetBlocks\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\ABlock;

/**
 * Class SocialShare
 *
 * @package ToolsetBlocks\Block\Style\Block
 */
class SocialShare extends ABlock {
	const KEY_STYLES_FOR_SOCIAL = 'social';

	/**
	 * @param FactoryStyleAttribute $factory
	 */
	public function load_block_specific_style_attributes( FactoryStyleAttribute $factory ) {
		$config = $this->get_block_config();
		if ( ! isset( $config['iconSize' ] ) ) {
			$config['iconSize' ] = 32; // default size
		}
		if( isset( $config[ 'textAlign' ] ) ) {
			if( $style = $factory->get_attribute( 'text-align', $config['textAlign' ] ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_SOCIAL );
			}
		}
		if( $style = $factory->get_attribute( 'width', [ 'width' => $config['iconSize' ] ] ) ) {
			$this->add_style_attribute( $style, self::KEY_STYLES_FOR_SOCIAL );
		}
		if( $style = $factory->get_attribute( 'height', [ 'height' => $config['iconSize' ] ] ) ) {
			$this->add_style_attribute( $style, self::KEY_STYLES_FOR_SOCIAL );
		}
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		return parent::get_css( $this->get_css_config(), $force_apply, $responsive_device );
	}

	private function get_css_config() {
		return array(
			parent::CSS_SELECTOR_ROOT => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => array(
					'background-color', 'border-radius', 'font-size', 'line-height',
					'color', 'padding', 'margin', 'box-shadow', 'border', 'display',
					'text-align'
				),
			),
			'.SocialMediaShareButton' => array(
				self::KEY_STYLES_FOR_SOCIAL => array(
					'text-align', 'width', 'height',
				)
			),
		);
	}
}
