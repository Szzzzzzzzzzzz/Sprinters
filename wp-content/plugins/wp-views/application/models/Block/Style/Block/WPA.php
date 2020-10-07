<?php

namespace OTGS\Toolset\Views\Models\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\Common;
use ToolsetBlocks\Block\Style\Block\Grid;
use ToolsetCommonEs\Library\MobileDetect\MobileDetect;

/**
 * Loop Item Styles
 *
 * @package OTGS\Toolset\Views\Models\Block\Style\Block
 */
class WPA extends View {
	/**
	 * @var MobileDetect
	 */
	private $mobile_detect;

	public function get_css_block_class() {
		return '.wp-block-toolset-views-wpa-editor';
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		$css = parent::get_css( $this->css_config(), $force_apply, $responsive_device );
		$css = preg_replace(
			'/\[(data-toolset-views-wpa-editor)=\"([^\"]*)\"\]/',
			'',
			$css
		);
		return $css;
	}

	/**
	 * Abuse filter_block_content, which is called before filter_content to get the instance
	 * of MobileDetect. A bit hacky, but this can be removed once the WPA rendering is using the default approach for#
	 * rendering blocks -> views-3260.
	 *
	 * @param $content
	 * @param MobileDetect $device_detect
	 *
	 * @return string
	 */
	public function filter_block_content( $content, MobileDetect $device_detect ) {
		$this->mobile_detect = $device_detect;

		return $content;
	}

	/**
	 * Required to use the filter_content instead of filter_block_content as the block content
	 * only contains the wpa shortcode, which is rendered on filter_content.
	 *
	 * This shouldn't become a problem as there will always be just one WPA per page.
	 * Can be changed to use filter_block_content as all other blocks once views-3260 is applied.
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function filter_content( $content ) {
		$config = $this->get_block_config();
		$style = isset( $config['style'] ) ? $config['style'] : [];

		if( $this->mobile_detect ) {
			$block_alignment = $this->get_block_alignment( $style, $this->mobile_detect );
		} else {
			$block_alignment = isset( $style['blockAlign'] ) ?
				$style['blockAlign'] :
				false;
		}

		if( ! $block_alignment ) {
			return $content;
		}

		if(
			preg_match(
			'/(class=[\"\'](?:.*?)wp-block-toolset-views-wpa-editor(.*)align'.$block_alignment.'(?:.*?))([\"\'])/',
			$content )
		) {
			// The align class is already applied.
			return $content;
		}

		// Add align class.
		return preg_replace(
			'/(class=[\"\'](?:.*?)wp-block-toolset-views-wpa-editor(?:.*?))([\"\'])/',
			'$1 align'.$block_alignment.'$2',
			$content,
			1 // Only the first class needs to be adjusted.
		);
	}
}
