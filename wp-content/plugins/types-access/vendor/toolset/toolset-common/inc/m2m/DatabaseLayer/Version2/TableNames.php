<?php

namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2;


/**
 * Holds names of database tables in the second version of the database layer.
 *
 * @since 4.0
 */
class TableNames {

	const ASSOCIATIONS = 'toolset_associations';
	const CONNECTED_ELEMENTS = 'toolset_connected_elements';
	const RELATIONSHIPS = 'toolset_relationships';
	const TYPE_SETS = 'toolset_type_sets';

	const ALL_RELATIONSHIP_TABLES = [
		self::ASSOCIATIONS,
		self::CONNECTED_ELEMENTS,
		self::RELATIONSHIPS,
		self::TYPE_SETS,
	];

	const ICL_TRANSLATIONS = 'icl_translations';


	/** @var \wpdb */
	private $wpdb;


	/**
	 * TableNames constructor.
	 *
	 * @param \wpdb $wpdb
	 */
	public function __construct( \wpdb $wpdb ) {
		$this->wpdb = $wpdb;
	}


	/**
	 * Determine the full name of a table how it exists (or should exist) in the database for the current site.
	 *
	 * @param string $table_name One of the well-defined table names from this class.
	 *
	 * @return string
	 */
	public function get_full_table_name( $table_name ) {
		return $this->wpdb->prefix . $table_name;
	}

}
