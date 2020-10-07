<?php

namespace OTGS\Toolset\Views\Models\Translation\Frontend\View\Domain;

// Common Dependencies
use OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain\IBlockContent;

/**
 * Class BlockContent
 *
 * Value object. Just verifies that the given content is content of a View block and holds/serves it.
 *
 * @package OTGS\Toolset\Views\Models\Translation\Frontend\View\Domain
 *
 * @since TB 1.3
 */
class BlockContent implements IBlockContent {
	/** @var string Content of the Block. */
	private $content;

	/** @var bool */
	private $has_search = false;

	/**
	 * BlockContent constructor.
	 *
	 * @param string $some_content
	 * @param ViewId $view_id
	 */
	public function __construct( $some_content, ViewId $view_id ) {
		if( ! is_string( $some_content ) ||
			! preg_match( "#<!-- (?:wp:)?toolset-views/view-editor" .
						  "[^>]*?[\"']viewId[\"']:".$view_id->get()."[^>]*?-->" .
						  ".*?<!-- /(?:wp:)?toolset-views/view-editor -->" .
						  "#ims", $some_content, $matches )
		) {
			throw new \InvalidArgumentException(
				'$some_markup does not contain a view block with id ' . $view_id->get() . '.'
			);
		}

		$this->content = $matches[0];


		if( preg_match(
			'#\/(?:wp:)?toolset-views\/custom-search-container -->#ism',
			$this->content,
			$search )
		) {
			$this->has_search = true;
		}
	}

	/**
	 * @return string
	 */
	public function get() {
		return $this->content;
	}

	public function has_search() {
		return $this->has_search;
	}

	public function get_content_search_container() {
		if(
			preg_match(
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
				'#<div class="wp-block-toolset-views-view-editor.*?".*?>' .
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
			'#<div class="wp-block-toolset-views-view-editor.*?".*?>(.*?)<!-- wp:toolset-views\/view-layout-block#ism',
			$this->content,
			$matches )
		) {
			return $matches[1];
		}

		/*
			This point should never be reached with the current state of Views.
			But it may change once we allow to have separated standalone blocks for search and output.
		 */
		throw new \RuntimeException( 'The content of the view is missing the start' .
									 ' of the View block or the Output block.' );
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
			'#\/wp:toolset-views\/custom-search-container -->(.*?)<!-- \/wp:toolset-views\/view-editor -->#ism',
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
			'#\/wp:toolset-views\/view-layout-block -->(.*?)</[a-z]*>\s?<!-- \/wp:toolset-views\/view-editor -->#ism',
			$this->content,
			$matches )
		) {
			return $matches[1];
		}

		/*
			This point should never be reached with the current state of Views.
			But it may change once we allow to have separated standalone blocks for search and output.
		 */
		throw new \RuntimeException( 'The content of the view is missing the end of the View block' .
									 ' or the output block.' );
	}
}
