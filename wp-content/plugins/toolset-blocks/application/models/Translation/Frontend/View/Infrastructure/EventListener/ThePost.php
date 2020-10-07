<?php

namespace OTGS\Toolset\Views\Models\Translation\Frontend\View\Infrastructure\EventListener;

use OTGS\Toolset\Views\Models\Translation\Frontend\View\Application\RestoreTranslatedViewComponents;
use OTGS\Toolset\Views\Models\Translation\Frontend\View\Infrastructure\EventListener\Helper\PostActions;
use ToolsetCommonEs\Library\WordPress\Actions;

/**
 * Class ThePost
 *
 * Hook to the post filter to trigger any translation apply for View Blocks inside the post.
 *
 * @package OTGS\Toolset\Views\Models\Translation\Frontend\View\Infrastructure\EventListener
 *
 * @since TB 1.13
 */
class ThePost {
	/** @var WpvViewSettings */
	private $wpv_view_settings_event;

	/** @var WpvPostContent */
	private $wpv_post_content;

	/** @var Actions */
	private $wp_actions;

	/** @var PostActions */
	private $post_actions;

	/**
	 * ThePost constructor.
	 *
	 * @param WpvViewSettings $restore_filter_translation
	 * @param WpvPostContent $restore_content_translation
	 * @param Actions $wp_actions
	 * @param PostActions $post_actions
	 */
	public function __construct(
		WpvViewSettings $restore_filter_translation,
		WpvPostContent $restore_content_translation,
		Actions $wp_actions,
		PostActions $post_actions
	) {
		$this->wpv_view_settings_event = $restore_filter_translation;
		$this->wpv_post_content = $restore_content_translation;
		$this->wp_actions = $wp_actions;
		$this->post_actions = $post_actions;
	}

	/**
	 * Listen to 'the_post' only when WPML is active and the current language differs from the default language.
	 *
	 * @param bool $is_frontend_call
	 * @param bool $is_doing_ajax
	 */
	public function start_listen( $is_frontend_call = false, $is_doing_ajax = false ) {
		$current_language = $this->wp_actions->apply_filters( 'wpml_current_language', false );
		if( $current_language === false ) {
			return;
		}

		$default_language = $this->wp_actions->apply_filters( 'wpml_default_language', false );

		if( $current_language !== $default_language ) {
			if( $is_doing_ajax ) {
				// For view ajax refreshs (view search inputs) we need to use this for fetching the post:
				$this->wp_actions->add_filter( 'wpv_action_wpv_set_top_current_post', array( $this, 'on_event' ), 10, 1 );
			}

			if( $is_frontend_call ) {
				$this->wp_actions->add_filter( 'the_post', array( $this, 'on_event' ), 10, 1 );
			}
		}
	}

	public function on_event( \WP_Post $post ) {
		try {
			// Check for Content Template
			$content_template = $this->post_actions->has_wpv_content_template( $post->ID );
			if( $content_template > 0 ) {
				$ct = $this->post_actions->get_post( $content_template );

				if( ! $ct ) {
					return $post;
				}

				$ct_translated_id = $this->wp_actions->apply_filters( 'wpml_object_id', $ct->ID, $ct->post_type );

 				if( ! $ct_translated_id ) {
					return $post;
				}

				$ct_translated = $this->post_actions->get_post( $ct_translated_id );

				if( ! $ct_translated ) {
					return $post;
				}

				$post = $ct_translated;
			}

			// This shouldn't be here from the code concept perspective, but as the "the_post" filter is very general
			// we want to check as early as possible if the post contains a view.
			if( $post && strpos( $post->post_content, "toolset-views/view-editor" ) === false ) {
				// No view post.
				return $post;
			}

			$settings = unserialize( serialize( $this->wpv_view_settings_event ) );
			$settings->set_post_translated( $post );
			$settings->start_listen();

			$content = unserialize( serialize( $this->wpv_post_content ) );
			$content->set_post_translated( $post );
			$content->start_listen();

			return $post;
		} catch ( \InvalidArgumentException $exception ) {
			// Not a Views block.
			return $post;
		} catch ( \Exception $exception ) {
			// Unexpected.
			if( defined( 'WPV_TRANSLATION_DEBUG' ) && WPV_TRANSLATION_DEBUG ) {
				// @codeCoverageIgnoreStart
				trigger_error(  'Problem with Views translation: ' . $exception->getMessage(), E_USER_WARNING );
				// @codeCoverageIgnoreEnd
			}
			return $post;
		}
	}
}
