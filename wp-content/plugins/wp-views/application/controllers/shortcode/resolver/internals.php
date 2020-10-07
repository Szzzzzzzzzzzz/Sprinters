<?php

namespace OTGS\Toolset\Views\Controller\Shortcode\Resolver;

/**
 * Shotcode resolver controller: internal shortcodes.
 *
 * @since 3.3.0
 */
class Internals extends HtmlAttributes implements IResolver {

	const SLUG = 'internals';

	/**
	 * Apply resolver.
	 *
	 * @param string $content
	 * @return string
	 * @since 3.3.0
	 */
	public function apply_resolver( $content ) {
		$content = $this->resolve_shortcodes( $content );

		return $content;
	}

	/**
	 * Resolve internal shortcodes.
	 *
	 * @param string $content
	 * @return string
	 * @since 3.3.0
	 */
	private function resolve_shortcodes( $content ) {
		// Search for outer shortcodes, to process their inner expressions.
		$matches = array();
		$counts = $this->find_outer_brackets( $content, $matches );

		// Iterate 0-level shortcode elements and resolve their internals, one by one.
		if ( $counts > 0 ) {
			$inner_expressions = $this->get_inner_expressions();

			foreach ( $matches as $match ) {
				foreach ( $inner_expressions as $inner_expression ) {
					$inner_counts = preg_match_all( $inner_expression, $match, $inner_matches );
					// Replace all 1-level inner shortcode matches.
					if ( $inner_counts > 0 ) {
						foreach ( $inner_matches[0] as &$inner_match ) {
							// Execute shortcode content and replace.

							// -----------------------------------
							// Not sure why we run here the HtmlAttributes resolver,
							// since we are matching just a shortcode,
							// and it should not include any HTML string, but maybe it does?
							// Let's keep this for the sake of backwards compatibility only!
							$resolved_match = parent::apply_resolver( $inner_match );
							// -----------------------------------

							$filter_state = new \WPV_WP_filter_state( 'the_content' );
							// Not sure whether we need to run again do_shortcode as it is run in wpv_preprocess_shortcodes_in_html_elements already?
							$resolved_match = do_shortcode( $resolved_match );
							// Escape quote characters as they should be wrapping those shortcodes too.
							$resolved_match = str_replace( '"', '&quot;', $resolved_match );
							$resolved_match = str_replace( "'", '&#039;', $resolved_match );
							$filter_state->restore();
							$content = str_replace( $inner_match, $resolved_match, $content );
							$match = str_replace( $inner_match, $resolved_match, $match );
						}
					}
				}
			}
		}

		return $content;
	}

	/**
	 * Get a list of regex compatible expressions to catch.
	 *
	 * @return array
	 * @since 3.3.0
	 */
	private function get_inner_expressions() {
		$inner_expressions = array();

		// It is extremely important that Types shortcodes are registered before Views inner shortcodes.
		// Otherwise, Types shortcodes wil not be parsed properly.
		$inner_expressions[] = '/\\[types.*?\\].*?\\[\\/types\\]/i';

		$views_shortcodes_regex = $this->get_inner_shortcodes_regex();
		$inner_expressions[] = '/\\[(' . $views_shortcodes_regex . ').*?\\]/i';

		$custom_inner_shortcodes = $this->get_custom_inner_shortcodes();
		if ( count( $custom_inner_shortcodes ) > 0 ) {
			foreach ( $custom_inner_shortcodes as $custom_inner_shortcode ) {
				$inner_expressions[] = '/\\[' . $custom_inner_shortcode . '.*?\\].*?\\[\\/" . $custom_inner_shortcode . "\\]/i';
			}
			$inner_expressions[] = '/\\[(' . implode( '|', $custom_inner_shortcodes ) . ').*?\\]/i';
		}

		return $inner_expressions;
	}

	/**
	 * Find top-level shortcodes.
	 *
	 * @param string $content The content to check.
	 * @param array $matches List of top level shortcodes: full shortcode.
	 * @return int Number of top level shortcodes found.
	 * @since 3.3.0
	 */
	private function find_outer_brackets( $content, &$matches ) {
		$count = 0;

		$first = strpos( $content, '[' );
		if ( false !== $first ) {
			$length = strlen( $content );
			$brace_count = 0;
			$brace_start = -1;
			for ( $i = $first; $i < $length; $i++ ) {
				if ( '[' === $content[ $i ] ) {
					if ( 0 === $brace_count ) {
						$brace_start = $i + 1;
					}
					$brace_count++;
				}
				if ( ']' === $content[ $i ] ) {
					if ( $brace_count > 0 ) {
						$brace_count--;
						if ( 0 === $brace_count ) {
							$matches[] = substr( $content, $brace_start, $i - $brace_start );
							$count++;
						}
					}
				}
			}
		}

		return $count;
	}

}
