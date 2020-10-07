<?php

namespace ToolsetBlocks\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\Common;
use ToolsetCommonEs\Block\Style\Block\IBlock;
use ToolsetCommonEs\Block\Style\Block\IFactory;

/**
 * Class Factory
 *
 * Maps block array comming from WordPress to our Style/Block class. The array can be filtered, so it's important
 * to prove every key before use.
 *
 * @since 0.9.3
 */
class Factory implements IFactory {
	/** @var FactoryStyleAttribute */
	private $factory_style_attribute;

	/**
	 * Factory constructor.
	 *
	 * @param FactoryStyleAttribute $factory_attribute
	 */
	public function __construct( FactoryStyleAttribute $factory_attribute ) {
		$this->factory_style_attribute = $factory_attribute;
	}

	/**
	 * @param array $config
	 *
	 * @return IBlock
	 */
	public function get_block_by_array( $config ) {
		if (
			! is_array( $config ) ||
			! array_key_exists( 'blockName', $config ) ||
			! array_key_exists( 'attrs', $config )
		) {
			return;
		}

		$block_name = $config['blockName'];
		$block_attributes = $config['attrs'];

		switch ( $block_name ) {
			case 'toolset-blocks/grid-column':
				return new GridColumn( $block_attributes, $block_name );
			case 'toolset-blocks/grid':
				return new Grid( $block_attributes, $block_name );
			case 'toolset-blocks/audio':
				return new Common( $block_attributes, $block_name );
			case 'toolset-blocks/countdown':
				return new Countdown( $block_attributes, $block_name );
			case 'toolset-blocks/field':
				return new Field( $block_attributes, $block_name );
			case 'toolset-blocks/button':
				return new Button( $block_attributes, $block_name );
			case 'toolset-blocks/container':
				return new Container( $block_attributes, $block_name );
			case 'toolset-blocks/fields-and-text':
				return new FieldsAndText( $block_attributes, $block_name );
			case 'toolset-blocks/gallery':
				return new Gallery( $block_attributes, $block_name );
			case 'toolset-blocks/heading':
				return new Heading( $block_attributes, $block_name );
			case 'toolset-blocks/image':
				return new Image( $block_attributes, $block_name );
			case 'toolset-blocks/star-rating':
				return new StarRating( $block_attributes, $block_name );
			case 'toolset-blocks/video':
				return new Video( $block_attributes, $block_name );
			case 'toolset-blocks/progress':
				return new Progress( $block_attributes, $block_name );
			case 'toolset-blocks/repeating-field':
				return new RepeatingField( $block_attributes, $block_name );
			case 'toolset-blocks/social-share':
				return new SocialShare( $block_attributes, $block_name );
			case 'toolset-blocks/youtube':
				return new Youtube( $block_attributes, $block_name );
			case 'toolset-blocks/image-slider':
				return new ImageSlider( $block_attributes, $block_name );
			default:
				return;
		}
	}
}
