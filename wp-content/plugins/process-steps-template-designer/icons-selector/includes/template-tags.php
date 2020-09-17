<?php  
/**
 * Template tags for the Font Awesome Field
 * 
 * @package WordPress
 **/

if( ! function_exists( 'get_fa' ) ) {

	/**
	 * Retrieve the icon
	 *
	 * @param bool $format Format the output
	 * @param integer $post_id The post ID
	 * @return string The icon, either formatted as HTML, or just the name
	 * @author 
	 **/
	function get_fa( $format = false, $post_id = null ) {
		if ( ! $post_id ) {
			global $post;
			if ( ! is_object( $post ) ) {
				return;
			}
			$post_id = $post->ID;
		}
		$icon = get_post_meta( $post_id, 'fa_field_icon', true );
		if ( ! $icon ) {
			return;
		}
		if ( $format ) {
			$output = '<i class="fa ' . $icon . '"></i>';
		} else {
			$output = $icon;
		}
		return $output;
	}

}

if( ! function_exists( 'the_fa' ) ) {

	/**
	 * Print the icon
	 *
	 * @param bool $format Format the output
	 * @param integer $post_id The post ID
	 * @return void Echoes the icon, either formatted as HTML, or just the name
	 * @author 
	 **/
	function the_fa( $format = false, $post_id = null ) {
		echo get_fa( $format, $post_id );
	}

}
?>