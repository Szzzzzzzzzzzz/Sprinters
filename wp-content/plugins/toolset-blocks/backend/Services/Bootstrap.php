<?php

namespace OTGS\Toolset\Views\Services;

use OTGS\Toolset\Views\Blocks as Blocks;
use OTGS\Toolset\Views\Controller\Compatibility\EditorBlocks\View\Block;
use OTGS\Toolset\Views\Controllers\V1\ViewContentSelection;
use OTGS\Toolset\Views\RelationshipQueryFactory;
use OTGS\Toolset\Views\Services\ViewParsingService;

use const OTGS\Toolset\Views\UserCapabilities\EDIT_VIEWS;

class Bootstrap {
	const MODERN_BLOCK_NAME = 'toolset-views/view-editor';
	const BLOCK_NAME = 'toolset/view';
	const BLOCK_VIEW_DATA_POST_META_KEY = '_wpv_view_data';

	protected $toolset_ajax_manager;

	/** @var array */
	private $view_get_instance;

	/** @var \Toolset_Constants */
	private $constants;

	/**
	 * Bootstrap constructor.
	 *
	 * @param array $view_get_instance The instance of the WPV_View static class.
	 */
	public function __construct( array $view_get_instance, \Toolset_Constants $constants = null ) {
		$this->view_get_instance = $view_get_instance;
		$this->constants = $constants
			? $constants
			: new \Toolset_Constants();
	}

	/**
	 * Initialize stuff for new view editor
	 */
	public function initialize() {
		$dic = apply_filters( 'toolset_dic', false );

		/*
		 * Initialize REST API endpoints
		 */
		$this->create_rest_endpoints();

		/*
		 * Register block categories
		 */
		add_filter( 'block_categories', array( $this, 'register_block_categories' ), 20, 2 );

		/*
		 * Register render callback which will be used for view rendering
		 * using template from the Gutenberg "modern" mode
		 */
		register_block_type(
			'toolset-views/view-editor',
			array(
				'render_callback' => array( $this, 'view_render_callback' ),
			)
		);

		/*
		 * Register render callback which will be used for view rendering
		 * using template from the Gutenberg "modern" mode
		 */
		register_block_type(
			'toolset-views/wpa-editor',
			array(
				'render_callback' => array(
					$this,
					'view_render_callback',
				),
			)
		);

		/**
		 * Register Gutenberg Views editor assets
		 */
		add_action( 'wpv_action_require_frontend_assets', array($this, 'register_view_general_assets') );
		add_action( 'enqueue_block_assets', array($this, 'register_view_general_assets') );

		/**
		 * Render footer templates for some Views components, like query and frontend filters.
		 */
		add_action( 'admin_footer', array( $this, 'render_footer_templates' ) );
		/*
		 *
		 */
		$parser = new ViewParsingService();
		$parser->init();

		add_filter( 'wpv_filter_get_view_parent_post_id', array( $this, 'get_view_parent_post_id' ), 10, 2 );
		/**
		 * Add a handler to automatically replace old view block markup with a new one
		 */
		add_action( 'the_post', array( $this, 'convert_legacy_block_markup' ) );
		/**
		 * Create a dedicated tab for custom capabilities of Views
		 */
		add_filter( 'wpcf_access_custom_capabilities', array( $this, 'access_custom_capabilities' ), 50 );

		$wpml = new WPMLService();
		$wpml->init();

		$query_filter = $dic->make(
			QueryFilterService::class,
			array(
				':toolset_constants' => new \Toolset_Constants(),
			)
		);
		$query_filter->init();

		// init blocks
		$sorting = new Blocks\Sorting();
		$sorting->initialize();

		$pagination = new Blocks\Pagination();
		$pagination->initialize();

		$content_selection_service = $dic->make(
			ContentSelectionService::class,
			array(
				':relationship_query_factory' => class_exists( '\Toolset_Relationship_Query_Factory' ) ? $dic->make( '\Toolset_Relationship_Query_Factory' ) : null,
				':post_type_query_factory' => class_exists( '\Toolset_Post_Type_Query_Factory' ) ? $dic->make( '\Toolset_Post_Type_Query_Factory' ) : null,
				':toolset_post_type_repository' => class_exists( '\Toolset_Post_Type_Repository' ) ? $dic->make( '\Toolset_Post_Type_Repository' ) : null,
				':toolset_relationship_role_all' => class_exists( '\Toolset_Relationship_Role' ) ? \Toolset_Relationship_Role::all() : array()
			)
		);

		/** @var ViewEditorService $view_editor_service */
		$view_editor_service = $dic->make(
			ViewEditorService::class,
			array(
				':content_selection_service' => $content_selection_service,
				':toolset_settings_get_instance' => array( \Toolset_Settings::class, 'get_instance' ),
				':toolset_ajax_manager_get_instance' => array( \WPV_Ajax::class, 'get_instance' ),
			)
		);
		$view_editor_service->initialize();

		add_filter( 'wpv_filter_view_output', array( $this, 'maybe_add_the_custom_search_overlay_and_spinner' ), 10, 2 );
	}

	public function access_custom_capabilities( $data )
    {
        $wp_roles['label'] = __( 'Views capabilities', 'wpv-views' );
        $wp_roles['capabilities'] = array( EDIT_VIEWS => __( 'Edit Views', 'wpv-views' ) );
        $data[] = $wp_roles;
        return $data;
    }

	/**
	 * the_post filter handler to convert old view blocks to new (view-editor)
	 * needed to completely get rid of having registered toolset/view block
	 * @param \WP_Post $post Post to convert markup from.
	 */
	public function convert_legacy_block_markup( $post ) {
		// run this on admin page only
		if ( ! is_admin() ) {
			return;
		}
		$service = new ViewParsingService();
		do {
			$data = $service->find_block_in_text( $post->post_content, 'toolset/view' );
			if ( null === $data ) {
				break;
			}
			$markup = substr( $post->post_content, $data['start'], $data['end'] - $data['start'] );
			$blocks = parse_blocks( $markup );
			// if parse_blocks found nothing, but $data is not null - this means data corruption
			if ( count( $blocks ) === 0 ) {
				break;
			}
			$block = $blocks[0];
			$new_attributes = $block['attrs'];
			// if no view attribute is set, this means corrupted block and we're not able to convert it
			if ( ! isset( $new_attributes['view'] ) ) {
				break;
			}
			// if view attribute is just view ID, we can retrieve everything from the DB and go ahead
			if ( is_numeric( $new_attributes['view'] ) ) {
				$view_data = \WP_Post::get_instance( $new_attributes['view'] );
				$new_attributes['view'] = [
					'ID' => ( string ) $new_attributes['view'],
					'post_title' => $view_data->post_title,
					'post_name' => $view_data->post_name,
				];
			} else {
				// otherwise let's do JSON decode
				$view_data = json_decode( $new_attributes['view'] );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					$new_attributes['view'] = $view_data;
				}
			}
			// set missing attributes
			$new_attributes['insertExisting'] = '1';
			$new_attributes['wizardDone'] = true;
			$new_attributes['viewName'] = $view_data->post_title;

			// Compile the rest of the shortcode attributes
			$shortcode_atts = $this->get_view_block_shortcode_attributes( $block );
			$shortcode_atts = ! empty( $shortcode_atts ) ? ' ' . $shortcode_atts : $shortcode_atts;

			// create new markup
			$new_markup = '<!-- wp:toolset-views/view-editor ' . wp_json_encode( $new_attributes ) . ' -->' .
				'<div class="wp-block-toolset-views-view-editor ">[wpv-view name="' . $view_data->post_name . '"' . $shortcode_atts . ']</div>' .
				'<!-- /wp:toolset-views/view-editor -->';
			// and use it to replace the old markup
			$post->post_content = substr( $post->post_content, 0, $data['start'] ) .
				$new_markup .
				substr( $post->post_content, $data['end'] );
		} while ( null !== $data );
	}

	/**
	 * Gets the legacy View block shortcode attributes for the migration of the legacy View block shortcode to the shortcode
	 * of the new View block.
	 *
	 * @param  array $block The parsed block as it comes from the native block parser.
	 *
	 * @return string Space separated string consisting of key-value pairs in the format of key="value" for the new View
	 *                block shortcode attributes.
	 */
	private function get_view_block_shortcode_attributes( $block ) {
		$block_content = $block['innerHTML'];
		$view_shortcode_regex = get_shortcode_regex( array( 'wpv-view' ) );

		$matches = array();
		preg_match( '/' . $view_shortcode_regex . '/', $block_content, $matches );

		$view_shortcode_atts = array();
		// $matches[3] is where the View shortcode attributes should be.
		if ( isset( $matches[3] ) ) {
			$view_shortcode_atts = shortcode_parse_atts( $matches[3] );
			unset( $view_shortcode_atts['id'] );
			unset( $view_shortcode_atts['name'] );
		}

		$view_shortcode_atts_string = implode(
			' ',
			array_map(
				function ( $attr_value, $attr_key ) {
					return sprintf( "%s=\"%s\"", esc_attr( $attr_key ), esc_attr( $attr_value ) );
				},
				$view_shortcode_atts,
				array_keys( $view_shortcode_atts )
			)
		);

		return $view_shortcode_atts_string;
	}

	/**
	 * Creates REST API endpoints for view editor
	 */
	protected function create_rest_endpoints() {
		add_action('rest_api_init', function () {
			/**
			 * @var \OTGS\Toolset\Common\Auryn\Injector
			 */
			$dic = apply_filters( 'toolset_dic', false );

			$view_ordering_fields_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewOrderingFields();
			$view_ordering_fields_controller->register_routes();
			$post_types_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewPostTypes();
			$post_types_controller->register_routes();
			$taxonomies_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewTaxonomies();
			$taxonomies_controller->register_routes();
			$user_groups_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewUserGroups();
			$user_groups_controller->register_routes();
			$view_fields_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewFields();
			$view_fields_controller->register_routes();
			$views_controller = new \OTGS\Toolset\Views\Controllers\V1\Views();
			$views_controller->register_routes();
			$view_query_filter_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewQueryFilter();
			$view_query_filter_controller->register_routes();
			$custom_search_fields_controller = new \OTGS\Toolset\Views\Controllers\V1\CustomSearchFields();
			$custom_search_fields_controller->register_routes();

			$content_selection_service = $dic->make(
				ContentSelectionService::class,
				array(
					':relationship_query_factory' => class_exists( '\Toolset_Relationship_Query_Factory' ) ? $dic->make( '\Toolset_Relationship_Query_Factory' ) : null,
					':post_type_query_factory' => class_exists( '\Toolset_Post_Type_Query_Factory' ) ? $dic->make( '\Toolset_Post_Type_Query_Factory' ) : null,
					':toolset_post_type_repository' => class_exists( '\Toolset_Post_Type_Repository' ) ? $dic->make( '\Toolset_Post_Type_Repository' ) : null,
					':toolset_relationship_role_all' => class_exists( '\Toolset_Relationship_Role' ) ? \Toolset_Relationship_Role::all() : array()
				)
			);

			$view_content_selection_controller = $dic->make(
				ViewContentSelection::class,
				array(
					':content_selection_service' => $content_selection_service,
				)
			);
			$view_content_selection_controller->register_routes();

			$wpa_controller = $dic->make(
				'\OTGS\Toolset\Views\Controllers\V1\Wpa',
				array(
					':views_controller' => $views_controller,
				)
			);
			$wpa_controller->register_routes();
		});
	}

	/**
	 * Register Gutenberg block categories
	 * @param $categories
	 * @param $post
	 * @return array
	 */
	public function register_block_categories($categories, $post) {
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'toolset-views',
					'title' => __( 'Toolset Views Elements', 'wpv-views' ),
				),
			)
		);
	}

	/**
	 * Filters the output of a View/Wordpress Archive to maybe inject the custom search overlay and spinner.
	 *
	 * @param string $out     The output markup of the View.
	 * @param int    $view_id The ID of the View.
	 *
	 * @return string The output markup of the View.
	 */
	public function maybe_add_the_custom_search_overlay_and_spinner( $out, $view_id ) {
		$custom_search_loading_spinner_markup = $this->maybe_get_custom_search_loading_overlay_markup( $view_id );
		if ( ! empty( $custom_search_loading_spinner_markup ) ) {
			$out = sprintf(
				'<div class="wpv-view-wrapper">%s%s</div>',
				$custom_search_loading_spinner_markup,
				$out
			);
		}
		return $out;
	}

	/**
	 * Callback to render the view editor block as view shortcode ignoring Gutenberg output
	 * @param $attributes
	 * @param $content
	 * @return string
	 */
	public function view_render_callback( $attributes, $content ) {
		if ( ! empty( $attributes['view'] ) ) {
			return $content;
		}
		if ( empty( $attributes['viewId'] ) && empty( $attributes['viewSlug'] ) ) {
			return '';
		}
		$outputClass = " wpv-view-output";
		$class = isset( $attributes['align'] ) && $attributes['align'] ? 'class="' . esc_attr( 'align' . $attributes['align'] . $outputClass ) . '"' : '';
		if ( empty( $class ) ) {
			$class .= 'class="' . esc_attr( $outputClass ) . '"';
		}

		$cached = '';
		if (
			! is_admin() &&
			! $this->constants->defined( 'REST_REQUEST' ) &&
			isset( $attributes['cached'] ) &&
			false === $attributes['cached']
		) {
			$cached = ' cached="off"';
		}

		if( ! array_key_exists( 'viewId', $attributes ) && ! array_key_exists( 'viewSlug', $attributes ) ) {
			// Shouldn't happen that neither an id (legacy) nor a slug (current) is available.
			return $content;
		}

		if( ! array_key_exists( 'viewId', $attributes ) ) {
			$view_identification_attribute = sprintf( 'name="%s"', esc_attr( $attributes['viewSlug'] ) );
		} else {
			// Theoretically the slug should always be used if available - but probably this was added
			// for some good (but unknown) reason, so I kept it.
			$view_post = get_post( $attributes['viewId'] );
			if (  $view_post->ID === $attributes['viewId'] && array_key_exists( 'viewSlug', $attributes ) ) {
				$view_identification_attribute = sprintf( 'name="%s"', esc_attr( $attributes['viewSlug'] ) );
			} else {
				$view_identification_attribute = sprintf( 'id="%s"', esc_attr( $attributes['viewId'] ) );
			}
		}

		return isset( $view_identification_attribute ) ?
			sprintf(
				'<div %s data-toolset-views-view-editor="1">[wpv-view %s%s]</div>',
				$class,
				$view_identification_attribute,
				$cached
			) :
			$content;
	}

	/**
	 * Gets the custom search loading overlay markup if one is necessary for the View with $view_id.
	 *
	 * TODO: When the View block will switch to client side rendering this method along with "get_rgba_string_by_array" will need to be removed.
	 *
	 * @param string $view_id The ID of the View.
	 *
	 * @return string         The custom search loading overlay markup.
	 */
	private function maybe_get_custom_search_loading_overlay_markup( $view_id ) {
		$view_data = get_post_meta( $view_id, '_wpv_view_data', true );

		if ( ! $view_data ) {
			return '';
		}

		$custom_search_mode = toolset_getnest( $view_data, array( 'custom_search', 'mode_helper' ), '' );

		// If the View doesn't have custom search or the custom search is not using AJAX.
		if ( ! in_array( $custom_search_mode, array( 'ajaxrefreshonsubmit', 'ajaxrefreshonchange' ), true ) ) {
			return '';
		}

		$custom_search_spinner_url = '';
		// For older Views, the default spinner source is the built-in spinner in Views.
		$custom_search_spinner_source = toolset_getnest( $view_data, array( 'custom_search', 'customSearchSpinnerSource' ), 'builtin' );

		switch ( $custom_search_spinner_source ) {
			// AJAX custom search using built-in spinners.
			case 'builtin':
				// For older Views, the default spinner is the "spinner1".
				$custom_search_spinner_type = toolset_getnest( $view_data, array( 'custom_search', 'customSearchSpinnerType' ), 'spinner1' );
				$custom_search_spinner_url = apply_filters(
					'wpv_filter_get_spinner_url',
					$custom_search_spinner_type
				);
				break;
			// AJAX custom search using custom uploaded spinners.
			case 'uploaded':
				$custom_search_spinner_url = toolset_getnest( $view_data, array( 'custom_search', 'customSearchSpinnerUploaded' ), '' );
				break;
		}

		$custom_search_overlay_color_array = toolset_getnest(
			$view_data,
			array( 'custom_search', 'customSearchOverlayColor' ),
			''
		);
		if ( ! is_array( $custom_search_overlay_color_array ) ) {
			$custom_search_overlay_color_array = array(
				'r' => '182',
				'g' => '218',
				'b' => '224',
				'a' => '0.7',
			);
		}
		$custom_search_overlay_color = $this->get_rgba_string_by_array( $custom_search_overlay_color_array );

		// $custom_search_spinner_url can be empty in case the "No spinner" option is selected. In this case, only the
		// overlay will be displayed with no other visual information that a custom search is happening.
		return '<div class="wpv-custom-search-loading-overlay js-wpv-custom-search-loading-overlay" style="display:none;background:' . $custom_search_overlay_color . ';">
					<div class="spinner">
						<div class="icon" style="background:url(' . $custom_search_spinner_url . ')"></div>
					</div>
				</div>';
	}

	/**
	 * Get RGBA string: rgba( 123, 123, 123, 0.5 );
	 * If the input is no rgba array an empty string will be returned.
	 *
	 * TODO: When the View block will switch to client side rendering this method along with "maybe_get_custom_search_loading_overlay_markup" will need to be removed.
	 *
	 * @param array $rgba The color in an RGBA array.
	 *
	 * @return string
	 */
	private function get_rgba_string_by_array( $rgba ) {
		if (
			! is_array( $rgba ) ||
			! array_key_exists( 'r', $rgba ) ||
			! array_key_exists( 'g', $rgba ) ||
			! array_key_exists( 'b', $rgba ) ||
			! array_key_exists( 'a', $rgba )
		) {
			return '';
		}

		return 'rgba( ' . $rgba['r'] . ', ' . $rgba['g'] . ', ' . $rgba['b'] . ', ' . $rgba['a'] . ' )';
	}

	public function register_view_general_assets() {
		// Views blocks frontend scripts.
		if ( ! is_admin() ) {
			wp_enqueue_script(
				'views-blocks-frontend',
				WPV_URL . '/public/js/views-frontend.js',
				array( 'toolset-common-es-masonry' ),
				WPV_VERSION,
				true
			);
		}

		/**
		 * register style for frontend
		 */
		wp_enqueue_style(
			'view_editor_gutenberg_frontend_assets',
			WPV_URL . '/public/css/views-frontend.css',
			array( 'toolset-common-es' ),
			WPV_VERSION
		);
	}

	/**
	 * Render footer templates for some Views components, like query and frontend filters.
	 *
	 * @since 2.9
	 */
	public function render_footer_templates() {
		$template_repository = \WPV_Output_Template_Repository::get_instance();
		$renderer = \Toolset_Renderer::get_instance();

		// Template for the ancestor selector on the frontend filter by post relationships
		$renderer->render(
			$template_repository->get( \WPV_Output_Template_Repository::ADMIN_FILTERS_POST_RELATIONSHIP_ANCESTOR_NODE ),
			null
		);
	}

	/**
	 * Gets the parent post id of a View, if any, for the cases that a View is used inside a post.
	 *
	 * @param null|string|int $view_parent_post_id
	 * @param null|string|int $view_id
	 *
	 * @return mixed
	 */
	public function get_view_parent_post_id( $view_parent_post_id, $view_id ) {
		$view = call_user_func( $this->view_get_instance, $view_id );

		if ( null === $view ) {
			return $view_parent_post_id;
		}

		$parent_view_id = (int) $view->get_parent_post_id();

		return $parent_view_id ? $parent_view_id : $view_parent_post_id;
	}
}
