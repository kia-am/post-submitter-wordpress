<?php
/*
Plugin Name: Post Submitter
Plugin URI: http://welearn.site
Description: Description
Version: 1.0
Author: Welearn
Author URI: http://welearn.site
License: A "Slug" license name e.g. GPL2
*/

class WL_PS{
	
	function __construct(){
		register_activation_hook(__FILE__,array(&$this,'wl_ps_activition'));
		add_action('plugins_loaded',array(&$this,'wl_ps_defining_constants'),1);
		add_action('plugins_loaded',array(&$this,'wl_ps_load_textdomain'),1);
		add_action('plugins_loaded',array(&$this,'wl_ps_set_includes'),1);
		add_action('plugins_loaded',array(&$this,'wl_ps_load_includes'),1);
	}
	
	public function wl_ps_activition(){ 
		if(!get_option('wl_ps_opts')){
			add_option('wl_ps_opts');
		}
	}
	
	public function wl_ps_defining_constants(){
		define('VERSION','1.0.0');
		define('PLUGIN_NAME','post-submitter');
		define('BASE_PLUGIN_DIR',PLUGIN_NAME.'/'.PLUGIN_NAME.'.php');
		define('PLUGIN_URL',plugin_dir_url(__FILE__));
		define('LANGUAGES_DIR',PLUGIN_NAME.'/languages');
		define('INC_DIR','inc');
		define('IMG_DIR','assets/images');
		define('CSS_DIR','assets/css');
		define('JS_DIR','assets/js');
	}
	
	public function wl_ps_load_textdomain(){
		load_plugin_textdomain(PLUGIN_NAME,false,LANGUAGES_DIR);
	}
	
	public function wl_ps_set_includes(){
		$this->includes = array(
			'admin' => array(
				INC_DIR.'/page.php',
				INC_DIR.'/page-settings.php',
				INC_DIR.'/page-logs.php',
				INC_DIR.'/admin.php',
				INC_DIR.'/functions.php',
			),
			'fronted' => array()
		);
	}
	
	public function wl_ps_load_includes(){
		$includes = $this->includes;
		if($includes):
			foreach($includes as $condition => $files):
				switch($condition):
		
				case 'admin':
					if(is_admin()){
						foreach($files as $file){
							require_once $file;
						}
					}
				break;
		
				case 'fronted':
					foreach($files as $file){
						require_once $file;
					}
				break;
						
				default:
					foreach($files as $file){
						require_once $file;
					}
				break;	
		
				endswitch;
		
			endforeach;
		endif;
	}
	
}
new WL_PS();