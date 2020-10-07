<?php

namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition;

use InvalidArgumentException;
use OTGS\Toolset\Common\PostStatus;
use OTGS\Toolset\Common\Relationships\API\ElementStatusCondition;
use OTGS\Toolset\Common\Relationships\API\RelationshipRole;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager;

/**
 * Condition to query associations by a status of an element in a particular role.
 *
 * Allows querying for a specific status or for a set of statuses that may be
 * depending on other circumstances (e.g. capabilities of the current user).
 *
 * Note that the functionality may be different per each domain. Currently, only posts
 * are supported.
 *
 * @since 4.0
 */
class ElementStatus extends AbstractCondition {

	/** @var string|string[] */
	private $statuses;


	/** @var RelationshipRole */
	private $for_role;


	/** @var TableJoinManager */
	private $join_manager;


	/** @var PostStatus */
	private $post_status;


	/**
	 * @param string|string[] $statuses One or more status values.
	 * @param RelationshipRole $for_role
	 * @param TableJoinManager $join_manager
	 * @param PostStatus $post_status
	 */
	public function __construct(
		$statuses,
		RelationshipRole $for_role,
		TableJoinManager $join_manager,
		PostStatus $post_status
	) {
		if ( ( ! is_string( $statuses ) && ! is_array( $statuses ) ) || empty( $statuses ) ) {
			throw new InvalidArgumentException( 'Invalid statuses provided' );
		}

		$this->statuses = $statuses;
		$this->for_role = $for_role;
		$this->join_manager = $join_manager;
		$this->post_status = $post_status;
	}


	/**
	 * Get a part of the WHERE clause that applies the condition.
	 *
	 * @return string Valid part of a MySQL query, so that it can be
	 *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
	 */
	public function get_where_clause() {
		return $this->get_where_clause_for_posts();
	}


	/**
	 * Get the WHERE clause if the domain is known to be posts.
	 *
	 * @return string
	 */
	private function get_where_clause_for_posts() {
		if ( is_array( $this->statuses ) ) {
			$accepted_statuses = $this->statuses;
		} else {
			$single_status = $this->statuses;

			switch ( $single_status ) {
				case ElementStatusCondition::STATUS_PUBLIC:
					$accepted_statuses = array( 'publish' );
					break;
				case ElementStatusCondition::STATUS_AVAILABLE:
					// FIXME make the logic complete (involving WP_Query business logic and Access)
					$accepted_statuses = $this->post_status->get_available_post_statuses();
					if ( current_user_can( 'read_private_posts' ) ) {
						$accepted_statuses[] = 'private';
					}
					break;
				case ElementStatusCondition::STATUS_ANY:
					// Match anything, don't bother with adding a query.
					return ' 1 = 1 ';
				default:
					// Single status string. If this is a wrong input, we'll return zero results anyway.
					$accepted_statuses = array( $single_status );
					break;
			}
		}

		if ( empty( $accepted_statuses ) ) {
			// For some reason, we don't allow any post status. Match nothing.
			// Note: This cannot be reached because of the validation in the constructor.
			return ' 1 = 0 '; // @codeCoverageIgnore
		}

		return sprintf(
			' %s.post_status IN ( %s ) ',
			$this->join_manager->wp_posts( $this->for_role ),
			'\'' . implode( '\', \'', $accepted_statuses ) . '\''
		);
	}
}
