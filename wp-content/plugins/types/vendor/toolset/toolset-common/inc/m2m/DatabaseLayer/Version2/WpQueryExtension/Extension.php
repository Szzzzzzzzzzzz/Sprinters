<?php /** @noinspection DuplicatedCode */

namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\WpQueryExtension;

use IToolset_Post;
use OTGS\Toolset\Common\Relationships\API\RelationshipRole;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns\ConnectedElementTable;
use OTGS\Toolset\Common\Relationships\DatabaseLayer\WpQueryExtension\AbstractRelationshipsExtension;
use WP_Query;

/**
 * The toolset_relationships WP_Query extension for the second database layer version.
 *
 * See superclasses for further information.
 *
 * The JoinManager does the heavy lifting here.
 *
 * @since 4.0
 */
class Extension extends AbstractRelationshipsExtension {

	/**
	 * Get the table join manager object attached to the WP_Query instance or create and attach a new one.
	 *
	 * @param WP_Query $query
	 *
	 * @return JoinManager
	 */
	protected function get_table_join_manager( WP_Query $query ) {
		// This is a dirty hack but still cleanest considering we need to use this object
		// in different callbacks from WP_Query.
		//
		// The value is deliberately not stored as a constant since it should not be used anywhere else.
		$property_name = 'toolset_join_manager';
		if ( ! property_exists( $query, $property_name ) ) {
			$query->{$property_name} = $this->database_layer_factory->join_manager_for_wp_query_extension();
		}

		return $query->{$property_name};
	}


	/**
	 * @inheritDoc
	 */
	protected function get_join_clause( \WP_Query $wp_query ) {
		return $this->get_table_join_manager( $wp_query )->get_join_clauses();
	}


	/**
	 * @inheritDoc
	 */
	protected function get_where_clause(
		\WP_Query $wp_query,
		$relationship_slug,
		RelationshipRole $role_to_return,
		RelationshipRole $role_to_query_by,
		IToolset_Post $related_to_post
	) {
		$connected_elements_table_alias = $this->get_table_join_manager( $wp_query )->connected_elements_table(
			$relationship_slug, $role_to_return, $role_to_query_by, $related_to_post->get_id()
		);

		$element_id_column = ConnectedElementTable::ELEMENT_ID;
		$wpml_trid_column = ConnectedElementTable::WPML_TRID;

		$element_trid = $related_to_post->get_trid();
		$element_id = $related_to_post->get_id();

		if ( 0 !== $element_trid ) {
			return " AND {$connected_elements_table_alias}.{$wpml_trid_column} = {$element_trid} ";
		}

		return " AND {$connected_elements_table_alias}.{$element_id_column} = {$element_id} ";
	}


}
