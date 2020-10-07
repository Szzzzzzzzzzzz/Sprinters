<?php

namespace ToolsetBlocks\Block\Image\Content;

use OTGS\Toolset\Common\Utils\Attachments;
use ToolsetCommonEs\Library\WordPress\Actions;
use ToolsetCommonEs\Library\WordPress\Image;

/**
 * @package ToolsetBlocks\Block\Image\Shortcode
 */
class Placeholders {
	const PLACEHOLDER_ALT_TEXT = '%%tb-image-alt-text%%';
	const PLACEHOLDER_ID = '%%tb-image-id%%';
	const PLACEHOLDER_URL = '%%tb-image-url%%';
	const PLACEHOLDER_FILENAME = '%%tb-image-filename%%';
	const PLACEHOLDER_ATTACHMENT_URL = '%%tb-image-attachment-url%%';
	const PLACEHOLDER_WP_IMAGE_CLASS = '%%tb-image-wp-image-class%%';

	/** @var Image */
	private $wp_image;

	/** @var Attachments */
	private $common_attachments;

	/**
	 * AltText constructor.
	 *
	 * @param Actions $wp_actions
	 * @param Image $wp_image
	 * @param Attachments $common_attachments
	 */
	public function __construct( Actions $wp_actions, Image $wp_image, Attachments $common_attachments ) {
		$this->common_attachments = $common_attachments;
		$this->wp_image = $wp_image;

		// Register callback to replace placeholders on the_content filter.
		$wp_actions->add_filter( 'the_content', array( $this, 'replace_placeholders' ), PHP_INT_MAX - 1, 1 );
		// Views / WPA content
		$wp_actions->add_filter( 'wpv_filter_wpv_view_shortcode_output', array( $this, 'replace_placeholders' ), 10, 2 );
		$wp_actions->add_filter( 'toolset_the_content_wpa', array( $this, 'replace_placeholders' ), PHP_INT_MAX - 1, 1 );
	}

	/**
	 * This replaces the defined placeholders inside an image tag.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function replace_placeholders( $content ) {
		if ( defined( 'WPV_BLOCK_UPDATE_ITEM' ) && WPV_BLOCK_UPDATE_ITEM ) {
			// Abort applying on backend.
			return $content;
		}

		$replaced_content = preg_replace_callback(
			'/\<figure.*?class=".*?tb-image.*"?src="(.*?)".*?<\/figure\>/',
			function ( $matches ) {
				$img_html = $matches[0];

				if( count( $matches ) === 1 || empty( $matches[1] ) ) {
					// No image src. Remove the complete figure.
					return '';
				}

				if ( strpos( $img_html, '%%' ) === false ) {
					// No placeholders.
					return $img_html;
				}

				$src = preg_replace( '/(\-[0-9]{1,10}x[0-9]{1,10})([^\s]*)/', '$2', $matches[1] );

				// Replace image url.
				$img_html = str_replace( self::PLACEHOLDER_URL, $src, $img_html );

				// Replace filename.
				$img_html = str_replace( self::PLACEHOLDER_FILENAME, basename( $src ), $img_html );

				if ( strpos( $img_html, '%%' ) === false || count( $matches ) === 1 || empty( $matches[1] ) ) {
					// No further "heavy" placeholders, which require to call the database.
					return $img_html;
				}

				$alt_text = '';
				$id = $this->common_attachments->get_attachment_id_by_url( $src );
				$wp_image_class = '';
				$attachment_url = '';

				if ( $id ) {
					$alt_text = $this->wp_image->get_alt_text( $id );
					$wp_image_class = 'wp-image-' . $id;
					$attachment_url = wp_get_attachment_url( $id );
				}

				// Replace alt text placeholder with actual alt text.
				$img_html = str_replace( self::PLACEHOLDER_ALT_TEXT, $alt_text, $img_html );

				// Replace id placeholder with actual id.
				$img_html = str_replace( self::PLACEHOLDER_ID, $id ? $id : '', $img_html );

				// Replace wp image class.
				$img_html = str_replace( self::PLACEHOLDER_WP_IMAGE_CLASS, $wp_image_class, $img_html );

				// Replace image url.
				$img_html = str_replace( self::PLACEHOLDER_ATTACHMENT_URL, $attachment_url, $img_html );

				return $img_html;
			},
			$content
		);

		// In case preg_replace_callback failed and returned null, just return unchanged content. (Fixes the problem
		// with WP Gallery block having lots of images.)
		if ( null === $replaced_content ) {
			return $content;
		}

		return $replaced_content;
	}
}
