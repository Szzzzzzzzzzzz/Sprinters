<?php

namespace OTGS\Toolset\Views\Models\Translation\Frontend\WPA\Domain;

use OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain\IBlockContent;

/**
 * Class BlockContent
 *
 * Value object for block content. Providing some helper function to get certain parts of the content.
 *
 * @package OTGS\Toolset\Views\Models\Translation\Frontend\WPA\Domain
 *
 * @since TB 1.3
 */
class BlockContent implements IBlockContent {
	/** @var string */
	private $content;

	/** @var bool */
	private $has_search = false;

	/**
	 * BlockContent constructor.
	 *
	 * @param $some_markup
	 */
	public function __construct( $some_markup ) {
		if( ! preg_match(
			"#<!-- (?:wp:)?toolset-views/wpa-editor.*?-->.*?<!-- /(?:wp:)?toolset-views/wpa-editor -->#ims",
			$some_markup,
			$matches )
		) {
			throw new \InvalidArgumentException( '$some_markup does not contain a wpa editor block.' );
		}

		$this->content = $matches[0];

		if( preg_match(
			'#\/wp:toolset-views\/custom-search-container -->#ism',
			$this->content,
			$search )
		) {
			$this->has_search = true;
		}
	}

	public function get() {
		return $this->content;
	}

	public function has_search() {
		return $this->has_search;
	}

	public function get_content_search_container() {
		if( preg_match(
			'#<!-- (?:wp:)?toolset-views/custom-search-container.*?-->' .
			'.*?<!-- /(?:wp:)?toolset-views/custom-search-container -->#ism',
			$this->content,
			$matches
		)
		) {
			return $matches[0];
		}

		return '';
	}


	public function get_content_between_start_and_search() {
		if(
		preg_match(
			'#<div class="wp-block-toolset-views-wpa-editor.*?".*?>' .
			'(.*?)<!-- wp:toolset-views\/custom-search-container#ism',
			$this->content,
			$matches
		)
		) {
			return $matches[1];
		}

		return '';
	}

	public function get_content_between_start_and_output() {
		if( preg_match(
			'#<div class="wp-block-toolset-views-wpa-editor.*?".*?>(.*?)<!-- wp:toolset-views\/view-layout-block#ism',
			$this->content,
			$matches )
		) {
			return $matches[1];
		}

		/*
			This point should never be reached with the current state of Views.
			But it may change once we allow to have separated standalone blocks for search and output.
		 */
		throw new \RuntimeException( 'The content of the WPA is missing the start' .
									 ' of the WPA block or the Output block.' );
	}

	/**
	 * @return string
	 */
	public function get_content_between_search_and_output() {
		if( preg_match(
			'#\/wp:toolset-views\/custom-search-container.*?-->(.*?)<!-- wp:toolset-views\/view-layout-block#ism',
			$this->content,
			$matches )
		) {
			return $matches[1];
		}

		return '';
	}

	/**
	 * @return string
	 */
	public function get_content_between_output_and_search() {
		if( preg_match(
			'#\/wp:toolset-views\/view-layout-block.*?-->(.*?)<!-- wp:toolset-views\/custom-search-container#ism',
			$this->content,
			$matches )
		) {
			return $matches[1];
		}

		return '';
	}

	/**
	 * @return string
	 */
	public function get_content_between_search_and_end() {
		if( preg_match(
			'#\/wp:toolset-views\/custom-search-container -->(.*?)<!-- \/wp:toolset-views\/wpa-editor -->#ism',
			$this->content,
			$matches )
		) {
			return $matches[1];
		}

		return '';
	}

	/**
	 * @return string
	 */
	public function get_content_between_output_and_end() {
		if( preg_match(
			'#\/wp:toolset-views\/view-layout-block -->(.*?)</[a-z]*>\s?<!-- \/wp:toolset-views\/wpa-editor -->#ism',
			$this->content,
			$matches )
		) {
			return $matches[1];
		}

		/*
			This point should never be reached with the current state of Views.
			But it may change once we allow to have separated standalone blocks for search and output.
		 */
		throw new \RuntimeException( 'The content of the WPA is missing the end of the WPA block' .
									 ' or the output block.' );
	}
}
