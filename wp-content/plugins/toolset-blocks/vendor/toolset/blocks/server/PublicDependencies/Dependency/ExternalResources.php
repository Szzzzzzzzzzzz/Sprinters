<?php

namespace ToolsetBlocks\PublicDependencies\Dependency;

/**
 * External resources dependency
 *
 * Loads external css or js depending on the content
 *
 * @since 1.0.0
 * @todo Use it in a general way, get the file extension and load a style or a script
 */
class ExternalResources implements IContent {

	/**
	 * External URL to be loaded
	 *
	 * @param string
	 * @since 1.0.0
	 */
	private $url = '';

	/**
	 * Returns true/false if the current dependency is required for the content
	 *
	 * @param string $content Content of the current post
	 *
	 * @return bool
	 */
	public function is_required_for_content( $content ) {
		preg_match_all( '/"cssUrl":"([^"]+)"/', $content, $m );
		if( isset( $m[1] ) ) {
			$this->url = $m[1];
		}
		preg_match_all( '/"starType":"([^"]+)"/', $content, $m );
		if( isset( $m[1] ) ) {
			preg_match_all( '/"customFontURL":"([^"]+)"/', $content, $m );
			if ( ! $this->url ) {
				$this->url = [];
			} else if ( ! is_array( $this->url ) ) {
				$this->url = [ $this->url ];
			}
			// WPML add slashes in the URLs.
			$this->url = array_merge(
				array_map(
					function( $url ) {
						return stripslashes( $url );
					},
					$m[1]
				),
				$this->url
			);
		}
		if ( is_array( $this->url ) ) {
			$this->url = array_unique( $this->url );
		}

		return $this->url;
	}

	/**
	 * Function to load the dependencies
	 */
	public function load_dependencies() {
		if ( ! is_array( $this->url ) ) {
			$this->url = [ $this->url ];
		}
		foreach ( $this->url as $url ) {
			$slug = 'toolset-blocks-' . preg_replace( '/.*\/([^\/]+)$/', '$1', $url );
			wp_enqueue_style( $slug, $url );
		}
	}
}
