<?php

/**
 * Note: Keep the IToolset_Relationship_Role interface here for backward compatibility purposes.
 * All role classes must implement it, just RelationshipRole is not enough. Code like this
 * still needs to pass:
 *
 * `$role instanceof IToolset_Relationship_Role`
 */
abstract class Toolset_Relationship_Role_Abstract implements IToolset_Relationship_Role {

	public function __toString() {
		return $this->get_name();
	}

}
