<?php

namespace ToolsetBlocks;

/*
 * ON INIT
 */
add_action( 'init', function() {
	$dic = DicLoader::get_instance()->get_dic();

	// Frontend
	if( ! is_admin() ) {
		// Public Dependencies
		$frontend = $dic->make( '\ToolsetBlocks\PublicDependencies\Frontend' );
		// - Lightbox
		$frontend->add_content_based_dependency( $dic->make( '\ToolsetBlocks\PublicDependencies\Dependency\Lightbox' ) );
		// - Glide.js
		$frontend->add_content_based_dependency( $dic->make( '\ToolsetBlocks\PublicDependencies\Dependency\Glide' ) );
		// - Dashicons
		$frontend->add_content_based_dependency( $dic->make( '\ToolsetBlocks\PublicDependencies\Dependency\Dashicons' ) );
		// - ExternalResources
		$frontend->add_content_based_dependency( $dic->make( '\ToolsetBlocks\PublicDependencies\Dependency\ExternalResources' ) );
		// - Blocks frontend JS
		$frontend->add_content_based_dependency( $dic->make( '\ToolsetBlocks\PublicDependencies\Dependency\Javascript' ) );

		// - Load Dependecies
		$frontend->load();

		// Gallery shortcodes
		$gallery_shortcode_factory = $dic->make( 'ToolsetBlocks\Block\Gallery\Shortcode\Factory' );
		$gallery_shortcode_factory->initialize();
	} else {
		// Backend - Public Dependencies
		$backend_public_dependencies = $dic->make( '\ToolsetBlocks\PublicDependencies\Backend' );

		// - CodeMirror
		$backend_public_dependencies->add_dependency( $dic->make( '\ToolsetBlocks\PublicDependencies\Dependency\CodeMirror' ) );
		// - Glide
		$backend_public_dependencies->add_dependency( $dic->make( '\ToolsetBlocks\PublicDependencies\Dependency\Glide' ) );

		// - Load Dependencies
		$backend_public_dependencies->load_dependencies();
	}

	// Common ES Blocks Styles - Add Block Factory for blocks of "Toolset Blocks".
	add_filter( 'toolset_common_es_block_factories', function( $block_factories ) use ( $dic ) {
		if( $block_factory = $dic->make( '\ToolsetBlocks\Block\Style\Block\Factory' ) ) {
			$block_factories[] = $block_factory;
		}
		return $block_factories;
	}, 10, 1 );

	// Toolset Blocks.
	$tb = $dic->make( '\ToolsetBlocks\Block\PublicLoader' );
	$tb->initialize();

	// i18n.
	$tb = $dic->make( '\ToolsetBlocks\Block\I18n' );
	$tb->initialize();

	// Sticky Links.
	$tb = $dic->make( '\ToolsetBlocks\Block\StickyLinks' );
	$tb->initialize();

	// Plugins
	$manager = $dic->make( '\ToolsetBlocks\Plugin\Manager' );
	// - Editor Max Width
	$manager->register_plugin( $dic->make( '\ToolsetBlocks\Plugin\EditorMaxWidth\EditorMaxWidth' ) );
	// - WPML integration
	$manager->register_plugin( $dic->make( '\ToolsetBlocks\Plugin\WPML\WPML' ) );

	// - Load Plugins
	$manager->load_plugins();

	// Image Crop by shortcodes (currently only DS shortcode).
	$image_crop = $dic->make( '\ToolsetBlocks\Block\Image\CustomSize' );
	// - add TB Dynamic as supported shortcode.
	$image_crop->add_shortcode( $dic->make( '\ToolsetBlocks\Block\Image\Shortcode\TBDynamic' ) );
	// - apply crop functionality to do_shortcode_tag.
	add_filter( 'do_shortcode_tag', array( $image_crop, 'resize_by_shortcodes' ), 100, 3 );

	// Image Content Placeholders.
	$image_content_placeholders = $dic->make( '\ToolsetBlocks\Block\Image\Content\Placeholders' );
}, 1 );

add_action( 'rest_api_init', function() {
	if( is_admin() || ( defined( 'REST_REQUEST') && REST_REQUEST ) ) {
		$dic = DicLoader::get_instance()->get_dic();

		// Rest API
		$rest_api = $dic->make( '\ToolsetCommonEs\Rest\API' );

		// Add Media Object (required for Image/Video block).
		$rest_api->add_route( $dic->make( '\ToolsetBlocks\Rest\Route\MediaObject' ) );
	}
}, 1 );


