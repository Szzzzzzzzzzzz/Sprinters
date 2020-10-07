<?php
namespace ToolsetBlocks\Plugin;

/**
 * Wrapper for a mockable access to constants.
 *
 * Motivation: http://www.theaveragedev.com/mocking-constants-in-tests/
 *
 * Note: Use this *only* if you need it in unit tests!
 *
 * @see Toolset_Constants
 *
 * @since 1.2.0
 */
class Constants {
	/**
	 * Defines a constant
	 *
	 * @param string $key Key.
	 * @param string $value Value.
	 */
	public function define( $key, $value ) {
		if ( defined( $key ) ) {
			throw new RuntimeException( "Constant $key is already defined." );
		}

		define( $key, $value );
	}

	/**
	 * Checks if a constant is defined
	 *
	 * @param string $key Key.
	 * @return bool
	 */
	public function defined( $key ) {
		return defined( $key );
	}

	/**
	 * Return a constant value
	 *
	 * @param string $key Key.
	 * @return bool
	 */
	public function constant( $key ) {
		return constant( $key );
	}

}
