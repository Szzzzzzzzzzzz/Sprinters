<?php

namespace OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain\TranslatableStructure;

use OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain\ITranslatable;
use OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain\PostContent;
use OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain\IBlockContent;
use OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain\Settings;

/**
 * Class OutputBeforeSearch
 *
 * @package OTGS\Toolset\Views\Models\Translation\Frontend\Common\Domain
 *
 * @since TB 1.3
 */
class OutputBeforeSearch implements ITranslatable {

	/** @var ITranslatable */
	private $delegate_to;

	/**
	 * OutputBeforeSearch constructor.
	 *
	 * @param ITranslatable|null $different_structure
	 */
	public function __construct( ITranslatable $different_structure = null ) {
		$this->delegate_to = $different_structure;
	}

	/**
	 * @inheritDoc
	 */
	public function translate_settings( Settings $settings, IBlockContent $block_current_language ) {
		// Nothing to do for the settings.
	}

	/**
	 * @inheritDoc
	 */
	public function translate_content( PostContent $post_untranslated, IBlockContent $block_translated ) {
		if(
			! preg_match(
				'#'.$post_untranslated->regex_start(). '.*?' .
				$post_untranslated->regex_loop() . '.*?' .
				$post_untranslated->regex_search() . '.*?' .
				$post_untranslated->regex_end().'$#ism',
				$post_untranslated->get()
			)
		) {
			// This structure does not apply.
			if( $this->delegate_to ) {
				$this->delegate_to->translate_content( $post_untranslated, $block_translated );
			}

			return;
		}

		// Between Block Start and Loop.
		$translation = $block_translated->get_content_between_start_and_output();
		$post_untranslated->apply_translation_between_start_and_loop( $translation );

		// Between Loop and Search.
		$translation = $block_translated->get_content_between_output_and_search();
		$post_untranslated->apply_translation_between_loop_and_search( $translation );

		// Between Search and Block End.
		$translation = $block_translated->get_content_between_search_and_end();
		$post_untranslated->apply_translation_between_search_and_end( $translation );
	}
}
