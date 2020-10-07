<?php

namespace OTGS\Toolset\Views\Controller\Shortcode\Resolver;

/**
 * Shotcode resolver controller: alternative syntax.
 *
 * @since 3.3.0
 */
class AlternativeSyntax implements IResolver {

	const SLUG = 'alternative_syntax';

	/**
	 * Apply resolver.
	 *
	 * @param string $content
	 * @return string
	 * @since 3.3.0
	 */
	public function apply_resolver( $content ) {
		if ( false === strpos( $content, '{!{' ) ) {
			return $content;
		}

		$content = apply_filters( 'toolset_transform_shortcode_format', $content );

		return $content;
	}

}
