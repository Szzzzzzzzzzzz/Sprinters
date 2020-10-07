<?php

namespace OTGS\Toolset\Common\Relationships\API;

/**
 * Represents one element's role in a relationship.
 *
 * Always expect this interface rather than relying on \IToolset_Relationship_Role.
 *
 * @since 4.0
 */
interface RelationshipRole {

	/**
	 * Role name.
	 *
	 * @return string
	 */
	public function get_name();


	/**
	 * @return bool
	 */
	public function is_parent_child();


	/**
	 * Convert this to a role name string.
	 *
	 * @return string
	 */
	public function __toString();
}
