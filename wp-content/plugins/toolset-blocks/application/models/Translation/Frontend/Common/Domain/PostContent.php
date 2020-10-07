<?php

namespace OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain;

/**
 * Class PostContent
 *
 * Value Object for post content. This wouldn't be needed if string type hinting would be available.
 *
 * @package OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain
 *
 * @since TB 1.3
 */
class PostContent {
	/** @var string */
	private $content;

	/** @var IPostContentType */
	private $type;

	public function __construct( IPostContentType $type ) {
		$this->type = $type;
	}

	/**
	 * @param string $content
	 */
	public function set( $content ){
		if( ! is_string( $content ) ) {
			throw new \InvalidArgumentException( '$content must be a string.' );
		}

		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function get() {
		return $this->content;
	}

	public function regex_start() {
		return '<div class="' .$this->type->get_root_class() . '.*?".*?>';
	}

	public function regex_loop() {
		return '\[wpv-layout-meta-html\]';
	}

	public function regex_search() {
		return '\[wpv-filter-meta-html\]';
	}

	public function regex_end() {
		return '</div>';
	}

	/**
	 * Apply translation between start of the view block and the search container.
	 *
	 * @param $translation
	 */
	public function apply_translation_between_start_and_search( $translation ) {
		if( empty( $translation ) ) {
			return;
		}

		$translated_post_content = preg_replace(
			'#(<div class="' .$this->type->get_root_class() . '.*?".*?>)(.*?)(\[wpv-filter-meta-html\])#ism',
			"$1" . $translation . "$3",
			$this->get()
		);

		$this->set( $translated_post_content );
	}

	/**
	 * Apply translation between the search container and the loop.
	 * Search must be first in this scenario.
	 *
	 * @param $translation
	 */
	public function apply_translation_between_search_and_loop( $translation ) {
		if( empty( $translation ) ) {
			return;
		}

		$translated_post_content = preg_replace(
			'#(\[wpv-filter-meta-html\])(.*?)(\[wpv-layout-meta-html\])#ism',
			"$1" . $translation . "$3",
			$this->get()
		);

		$this->set( $translated_post_content );
	}

	/**
	 * Apply translation between the loop and the search container.
	 * Loop must be first in this scenario.
	 *
	 * @param $translation
	 */
	public function apply_translation_between_loop_and_search( $translation ) {
		if( empty( $translation ) ) {
			return;
		}

		$translated_post_content = preg_replace(
			'#(\[wpv-layout-meta-html\])(.*?)(\[wpv-filter-meta-html\])#ism',
			"$1" . $translation . "$3",
			$this->get()
		);

		$this->set( $translated_post_content );
	}

	/**
	 * Apply translation between the start of the view block and the loop.
	 *
	 * @param $translation
	 */
	public function apply_translation_between_start_and_loop( $translation ) {
		if( empty( $translation ) ) {
			return;
		}

		$translated_post_content = preg_replace(
			'#(<div class="' .$this->type->get_root_class() . '.*?".*?>)(.*?)(\[wpv-layout-meta-html\])#ism',
			"$1" . $translation . "$3",
			$this->get()
		);

		$this->set( $translated_post_content );
	}

	/**
	 * Apply translation between the search container and the end of the view block.
	 *
	 * @param $translation
	 */
	public function apply_translation_between_search_and_end( $translation ) {
		if( empty( $translation ) ) {
			return;
		}

		$translated_post_content = preg_replace(
			'#(\[wpv-filter-meta-html\])(.*)(</div>)$#ism',
			"$1" . $translation . "$3",
			$this->get()
		);

		$this->set( $translated_post_content );
	}

	/**
	 * Apply translation between the loop and the end of the view block.
	 * Here is some oddness in the database storage. When there is only a loop,
	 * Views still apply [wpv-filter-meta-html] after the closing div. No clue why.
	 *
	 * @param $translation
	 */
	public function apply_translation_between_loop_and_end( $translation ) {
		if( empty( $translation ) ) {
			return;
		}

		$translated_post_content = preg_replace(
			'#(\[wpv-layout-meta-html\])(.*)(</div>(?:\[wpv-filter-meta-html\])?)$#ism',
			"$1" . $translation . "$3",
			$this->get()
		);

		$this->set( $translated_post_content );
	}
}
