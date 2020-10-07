<?php

namespace OTGS\Toolset\Views\Services;

class WPMLService {
	const REGEX_LOOP_IN_LAYOUT_META_HTML =
		'/(<wpv-loop.*)<div.*?class=".*wp-block-toolset-views-view-template-block(.*) --><\/div>(.*<\/wpv-loop>)/ism';

	public function init() {
		add_filter( 'wpv_filter_override_view_layout_settings', array( $this, 'adapt_settings_for_translation' ), 10, 2 );
		add_filter( 'wpv_filter_localize_view_block_strings', array( $this, 'add_if_is_translated_content' ) );
	}

	/**
	 * Modifies View settings if WPML is installed, but only for Views created as blocks, in the frontend.
	 *
	 * @param array $settings View settings.
	 * @param int   $id View/WPA ID.
	 * @return array
	 */
	public function adapt_settings_for_translation( $settings, $id ) {
		// If WPML is active.
		$wpml_active_and_configured = apply_filters( 'wpml_setting', false, 'setup_complete' );

		// is_admin() is always true for ajax calls. Also Check if it's a pagination ajax call.
		$is_ajax_pagination = wp_doing_ajax() &&
							  array_key_exists( 'action', $_REQUEST ) &&
							in_array(
								$_REQUEST['action'],
								[ 'wpv_get_view_query_results', 'wpv_get_archive_query_results' ],
								true
							);
		$is_frontend_call_or_ajax_call = ! is_admin() || $is_ajax_pagination;

		if ( $is_frontend_call_or_ajax_call && $wpml_active_and_configured && ! \WPV_View_Base::is_archive_view( $id ) && isset( $settings['layout_meta_html'] ) ) {
			$view_data = get_post_meta( $id, '_wpv_view_data', true );
			if ( empty( $view_data ) ) {
				// This View does not hold block data:
				// it was probably created with the legacy editor.
				return $settings;
			}
			// sometimes ID of helper post used for preview generation is passed here
			// but we need to always use correct view ID
			$id = $view_data['general']['id'];
			$helper_id = $view_data['general']['initial_parent_post_id'];
			$post = \WP_Post::get_instance( $helper_id );
			if ( ! $post ) {
				// Maybe the initial post where the View was created has been removed.
				return $settings;
			}
			$translated_helper_id = apply_filters( 'wpml_object_id', $helper_id, $post->post_type, true );
			if ( $helper_id !== $translated_helper_id ) {
				// if post is translated we need to extract the view markup
				// and replace content using it
				// we're extracting view markup because we can have more
				// than one view on a page
				$translated_helper = get_post( $translated_helper_id );
				$service = new ViewParsingService();
				$html = $service->get_view_markup( $translated_helper->ID, $id );
				$settings = $this->update_settings_from_html( $html, $settings );
			}
		}
		return $settings;
	}

	private function translate_table( $original_loop, $translated_loop ) {
		// Table Header.
		if( preg_match(
			'#<!-- (?:wp:)?toolset-views/table-header-row.*?-->(.*?)<!-- /(?:wp:)?toolset-views\/table-header-row -->#ism',
			$translated_loop,
			$translated_table_header
		) ) {
			$original_loop = preg_replace(
				'#(<table.*?class=".*?view-table.*?>.*?<thead><tr>)(.*?)(<\/tr><\/thead>)#ism',
				'\1'. do_blocks( $translated_table_header[1] ) . '\3',
				$original_loop
			);
		}

		// Table Body.
		if( preg_match(
			'#(<!-- (?:wp:)?toolset-views/table-row.*?-->(.*?)<!-- /(?:wp:)?toolset-views\/table-row -->)#ism',
			$translated_loop,
			$translated_table_body
		) ) {
			$original_loop = preg_replace(
				'#(<wpv-loop><tr.*?>)(.*?)(</tr></wpv-loop>)#ism',
				'\1'. do_blocks( $translated_table_body[1] ) . '\3',
				$original_loop
			);
		}

		// Table Footer.
		// -- There is no Table Footer option for the View table layout.

		return $original_loop;
	}
	/**
	 * Parses through a View block and replaces those part of content that are translatable.
	 *
	 * @param string $html
	 * @param array  $settings
	 *
	 * @return array
	 */
	public function update_settings_from_html( $html, $settings ) {
		// Main content.
		$translated_loop = preg_replace(
			'#^.*(<!-- wp:toolset-views/view-template-block.*-->.*<!-- /wp:toolset-views/view-template-block -->).*$#Us',
			'$1',
			$html
		);

		$settings['layout_meta_html'] = preg_replace(
			self::REGEX_LOOP_IN_LAYOUT_META_HTML,
			'\1'. do_blocks( $translated_loop ) . '\3',
			$settings['layout_meta_html']
		);

		// Main content. Tables.
		if( strpos( $translated_loop, 'views/table') !== false ) {
			$settings['layout_meta_html'] = preg_replace_callback(
				'#<!-- wpv-loop-start -->.*?<!-- wpv-loop-end -->#ism',
				function( $matches ) use ( $translated_loop ) {
					return $this->translate_table( $matches[0], $translated_loop );
				},
				$settings['layout_meta_html']
			);
		}

		// Top content.
		$translated_loop = preg_replace(
			'#^.*<!-- /wp:toolset-views/view-template-block .*-->(.*)<!-- /wp:toolset-views/view-layout-block -->.*$#Us',
			'$1',
			$html
		);
		$settings['layout_meta_html'] = preg_replace(
			'#\[\/wpv-no-items-found\](.*)\[wpv-layout-end\]#Us',
			'[/wpv-no-items-found]' . do_blocks( $translated_loop ) . '[wpv-layout-end]',
			$settings['layout_meta_html']
		);

		// Bottom content.
		$translated_loop = preg_replace(
			'#^.*<!-- wp:toolset-views/view-layout-block .*-->(.*)<!-- wp:toolset-views/view-template-block.*$#Us',
			'$1',
			$html
		);
		$settings['layout_meta_html'] = preg_replace(
			'#\[wpv-layout-start\](.*)\[wpv-items-found\]#Us',
			'[wpv-layout-start]' . do_blocks( $translated_loop ) . '[wpv-items-found]',
			$settings['layout_meta_html']
		);
		return $settings;
	}

	/**
	 * Stores in `toolset_view_block_strings` if it is translated content
	 *
	 * @param array $data Actual toolset_view_block_strings data.
	 * @return array
	 */
	public function add_if_is_translated_content( $data ) {
		global $post;
		$default_language = apply_filters( 'wpml_default_language', null );
		$translated_id = apply_filters( 'wpml_object_id', $post->ID, $post->post_type, true, $default_language );
		$source_lang = toolset_getget( 'source_lang' );
		$lang = toolset_getget( 'lang' );
		$data['isTranslatedContent'] = $translated_id !== $post->ID || ( $source_lang && $lang && $source_lang !== $lang ) ? 1 : 0;
		return $data;
	}
}
