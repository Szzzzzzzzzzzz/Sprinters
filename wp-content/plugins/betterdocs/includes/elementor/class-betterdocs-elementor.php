<?php

use ElementorPro\Plugin;

/**
 * Working with elementor plugin
 *
 *
 * @since      1.3.0
 * @package    BetterDocs
 * @subpackage BetterDocs/elementor
 * @author     WPDeveloper <support@wpdeveloper.net>
 */
class BetterDocs_Elementor
{
    public static $pro_active;
    
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.3.0
     */
    public static function init()
    {
        self::$pro_active = is_plugin_active( 'betterdocs-pro/betterdocs-pro.php' );
        add_action( 'elementor/editor/before_enqueue_scripts', [__CLASS__, 'editor_enqueue_scripts'] );
        if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
            add_action( 'elementor/init', [__CLASS__, 'load_widget_file'] );
            add_action( 'elementor/documents/register', [__CLASS__, 'register_singel_documents_page'] );
            add_filter( 'elementor/theme/need_override_location', [__CLASS__, 'theme_template_include'], 10, 2 );
            add_action( 'elementor/widgets/widgets_registered', [__CLASS__, 'register_widgets'] );
            add_filter( 'elementor/editor/localize_settings', [__CLASS__, 'promote_pro_elements'] );
            add_action( 'wp_enqueue_scripts', [__CLASS__, 'editor_load_asset'] );
        }
    }
    
    /**
     *
     * Mange all widget for single docs
     *
     * @return string[]
     * @since  1.3.0
     */
    public static function get_widget_list()
    {
        $widget_arr = [
            'betterdocs-elementor-breadcrumbs' => 'BetterDocs_Elementor_Breadcrumbs',
            'betterdocs-elementor-title'       => 'BetterDocs_Elementor_Title',
            'betterdocs-elementor-content'     => 'BetterDocs_Elementor_Content',
            'betterdocs-elementor-sidebar'     => 'BetterDocs_Elementor_Sidebar',
            'betterdocs-elementor-navigation'  => 'BetterDocs_Elementor_Navigation',
            'betterdocs-elementor-doc-share'   => 'BetterDocs_Elementor_Doc_Share',
            'betterdocs-elementor-feedback'    => 'BetterDocs_Elementor_Feedback',
            'betterdocs-elementor-doc-date'    => 'BetterDocs_Elementor_Doc_Date',
            'betterdocs-elementor-search-form' => 'BetterDocs_Elementor_Search_Form',
        ];
        if ( is_plugin_active( 'betterdocs-pro/betterdocs-pro.php' ) ) {
            $widget_arr[ 'betterdocs-elementor-reactions' ] = 'BetterDocs_Elementor_Reactions';
        }
        return $widget_arr;
    }
    
    /**
     *
     * Load asset for elementor icon
     *
     * @since  1.3.0
     */
    public static function editor_enqueue_scripts()
    {
        wp_enqueue_style(
            'betterdocs-el-icon',
            BETTERDOCS_ADMIN_URL . 'assets/css/betterdocs-el-icon.css',
            false,
            BETTERDOCS_VERSION
        );
        
        
    }
    
    public static function editor_load_asset()
    {
        
        wp_enqueue_style(
            'betterdocs-el-edit',
            BETTERDOCS_ADMIN_URL . 'assets/css/betterdocs-el-edit.css',
            false,
            BETTERDOCS_VERSION
        );
        
        if ( !self::$pro_active ) {
            wp_enqueue_script(
                'betterdocs-el-editor',
                BETTERDOCS_ADMIN_URL . 'assets/js/betterdocs-el-editor.js',
                ['jquery'],
                BETTERDOCS_VERSION,
                true
            );
        }
    }
    
    public static function load_widget_file()
    {
        
        require_once BETTERDOCS_DIR_PATH . 'includes/elementor/betterdocs-single-docs.php';
        self::__register_tag();
        
        //load widget file
        foreach ( self::get_widget_list() as $key => $value ) {
            require_once BETTERDOCS_DIR_PATH . "includes/elementor/widgets/$key.php";
        }
        
    }
    
    public static function register_singel_documents_page( $documents_manager )
    {
        if ( class_exists( 'BetterDocs_Single_Docs' ) ) {
            $documents_manager->register_document_type( 'docs', BetterDocs_Single_Docs::get_class_full_name() );
        }
    }
    
    public static function theme_template_include( $need_override_location, $location )
    {
        
        if ( is_singular( ['docs'] ) && 'single' === $location ) {
            $need_override_location = true;
        }
        
        return $need_override_location;
    }
    
    public static function register_widgets( $widgets_manager )
    {
        foreach ( self::get_widget_list() as $value ) {
            if ( class_exists( $value ) ) {
                $widgets_manager->register_widget_type( new $value );
            }
        }
    }
    
    public static function __register_tag()
    {
        require_once BETTERDOCS_DIR_PATH . 'includes/elementor/widgets/betterdocs-elementor-title-tag.php';
        
        $module = Plugin::elementor()->dynamic_tags;
        $module->register_tag( new BetterDocs_Elementor_Title_Tag() );
    }
    
    public static function promote_pro_elements( $config )
    {
        
        if ( is_plugin_active( 'betterdocs-pro/betterdocs-pro.php' ) ) {
            return $config;
        }
        
        $promotion_widgets = [];
        
        if ( isset( $config[ 'promotionWidgets' ] ) ) {
            $promotion_widgets = $config[ 'promotionWidgets' ];
        }
        
        $combine_array = array_merge( $promotion_widgets, [
            [
                'name'       => 'betterdocs-elementor-reactions',
                'title'      => __( 'Doc Reactions', 'betterdocs' ),
                'icon'       => 'betterdocs-icon-Reactions',
                'categories' => '["betterdocs-elements"]',
            ],
        ] );
        
        $config[ 'promotionWidgets' ] = $combine_array;
        
        return $config;
    }
}

BetterDocs_Elementor::init();
