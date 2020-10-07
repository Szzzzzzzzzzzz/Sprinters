<?php

namespace OTGS\Toolset\Views\Controller\Compatibility;

use OTGS\Toolset\Views\Services\Bootstrap;

/**
 * Handles the compatibility between Views and Ultimate Addons Gutenberg.
 *
 * @since 3.2
 */
class UltimateAddonsGutenbergCompatibility extends Base {
	/** @var \Toolset_Constants */
	private $constants;

	/** @var callable */
	private $uagb_helper_get_instance;

	/** @var callable */
	private $wpv_view_get_instance;

	/** @var string */
	public $uag_styles = null;

	public function __construct(
		callable $uagb_helper_get_instance,
		\Toolset_Constants $constants,
		callable $wpv_view_get_instance
	) {

		$this->uagb_helper_get_instance = $uagb_helper_get_instance;
		$this->constants = $constants;
		$this->wpv_view_get_instance = $wpv_view_get_instance;
	}

	/**
	 * Initializes the UAG integration.
	 */
	public function initialize() {
		$this->init_hooks();
	}

	/**
	 * Initializes the hooks for the UAG integration.
	 */
	private function init_hooks() {
		// Covers the case where UAG blocks are used in a WordPress Archive built with blocks.
		add_action( 'wp', array( $this, 'maybe_generate_stylesheet_for_wpa' ), PHP_INT_MAX - 1 );

		// Together the filters below cover the case where UAG blocks are used inside a View, in order to properly render the
		// View preview.
		add_filter( 'wpv_view_pre_do_blocks_view_layout_meta_html', array( $this, 'maybe_extract_uag_blocks_styles' ) );
		add_filter( 'wpv-post-do-shortcode', array( $this, 'maybe_append_uag_blocks_styles' ), 10, 2 );

		// Covers the case where a post that uses a Content Template with UAG blocks but no UAG blocks in the post's content.
		add_filter( 'uagb_post_for_stylesheet', array( $this, 'maybe_get_ct_post' ) );

		// Covers the case where UAG blocks are used in a WordPress Archive built with blocks and the first item in the loop
		// doesn't use a Content Template with UAG blocks in its content.
		add_filter( 'uagb_post_for_stylesheet', array( $this, 'maybe_get_blocks_wpa_helper_post' ) );

		// Covers the case where a legacy View that uses a Content Template designed with the block editor and with UAG block is inserted in a post
		// using the View block.
		add_filter( 'uagb_post_for_stylesheet', array( $this, 'maybe_scan_for_legacy_view_block_with_content_template_with_uag_blocks' ) );

		// Covers the case where UAG blocks are used in some post content fetched with the "Post Content" Dynamic Source.
		// This needs to specifically happen before priority '9' because otherwise it won't work properly.
		add_filter( 'the_content', array( $this, 'maybe_get_styles_for_post_content_source' ), 8 );
	}
	/**
	 * It calculates the UAG Blocks style for the content of the "Post Content" dynamic source.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function maybe_get_styles_for_post_content_source( $content ) {
		global $post;

		if (
			$post->dynamic_sources_content_processed ||
			$post->view_template_override
		) {
			$uag_styles = $this->get_uag_blocks_styles( $content );

			if ( ! empty( $uag_styles ) ) {
				$content = '<style>' . $uag_styles . '</style>' . $content;
			}
		}

		return $content;
	}

	/**
	 * Appends the styles for the UAG blocks that exist inside the template of a View/WordPress Archive for the editor preview.
	 *
	 * The styles are injected this way because we do this in the exact same way for our own blocks.
	 *
	 * @param string $content
	 * @param bool   $doing_excerpt
	 *
	 * @return string
	 */
	public function maybe_append_uag_blocks_styles( $content, $doing_excerpt ) {
		if (
			! $this->constants->defined( 'REST_REQUEST' ) ||
			! $this->constants->constant( 'REST_REQUEST' )
		) {
			return $content;
		}

		if (
			$doing_excerpt ||
			empty( $this->uag_styles )
		) {
			return $content;
		}

		$content = '<style>' . $this->uag_styles . '</style>' . $content;

		$this->uag_styles = null;

		return $content;
	}

	/**
	 * Gets the styles for UAG Blocks inside the given content.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	private function get_uag_blocks_styles( $content ) {
		if ( ! has_blocks( $content ) ) {
			return '';
		}

		$blocks = parse_blocks( $content );

		if (
			! is_array( $blocks ) ||
			empty( $blocks )
		) {
			return '';
		}

		$uagb_helper_class_instance = call_user_func( $this->uagb_helper_get_instance );
		$uag_styles = $uagb_helper_class_instance->get_assets( $blocks );

		return $uag_styles['css'];
	}

	/**
	 * Extracts the UAG blocks styles from the View Layout Meta HTML in order to later inject them properly so that they
	 * show up properly inside a View or a WordPress Archive.
	 *
	 * @param  string $content
	 *
	 * @return string
	 */
	public function maybe_extract_uag_blocks_styles( $content ) {
		if (
			! $this->constants->defined( 'REST_REQUEST' ) ||
			! $this->constants->constant( 'REST_REQUEST' )
		) {
			return $content;
		}

		$uag_styles = $this->get_uag_blocks_styles( $content );

		if ( ! empty( $uag_styles ) ) {
			$this->uag_styles = $uag_styles;
		}

		return $content;
	}

	/**
	 * Generates the stylesheet for UAG blocks when they lie inside a WordPress Archive.
	 */
	public function maybe_generate_stylesheet_for_wpa() {
		// Check if the WordPress Archive is built with blocks.
		$wpa_helper_post = $this->maybe_get_blocks_wpa_helper_post( null );

		if ( ! $wpa_helper_post ) {
			return;
		}

		/** @var \UAGB_Helper $uagb_helper_class_instance */
		$uagb_helper_class_instance = call_user_func( $this->uagb_helper_get_instance );
		$uagb_helper_class_instance->get_generated_stylesheet( $wpa_helper_post );
	}

	/**
	 * Checks if the $post uses a Content Template, in which case it returns the Content Template post in order to generate
	 * the stylesheet for it.
	 *
	 * @param \WP_Post $post
	 *
	 * @return \WP_Post
	 */
	public function maybe_get_ct_post( $post ) {
		if ( ! $post || ! isset( $post->ID ) ) {
			return $post;
		}

		$maybe_ct_selected = get_post_meta( $post->ID, '_views_template', true );

		if ( 0 !== (int) $maybe_ct_selected ) {
			$post = get_post( $maybe_ct_selected );
		}

		return $post;
	}

	/**
	 * Checks if the the user currently views a WordPress Archive built with blocks, in which case it returns the WPA Helper
	 * post in order to generate the stylesheet for it.
	 *
	 * @param null||\WP_Post $post
	 *
	 * @return array|\WP_Post|null
	 */
	public function maybe_get_blocks_wpa_helper_post( $post ) {
		if (
			! (
				is_archive() ||
				is_home() ||
				is_search()
			)
		) {
			// Do nothing if it's not a WordPress Archive.
			return $post;
		}

		// Get the ID of the WordPress Archive in use.
		$wpa_id = apply_filters( 'wpv_filter_wpv_get_current_archive', null );
		if ( ! $wpa_id ) {
			return $post;
		}

		// Check if the WordPress Archive is built with blocks.
		return get_post( apply_filters( 'wpv_filter_wpv_get_wpa_helper_post', $wpa_id ) ) ?: $post;
	}

	/**
	 * Scans the post content for a View block showing a legacy View using a Content Template designed with the block editor
	 * using UAG blocks.
	 *
	 * @param $post
	 *
	 * @return array|\WP_Post|null
	 */
	public function maybe_scan_for_legacy_view_block_with_content_template_with_uag_blocks( $post ) {
		if (
			! $post ||
			! isset( $post->post_content )
		) {
			return $post;
		}

		$post_blocks = parse_blocks( $post->post_content );

		foreach ( $post_blocks as $block ) {
			if ( Bootstrap::MODERN_BLOCK_NAME !== $block['blockName'] ) {
				continue;
			}

			$view = call_user_func( $this->wpv_view_get_instance, toolset_getnest( $block, array( 'attrs', 'viewId' ), 0 ) );

			if (
				$view &&
				$view->has_loop_template &&
				$view->loop_template_id
			) {
				$loop_template = get_post( $view->loop_template_id );
				if ( $this->has_uag_blocks( $loop_template->post_content ) ) {
					return $loop_template;
				}
			}
		}

		return $post;
	}

	/**
	 * Scans the specified content for UAG blocks.
	 *
	 * @param string $content
	 *
	 * @return bool
	 */
	private function has_uag_blocks( $content ) {
		$has_uag_blocks = false;
		$blocks = parse_blocks( $content );
		foreach ( $blocks as $block ) {
			if ( strpos( $block['blockName'], 'uagb/' ) !== false ) {
				$has_uag_blocks = true;
				break;
			}
		}
		return $has_uag_blocks;
	}
}
