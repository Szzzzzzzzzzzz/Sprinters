<?php

namespace OTGS\Toolset\Access\Controllers;

use OTGS\Toolset\Access\Models\Capabilities;
use OTGS\Toolset\Access\Models\Settings;
use OTGS\Toolset\Access\Models\UserRoles;

/**
 * Set moderate comments capability
 *
 * @package OTGS\Toolset\Access\Controllers
 * @since 2.7
 */
class CommentsPermissions {

	private static $instance;


	/**
	 * @return PermissionsTaxonomies
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
	 * @param $allcaps array
	 * @param $caps array
	 * @param $args array
	 * @param $user object
	 *
	 * @return array|mixed
	 */
	public function set_comments_capabilities( $allcaps, $caps, $args, $user ) {
		if ( current_user_can( 'edit_posts' ) ) {
			$allcaps['moderate_comments'] = true;
		} else {
			$allcaps['moderate_comments'] = false;
		}

		return $allcaps;
	}


	/**
	 * Get $post object by comment id
	 *
	 * @param $comment_id int
	 */
	public function get_comment_post( $comment_id ) {

		$comment = get_comment( $comment_id );
		if ( ! $comment ) {
			return;
		}

		$post = get_post( $comment->comment_post_ID );

		return $post;
	}


	/**
	 * A filter to manage comment actions on wp-admin/edit-comments.php
	 *
	 * @param $actions array
	 * @param $comment object
	 *
	 * @return array
	 */
	public function test_filter( $actions, $comment ) {
		return $actions;
	}

}