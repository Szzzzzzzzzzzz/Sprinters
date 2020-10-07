<?php

namespace OTGS\Toolset\Views\Models\Translation\RegisterAndStore\Block\Domain;

use OTGS\Toolset\Views\Models\Translation\RegisterAndStore\Common\Domain\TranslationService;

/**
 * Class CustomSearchFilter
 *
 * Registers the label of the Search input, no matter if a core filter (like "Title") or Types field filters,
 * to the page translation package and takes care of replacing the the translated string in the translated post.
 *
 * @package OTGS\Toolset\Views\Models\Translation\RegisterAndStore\Block\Domain
 *
 * @since TB 1.3
 */
class CustomSearchFilter implements ITranslatableBlock{
	/** @var Block */
	private $block;

	/** @var TranslationService  */
	private $translation_service;

	public function __construct( Block $block, TranslationService $translation_service) {
		$this->block = $block;
		$this->translation_service = $translation_service;
	}

	/**
	 * Register label of the search filters.
	 *
	 * @inheritDoc
	 */
	public function register_strings_to_translate( $strings_to_translate = [] ) {
		while( $line = $this->block->content_lines()->next() ) {
			$strings_to_translate = $this->add_filter_label( $strings_to_translate, $line );
		}

		return $strings_to_translate;
	}

	/**
	 * Store translated labels to translated post.
	 *
	 * @inheritDoc
	 */
	public function store_translated_strings( \WP_Block_Parser_Block $block, $translations, $lang ) {
		while( $line = $this->block->content_lines()->next() ) {
			$this->store_filter_label( $block, $translations, $lang, $line );
		}

		return $block;
	}

	/**
	 * Find filter label and adds it to translation.
	 *
	 * @param $strings_to_translate
	 * @param $line
	 *
	 * @return array
	 */
	private function add_filter_label( $strings_to_translate, $line ) {
		if ( $filter_label = $this->find_label( $line ) ) {
			$strings_to_translate[] = $this->translation_service->get_line_object(
				$filter_label,
				__( 'Custom search filter label', 'wpv-views' ),
				$this->block->name()
			);
		}

		return $strings_to_translate;
	}

	/**
	 * @param \WP_Block_Parser_Block $block
	 * @param $translations
	 * @param $lang
	 * @param $line
	 */
	private function store_filter_label( \WP_Block_Parser_Block $block, $translations, $lang, $line ) {
		if( ! $filter_label = $this->find_label( $line ) ) {
			return;
		}

		$filter_label_translated = $this->translation_service->get_translated_text_by_translations(
			$filter_label,
			$translations,
			$this->block->name(),
			$lang
		);

		if ( ! empty( $filter_label_translated ) ) {
			$line_translated  = preg_replace(
				'#(<label .*?class=".*?wpv-custom-search-filter.*?>)(.*?)(</label>)#ism',
				'$1' . $filter_label_translated . '$3',
				$line
			);

			// Also change the label inside the labelText and content block attributes.
			// This is required to display the block in the translated edit page.
			if( property_exists( $block, 'attrs' ) && is_array( $block->attrs ) ) {
				if( isset( $block->attrs['labelText'] ) ) {
					$block->attrs['labelText'] = [ $filter_label_translated ];
				}

				if( isset( $block->attrs['content'] ) ){
					$block->attrs['content'] = preg_replace(
						'#(<label .*?class=".*?wpv-custom-search-filter.*?>)(.*?)(</label>)#ism',
						'$1' . $filter_label_translated . '$3',
						$block->attrs['content']
					);
				}
			}

			$block->innerHTML = str_replace( $line, $line_translated, $block->innerHTML );
		}
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	private function find_label( $text ) {
		if( ! is_string( $text ) ) {
			throw new \InvalidArgumentException( '$text must be a string.' );
		}

		if( preg_match( '#\[wpml-string.*?\](.*?)\[\/wpml-string\]#ism', $text ) ) {
			// Old structure using the wpml-string shhortcode.
			return '';
		}

		if( preg_match( '#<label .*?class=".*?wpv-custom-search-filter.*?>(.*?)</label>#ism', $text, $matches ) ) {
			return $matches[1];
		}

		// No label found.
		return '';
	}
}
