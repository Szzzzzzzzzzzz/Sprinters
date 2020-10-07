<?php

namespace ToolsetBlocks\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\ABlock;
use ToolsetCommonEs\Library\MobileDetect\MobileDetect;

class Image extends ABlock {
	const KEY_STYLES_FOR_IMAGE = 'img';
	const KEY_STYLES_FOR_IMAGE_HOVER = 'img-hover';
	const KEY_STYLES_FOR_CAPTION = 'caption';

	const FRAME_NONE = 'none';
	const FRAME_POLAROID = 'polaroid';
	const FRAME_SHADOW_1 = 'shadow1';

	/**
	 * Image constructor.
	 *
	 * @param $block_config
	 * @param string $block_name_for_id_generation
	 */
	public function __construct( $block_config, $block_name_for_id_generation = 'unknown' ) {
		$block_config = $this->apply_defaults( $block_config );

		parent::__construct( $block_config, $block_name_for_id_generation );

		/* The image block already had an blockId before getting rid of inline css.
		   But old saved blocks are not valid. Luckily the old id is below 10 digits, while the new is above. */
		if( strlen( $this->get_id() ) < 10 ) {
			throw new \InvalidArgumentException( 'Old Image block.' . $this->get_id() );
		}
	}

	private function apply_defaults( $block_config ){
		// ApplyMaxWidth is true by default.
		$is_wide_or_full = isset( $block_config['align'] ) && in_array( $block_config['align'], [ 'wide', 'full' ] );
		if( ! $is_wide_or_full &&
			( ! isset( $block_config['style'] ) || ! isset( $block_config['style']['applyMaxWidth'] ) ) ) {
			$block_config['style']['applyMaxWidth'] = true;
		}

		return $block_config;
	}

	/**
	 * @param array $config
	 *
	 * @param bool $force_apply
	 *
	 * @return string
	 */
	public function get_css( $config = array(), $force_apply = false, $responsive_device = null ) {
		$config = $this->get_block_config();

		$frame = isset( $config['frame'] ) ? $config['frame'] : 'none';
		$frame_config = $this->get_frame_config( $frame );

		return parent::get_css( $frame_config, $force_apply, $responsive_device );
	}

	/**
	 * @param FactoryStyleAttribute $factory
	 */
	public function load_block_specific_style_attributes( FactoryStyleAttribute $factory ) {
		$config = $this->get_block_config();

		// caption color
		if( isset( $config['style'] ) ) {
			$factory->apply_style_to_block_for_all_devices(
				$this,
				$config['style'],
				'color',
				self::KEY_STYLES_FOR_CAPTION,
				'captionColor'
			);
		}

		/*
		 * Hover Styles
		 */
		if( ! isset( $config['hover'] ) || ! is_array( $config['hover'] ) ) {
			return;
		}

		$hover = $config['hover'];

		// scale
		if( isset( $hover['scale'] ) ) {
			if( $style = $factory->get_attribute( 'scale', $hover['scale'] ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_IMAGE_HOVER );
			}
		}

		// rotate
		if( isset( $hover['rotate'] ) ) {
			if( $style = $factory->get_attribute( 'rotate', $hover['rotate'] ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_IMAGE_HOVER );
			}
		}

		// z-index
		if( isset( $hover['zIndex'] ) ) {
			if( $style = $factory->get_attribute( 'zindex', $hover['zIndex'] ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_IMAGE_HOVER );
			}
		}
	}

	public function filter_block_content( $content, MobileDetect $device_detect ) {
		$config = $this->get_block_config();
		$style = isset( $config['style'] ) ? $config['style'] : [];
		$block_alignment = $this->get_block_alignment( $style, $device_detect );

		if( ! $block_alignment ) {
			return $content;
		}

		$alignments_require_container = [ 'left', 'center', 'right' ];

		if( in_array( $block_alignment, $alignments_require_container ) ) {
			// Remove wp-block-image from figure tag.
			$content = preg_replace(
				'/(figure.*?class=[\"\'](?:.*?))(wp-block-image)(.*?)([\"\'])/',
				'$1$3$4',
				$content,
				1
			);

			// Add container.
			$content = '<div class="wp-block-image">' . $content . '</div>';
		}

		// Add align class.
		return $this->common_filter_block_content_by_block_css_class(
			'tb-image',
			$content,
			$device_detect
		);
	}

	private function get_frame_config( $frame = 'none' ) {
		switch( $frame ) {
			case self::FRAME_POLAROID:
				return $this->get_frame_polaroid_config();
			case self::FRAME_SHADOW_1:
				return $this->get_frame_shadow_1_config();
			default:
				return $this->get_frame_none_config();
		}
	}

	private function get_frame_none_config() {
		return array(
			self::CSS_SELECTOR_ROOT => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'rotate', 'z-index', 'display', 'width', 'max-width'
				),
			),
			'figcaption' => array(
				self::KEY_STYLES_FOR_CAPTION => array(
					'color'
				)
			),
			'img' => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'box-shadow', 'border-radius', 'background-color', 'padding', 'margin', 'border', 'height'
				),
			),
			':hover' => array(
				self::KEY_STYLES_FOR_IMAGE_HOVER => array(
					'rotate', 'z-index'
				)
			),
			':hover img' => array(
				self::KEY_STYLES_FOR_IMAGE_HOVER => array(
					'scale',
				)
			)
		);
	}

	private function get_frame_polaroid_config() {
		return array(
			self::CSS_SELECTOR_ROOT => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'rotate', 'z-index', 'width', 'max-width'
				),
			),
			'figcaption' => array(
				self::KEY_STYLES_FOR_CAPTION => array(
					'color'
				)
			),
			'.tb-image-polaroid' => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'margin'
				)
			),
			'.tb-image-polaroid-inner' => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'background-color', 'border', 'border-radius', 'box-shadow', 'padding'
				)
			),
			'img' => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'height'
				),
			),
			':hover' => array(
				self::KEY_STYLES_FOR_IMAGE_HOVER => array(
					'rotate', 'scale', 'z-index'
				)
			),

		);
	}

	private function get_frame_shadow_1_config() {
		return array(
			self::CSS_SELECTOR_ROOT => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'rotate', 'z-index'
				),
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'width', 'max-width'
				)
			),
			'figcaption' => array(
				self::KEY_STYLES_FOR_CAPTION => array(
					'color'
				)
			),
			'.tb-image-shadow-1' => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'margin'
				)
			),
			'.tb-image-shadow-1-inner' => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'background-color', 'border', 'border-radius', 'box-shadow', 'padding'
				)
			),
			'img' => array(
				self::KEY_STYLES_FOR_COMMON_STYLES => array(
					'height'
				),
			),
			':hover' => array(
				self::KEY_STYLES_FOR_IMAGE_HOVER => array(
					'rotate', 'scale', 'z-index'
				)
			),

		);
	}
}
