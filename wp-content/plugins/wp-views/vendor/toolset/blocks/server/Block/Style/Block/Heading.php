<?php

namespace ToolsetBlocks\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\ABlock;

/**
 * Class Heading
 *
 * @package ToolsetBlocks\Block\Style\Block
 */
class Heading extends ABlock {
	const KEY_STYLES_FOR_HEADING = 'heading';

	/** @var string h1,h2...h6 */
	private $tag;

	/**
	 * @return string
	 */
	public function get_css_block_class() {
		return '.tb-heading';
	}

	/**
	 * @param FactoryStyleAttribute $factory
	 */
	public function load_block_specific_style_attributes( FactoryStyleAttribute $factory ) {
		$config = $this->get_block_config();
		if( isset( $config[ 'align' ] ) ) {
			if( $style = $factory->get_attribute( 'text-align', $config['align' ] ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_HEADING );
			}
		}
	}

	protected function get_css_selector( $css_selector = self::CSS_SELECTOR_ROOT ) {
		// Determine css selector. If it's root there is no extra css selector required.
		$css_selector = $css_selector === self::CSS_SELECTOR_ROOT ? '' : $css_selector . ' ';

		$css_selector = substr($css_selector, 0, 1) == ':' ? $css_selector : ' ' . $css_selector;

		return $this->tag . $this->get_css_block_class() . '[data-' . str_replace( '/', '-', $this->get_name() ) . '="' . $this->get_id() . '"]' . $css_selector;
	}

	public function get_css( $config = array(), $force_apply = false, $responsive_device = null ) {
		return parent::get_css( $this->get_css_config(), $force_apply, $responsive_device );
	}

	private function get_css_config() {
		return array(
			parent::CSS_SELECTOR_ROOT => array(
				self::KEY_STYLES_FOR_HEADING => array(
					'text-align',
				),
				parent::KEY_STYLES_FOR_COMMON_STYLES => array(
					'font-size',
					'font-family',
					'font-style',
					'font-weight',
					'line-height',
					'letter-spacing',
					'text-decoration',
					'text-shadow',
					'text-transform',
					'color',
					'text-align',
					'background-color',
					'border-radius',
					'padding',
					'margin',
					'box-shadow',
					'border',
					'display',
				),
			),
			'a' => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => array(
					'color',
					'text-decoration',
				),
			),
		);
	}

	public function make_use_of_inner_html( $inner_html ) {
		preg_match( '#\<(h[1-6])#', $inner_html, $matches );

		if( isset( $matches[1] ) ) {
			$this->tag = $matches[1];
		}
	}
}
