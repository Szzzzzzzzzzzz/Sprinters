<?php

namespace ToolsetBlocks\PublicDependencies;

use ToolsetBlocks\PublicDependencies\Dependency\IContent;


/**
 * Frontend dependencies
 */
class Frontend {

	/** @var IGeneral[] */
	private $dependencies = array();

	/** @var IContent[] */
	private $dependencies_content = array();

	/**
	 * Add a content based dependecy
	 * @param IContent $dependency [description]
	 */
	public function add_content_based_dependency( IContent $dependency ) {
		$this->dependencies_content[] = $dependency;
	}

	/**
	 * Load all previous added dependencies
	 */
	public function load() {
		// content related dependencies
		if ( null !== $this->dependencies_content ) {
			add_filter( 'the_content', array( $this, 'load_dependencies_content' ), 8 );
			// And for WPAs, we need to hook to this other filter, because the_content is empty there.
			add_filter( 'toolset_the_content_wpa', array( $this, 'load_dependencies_content' ) );
		}

		// general dependencies
		foreach ( $this->dependencies as $dependency ) {
			$dependency->load_dependencies();
		}
	}

	/**
	 * Add a content based dependecy
	 * @param IGeneral $dependency [description]
	 */
	public function add_dependency( IGeneral $dependency ) {
		$this->dependencies[] = $dependency;
	}

	/**
	 * Load content based dependencies
	 *
	 * @filter 'the_content' 8
	 * @param $content
	 * @return string Untouched content
	 */
	public function load_dependencies_content( $content ) {
		foreach( $this->dependencies_content as $dependency ) {
			if( $dependency->is_required_for_content( $content ) ) {
				$dependency->load_dependencies();
			}
		}

		return $content;
	}
}
