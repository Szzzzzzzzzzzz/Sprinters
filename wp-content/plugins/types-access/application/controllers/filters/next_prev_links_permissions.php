<?php

namespace OTGS\Toolset\Access\Controllers\Filters;

/**
 * Set Next and Previous front-end link permissions
 *
 * @package OTGS\Toolset\Access\Controllers\Filters
 * @since  2.8
 */
class NextPrevLinksPermissions {

	private static $instance;

	private $posts;


	/**
	 * @return NextPrevLinksPermissions
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	public static function initialize() {
		self::get_instance();
	}


	/**
	 * @param $where
	 * @param $in_same_term
	 * @param $excluded_terms
	 * @param $taxonomy
	 * @param $post
	 *
	 * @return string
	 */
	function set_next_prev_links_permissions( $where, $in_same_term, $excluded_terms, $taxonomy, $post ) {

		global $wpdb;

		if ( ! apply_filters( 'toolset_access_check_if_post_type_managed', true, $post->post_type )
			&& ! apply_filters( 'toolset_access_check_if_post_type_managed', true, 'post' ) ) {
			return $where;
		}
		$is_post_type_allowed = apply_filters( 'toolset_access_api_get_post_type_permissions', false, $post->post_type, 'read' );

		$where_groups = '';
		if ( ! $is_post_type_allowed ) {
			$post_groups = apply_filters( 'toolset_access_get_allowed_post_groups', array(), true );
			$where = ", {$wpdb->postmeta} as postmeta " . $where;
			for ( $i = 0; $i < count( $post_groups ); $i ++ ) {
				$where_groups .= " postmeta.meta_value = '{$post_groups[ $i ]}' OR ";
			}
			if ( ! empty( $where_groups ) ) {
				$where_groups = substr( $where_groups, 0, - 3 );
				$where .= " AND postmeta.meta_key = '_wpcf_access_group' AND ( {$where_groups} ) " .
					" AND p.ID = postmeta.post_id";
			}
		} else {
			$post_groups = apply_filters( 'toolset_access_get_allowed_post_groups', array(), false );

			for ( $i = 0; $i < count( $post_groups ); $i ++ ) {
				$where_groups .= "'{$post_groups[ $i ]}', ";
			}
			if ( ! empty( $where_groups ) ) {
				$where_groups = substr( $where_groups, 0, - 2 );
				$where = " LEFT JOIN $wpdb->postmeta postmeta ON p.ID = postmeta.post_id " .
					"AND postmeta.meta_key = '_wpcf_access_group' " . $where;
				$where .= " AND ( postmeta.meta_value IS NULL OR ( postmeta.meta_value IS NOT NULL AND postmeta.meta_value "
					.
					"NOT IN ({$where_groups}) ) ) ";

			}
		}

		return $where;
	}
}