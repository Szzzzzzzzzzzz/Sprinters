<?php /** @noinspection PhpUnhandledExceptionInspection */

/**
 * Initialize the Auryn dependency injector and offer it through a toolset_dic filter and functions.
 *
 * @since 3.0.6
 */

namespace {

	/**
	 * @return \OTGS\Toolset\Common\Auryn\Injector
	 */
	function toolset_dic() {
		static $dic;

		if ( null === $dic ) {
			/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
			$dic = new \OTGS\Toolset\Common\Auryn\Injector();
		}

		return $dic;
	}


	/** @noinspection PhpDocMissingThrowsInspection */
	/**
	 * @param $class_name
	 * @param array $args
	 *
	 * @return mixed
	 * @deprecated See https://github.com/rdlowrey/auryn#example-use-cases
	 */
	function toolset_dic_make( $class_name, $args = [] ) {
		/** @noinspection PhpUnhandledExceptionInspection */
		return toolset_dic()->make( $class_name, $args );
	}


	add_filter( 'toolset_dic', static function ( /** @noinspection PhpUnusedParameterInspection */ $ignored ) {
		return toolset_dic();
	} );

}


/**
 * Initialize the DIC for usage of Toolset Common classes.
 */

namespace OTGS\Toolset\Common\DicSetup {

	use OTGS\Toolset\Common\GuiBase\DialogBoxFactory;
	use OTGS\Toolset\Common\Relationships\DatabaseLayer\AssociationQueryCache;
	use OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory;
	use OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode;
	use OTGS\Toolset\Common\Utils\RequestMode;
	use OTGS\Toolset\Common\WPML\WpmlService;
	use Toolset_Element_Factory;

	/** @var \OTGS\Toolset\Common\Auryn\Injector $dic */
	$dic = apply_filters( 'toolset_dic', null );

	// To expose existing singleton classes, use delegate callbacks. These callbacks will
	// be invoked only when the instance is actually needed, thus save performance.
	// Only after a delegate is used, we'll use the $injector->share() method to
	// provide the singleton instance directly and to improve performance a bit further.
	$singleton_delegates = [
		'\Toolset_Ajax' => static function () {
			return \Toolset_Ajax::get_instance();
		},
		'\Toolset_Assets_Manager' => static function () {
			return \Toolset_Assets_Manager::get_instance();
		},
		'\Toolset_Output_Template_Repository' => static function () {
			return \Toolset_Output_Template_Repository::get_instance();
		},
		'\Toolset_Post_Type_Repository' => static function () {
			return \Toolset_Post_Type_Repository::get_instance();
		},
		'\Toolset_Relationship_Definition_Repository' => static function () {
			do_action( 'toolset_do_m2m_full_init' );

			return \Toolset_Relationship_Definition_Repository::get_instance();
		},
		'\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Migration_Controller' => static function () {
			$relationship_controller = \OTGS\Toolset\Common\Relationships\MainController::get_instance();
			$relationship_controller->initialize();
			$relationship_controller->force_autoloader_initialization();

			return new \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Migration_Controller();
		},
		'\Toolset_Renderer' => static function () {
			return \Toolset_Renderer::get_instance();
		},
		'\Toolset_Constants' => static function () {
			return new \Toolset_Constants();
		},
		'\Toolset_WPML_Compatibility' => static function () {
			return WpmlService::get_instance();
		},
		'\OTGS\Toolset\Common\WPML\WpmlService' => static function () {
			return WpmlService::get_instance();
		},
		'\Toolset_Field_Group_Post_Factory' => static function () {
			return \Toolset_Field_Group_Post_Factory::get_instance();
		},
		'\OTGS\Toolset\Common\GuiBase\DialogBoxFactory' => static function () {
			\Toolset_Common_Bootstrap::get_instance()->register_gui_base();

			return new DialogBoxFactory( \Toolset_Gui_Base::get_instance() );
		},
		'\wpdb' => static function () {
			global $wpdb;

			return $wpdb;
		},
		'\Toolset_Field_Definition_Factory_Post' => static function () {
			return \Toolset_Field_Definition_Factory_Post::get_instance();
		},
		'\Toolset_Field_Definition_Factory_User' => static function () {
			return \Toolset_Field_Definition_Factory_User::get_instance();
		},
		'\Toolset_Field_Definition_Factory_Term' => static function () {
			return \Toolset_Field_Definition_Factory_Term::get_instance();
		},
		'\Toolset_Condition_Plugin_Views_Active' => static function () {
			return new \Toolset_Condition_Plugin_Views_Active();
		},
		'\Toolset_Condition_Plugin_Layouts_Active' => static function () {
			return new \Toolset_Condition_Plugin_Layouts_Active();
		},
		'\Toolset_Common_Bootstrap' => static function () {
			return \Toolset_Common_Bootstrap::get_instance();
		},
		'\WPCF_Roles' => static function () {
			return \WPCF_Roles::getInstance();
		},
		'\WP_Views_plugin' => static function () {
			global $WP_Views;

			return $WP_Views;
		},
		'\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode' => static function () {
			return new \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode();
		},
		'\Toolset_Relationship_Controller' => static function () {
			return \OTGS\Toolset\Common\Relationships\MainController::get_instance();
		},
		'\OTGS\Toolset\Common\Relationships\MainController' => static function () {
			return \OTGS\Toolset\Common\Relationships\MainController::get_instance();
		},
		'\OTGS\Toolset\Common\Relationships\DatabaseLayer\AssociationQueryCache' => static function () {
			return AssociationQueryCache::get_instance();
		},
		'\Toolset_Gui_Base' => static function () {
			$toolset_common_bootstrap = \Toolset_Common_Bootstrap::get_instance();
			$toolset_common_bootstrap->register_gui_base();
			$gui_base = \Toolset_Gui_Base::get_instance();
			$gui_base->init();

			return $gui_base;
		},
		'\OTGS\Toolset\Common\Upgrade\ExecutedCommands' => static function () {
			return new \OTGS\Toolset\Common\Upgrade\ExecutedCommands();
		},
		DatabaseLayerFactory::class => static function() {
			global $wpdb;
			return new DatabaseLayerFactory(
				toolset_dic()->make( DatabaseLayerMode::class ), $wpdb, WpmlService::get_instance(), new Toolset_Element_Factory()
			);
		},
	];

	foreach ( $singleton_delegates as $class_name => $callback ) {
		/** @noinspection PhpUnhandledExceptionInspection */
		$dic->delegate( $class_name, static function () use ( $callback, $dic ) {
			$instance = $callback();
			$dic->share( $instance );

			return $instance;
		} );
	}

	// Direct instances sharing; Use this *only* for classes that are used in 100% of requests.
	$dic->share( new RequestMode() );
}
