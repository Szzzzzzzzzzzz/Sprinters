<?php

namespace ToolsetBlocks\Block\Gallery\Shortcode;

/**
 * Handles [tb-caption] shortcode
 *
 * Shortcode: tb-caption
 * Description: gets the caption from an url
 * Attributes:
 *   - url: URL of the image
 *
 * @link https://toolset.com/forums/topic/displaying-caption-from-media-post/
 * @since 1.2
 */
class Caption {
	/**
	 * Initializes the class
	 */
	public function initialize() {
		$this->add_shortcode();
	}

	/**
	 * Adds the shortcode
	 */
	private function add_shortcode() {
		add_shortcode( 'tb-caption', array( $this, 'caption_shortcode_render' ) );
	}

	/**
	 * Renders the shortcode
	 */
	public function caption_shortcode_render( $attributes ) {
		if ( ! isset( $attributes[ 'url' ] ) ) {
			return '';
		}
		global $wpdb;
		$url = $attributes['url'];
		$post = $wpdb->get_row( $wpdb->prepare( "SELECT ID, post_excerpt FROM $wpdb->posts WHERE guid=%s ORDER BY `ID` DESC", $url ) );
		if ( $post->post_excerpt ) {
			return $post->post_excerpt;
		}

		$attachment_meta = get_post_meta( $post->ID, '_wp_attachment_metadata', true );
		if ( isset( $attachment_meta['image_meta'] ) ) {
			return $attachment_meta['image_meta']['caption'];
		}
		return '';
	}
}
