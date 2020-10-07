<?php

namespace ToolsetBlocks\Block\Gallery\Shortcode;

/**
 * Handles [tb-alttext] shortcode
 *
 * Shortcode: tb-alttext
 * Description: change the alt text, from an url to the real alt
 *
 * Example:
 * [tb-alttext][types field='r-image' size='full' alt='[types field="r-image" output="raw"][/types]' output="normal'][/types][/tb-alttext]
 * Why? because it was a mess of quotes, so it is the best solution
 *
 * @since 1.2
 */
class AltText {
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
		add_shortcode( 'tb-alttext', array( $this, 'alttext_shortcode_render' ) );
	}

	/**
	 * Renders the shortcode
	 */
	public function alttext_shortcode_render( $attributes, $content = '' ) {
		$image = do_shortcode($content);
		preg_match( '#alt="([^"]+)"#', $image, $url );
		if ( ! isset( $url[1] ) ) {
			return '';
		}
		global $wpdb;
		$post = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s ORDER BY `ID` DESC", $url[1] ) );
		$alt = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
		return preg_replace( '#alt="([^"]+)"#', 'alt="' . $alt . '"', $image );
	}
}
