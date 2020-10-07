<?php

namespace ToolsetCommonEs\Block\Style;

use ToolsetCommonEs\Block\Style\Block\Factory;
use ToolsetCommonEs\Library\WordPress\Actions as WPActions;
use ToolsetCommonEs\Library\WordPress\Blocks as WPBlocks;
use ToolsetCommonEs\Library\WordPress\Loop as WPLoop;

class LoaderByHead {

	/** @var Factory  */
	private $block_factory;

	/** @var IBlock[] */
	private $blocks = array();

	/** @var WPActions */
	private $wp_actions;

	/** @var WPLoop */
	private $wp_loop;

	/** @var WPBlocks */
	private $wp_blocks;

	/**
	 * Loader constructor.
	 *
	 * @param Factory $block_factory
	 * @param WPActions $wp_actions
	 * @param WPLoop $wp_loop
	 * @param WPBlocks $wp_blocks
	 */
	public function __construct( Factory $block_factory, WPActions $wp_actions, WPLoop $wp_loop, WPBlocks $wp_blocks ) {
		$this->block_factory = $block_factory;

		$this->wp_actions = $wp_actions;
		$this->wp_loop = $wp_loop;
		$this->wp_blocks = $wp_blocks;

        // Feeds should display the div containing the hidden styles encoded data
		if ( ! is_feed() ) {
			$this->wp_actions->add_action( 'wp_head', array( $this, 'blocks_style' ) );
		}
	}

	/**
	 * Blocks Style
	 */
	public function blocks_style() {
		$posts = $this->wp_loop->get_posts();

		foreach( $posts as $post ) {
			$this->load_blocks_by_post( $post );
		}

		$style = '';
		foreach( $this->blocks as $block ) {
			$style .= $block->get_css();
		}

		if( ! empty( $style ) ) {
			echo '<style>'.$style.'</style>';
		}
	}

	/**
	 * Returns the style of all blocks inside the post content.
	 *
	 * @param \WP_Post $post
	 */
	private function load_blocks_by_post( \WP_Post $post ) {
		if( ! $this->wp_blocks->has_blocks( $post->post_content ) ) {
			return;
		}

		$blocks = $this->wp_blocks->parse_blocks( $post->post_content );

		if( empty( $blocks ) ) {
			return;
		}

		foreach( $blocks as $block ) {
			try {
				$supported_block = $this->block_factory->get_block_by_array( (array) $block );

				if( $supported_block && ! array_key_exists( $supported_block->get_id(), $this->blocks ) ) {
					$this->blocks[ $supported_block->get_id() ] = $supported_block;
				}
			} catch( \Exception $e ) {
				// Something went wrong, which may end in an unexpected display on the frontend.
				die( $e->getMessage() );
			}
		}

	}

	/**
	 * Returns the style of a block.
	 *
	 * @param \WP_Block_Parser_Block $block The class typing is not part of the parameter list as the parse blocks
	 *                                        parser can be alter by a filter. So it's not really sure if we get
	 *                                        WP_Block_Parser_Block here. Check that params exist before use!
	 *
	 * @return string
	 */
	private function load_style_by_block( $block ) {
		$block_style = '';

		if(
			! is_object( $block ) ||
			! property_exists( $block, 'innerBlocks' ) ||
			! is_array( $block->innerBlocks )
		) {
			// no block this method can work with
			return $block_style;
		}

		foreach( $block->innerBlocks as $innerBlock ) {
			$block_style .= $this->load_style_by_block( $innerBlock );
		}
	}
}
