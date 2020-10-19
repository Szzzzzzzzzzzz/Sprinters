<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/logging.php';

class OpinionStageAdminPageLoader {
	protected static $instance = false;
	protected $slug = false;
	protected $assetsPath  = "resources/";
	protected $helpersPath = "helpers/";
	protected $viewsPath   = "views/";

	protected function __construct() {
		// Check if page is for OpinionStage plugin and prepare page slug
		$this->OSAPL_PrepareSlug();

		// Apply page loader actions if it is OpinionStage plugin page
		if($this->slug != false){
			include_once( plugin_dir_path( __FILE__ ).'content-popup.php' );
			$this->OSAPL_Debug('OSAPL: Load Page Loader for Slug - '.$this->slug);

			$this->OSAPL_LoadFile();
			add_action( 'admin_enqueue_scripts',array( $this, 'OSAPL_LoadAssets' ) );
			add_action( 'admin_head', array( $this, 'OSAPL_LoadHeader' ) );
			add_action('admin_footer',array( $this, 'OSAPL_LoadFooter' ));
		}else{
	    	// Load content popup javascript
			include_once( plugin_dir_path( __FILE__ ).'content-popup.php' );
		}
	}

	public function OSAPL_Debug($string){
		opinionstage_error_log( $string );
	}

	public function OSAPL_PrepareSlug(){
		if(isset($_REQUEST['page'])){
			$qryStrCheckOs = sanitize_text_field($_REQUEST['page']);
			$qryStrCheckOs = explode("-",$qryStrCheckOs);
			if($qryStrCheckOs[0] == 'opinionstage'){
				$querystring = str_replace('opinionstage-', '', sanitize_text_field($_REQUEST['page']));

				$this->OSAPL_Debug('OSAPL: Slug applied - '.$querystring);
				$this->slug = $querystring;
			}
		}
	}

	public function OSAPL_LoadFile(){

		if(file_exists(plugin_dir_path( __FILE__ ).$this->assetsPath."common.php")){
			include_once(plugin_dir_path( __FILE__ ).$this->assetsPath."common.php");
			$this->OSAPL_Debug('OSAPL: Loaded Common Assets File');
		}else{
			$this->OSAPL_Debug('OSAPL: Common Assets File Not Found');
		}

		if(file_exists(plugin_dir_path( __FILE__ ).$this->assetsPath.$this->slug.".php")){
			include_once(plugin_dir_path( __FILE__ ).$this->assetsPath.$this->slug.".php");
			$this->OSAPL_Debug('OSAPL: Loaded '.$this->slug.' Assets File');
		}else{
			$this->OSAPL_Debug('OSAPL: '.$this->slug.' Assets File Not Found');
		}
	}

	public function OSAPL_LoadAssets(){
		$function_name = str_replace("-", "_", $this->slug);
		$function_name = "opinionstage_".$function_name."_load_resources";
		if( function_exists($function_name) ){
			$this->OSAPL_Debug('OSAPL: Calling resources function - '.$function_name);
			call_user_func($function_name);
		}else{
			$this->OSAPL_Debug('OSAPL: Resources function does not exist: '.$function_name);
		}
		$function_name_common = "opinionstage_common_load_resources";
		if( function_exists($function_name_common) ){
			$this->OSAPL_Debug('OSAPL: Calling common resources function - '.$function_name_common);
			call_user_func($function_name_common);
		}else{
			$this->OSAPL_Debug('OSAPL: Resources function does not exist: '.$function_name_common);
		}
	}

	public function OSAPL_LoadHeader(){
		$function_name_header = str_replace("-", "_", $this->slug);
		$function_name_header = "opinionstage_".$function_name_header."_load_header";
		if( function_exists($function_name_header) ){
			$this->OSAPL_Debug('OSAPL: Calling header function - '.$function_name_header);
			call_user_func($function_name_header);
		}else{
			$this->OSAPL_Debug('OSAPL: Header function does not exist: '.$function_name_header);
		}

		$function_name_header_common = "opinionstage_common_load_header";
		if( function_exists($function_name_header_common) ){
			$this->OSAPL_Debug('OSAPL: Calling common header function - '.$function_name_header_common);
			call_user_func($function_name_header_common);
		}else{
			$this->OSAPL_Debug('OSAPL: Header common function does not exist: '.$function_name_header_common);
		} 
	}

	public function OSAPL_LoadFooter(){
		$function_name_footer = str_replace("-", "_", $this->slug);
		$function_name_footer = "opinionstage_".$function_name_footer."_load_footer";
		if( function_exists($function_name_footer) ){
			$this->OSAPL_Debug('OSAPL: Calling footer function - '.$function_name_footer);
			call_user_func($function_name_footer);
		}else{
			$this->OSAPL_Debug('OSAPL: Footer function does not exist: '.$function_name_footer);
		}
		$function_name_footer_common = "opinionstage_common_load_footer";
		if( function_exists($function_name_footer_common) ){
			$this->OSAPL_Debug('OSAPL: Calling common footer function - '.$function_name_footer_common);
			call_user_func($function_name_footer_common);
		}else{
			$this->OSAPL_Debug('OSAPL: Footer common function does not exist: '.$function_name_footer_common);
		}
	}

	public function OSAPL_LoadTemplateFile(){
		$file_name = str_replace("-", "_", $this->slug);
		$file_name = $file_name.".php";
		if(file_exists(plugin_dir_path( __FILE__ ).$this->helpersPath."common.php")){
			$this->OSAPL_Debug('OSAPL: Loading common file - '.'common.php');
			include plugin_dir_path( __FILE__ ).$this->helpersPath."common.php";
		}else{
			$this->OSAPL_Debug('OSAPL: Common file does not exist: '.'common.php');
		}
		if(file_exists(plugin_dir_path( __FILE__ ).$this->helpersPath.$file_name)){
			$this->OSAPL_Debug('OSAPL: Loading helpers file - '.$file_name);
			include plugin_dir_path( __FILE__ ).$this->helpersPath.$file_name;
		}else{
			$this->OSAPL_Debug('OSAPL: Helpers file does not exist: '.$file_name);
		}
		if(file_exists(plugin_dir_path( __FILE__ ).$this->viewsPath.$file_name)){
			$this->OSAPL_Debug('OSAPL: Loading views file - '.$file_name);
			include plugin_dir_path( __FILE__ ).$this->viewsPath.$file_name;
		}else{
			$this->OSAPL_Debug('OSAPL: Views file does not exist: '.$file_name);
		}
	}

	public static function getInstance(){
		if(self::$instance == false){
			self::$instance = new OpinionStageAdminPageLoader();
		}
		return self::$instance;
	}
}

OpinionStageAdminPageLoader::getInstance();
