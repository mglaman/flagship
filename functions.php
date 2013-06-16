<?php
/**
 * A bunch of goodness.
 * 
 * @package Flagship
 * @since Flagship 0.1
 */
 
//define(FLAGSHIP_DEBUG, TRUE);
 
//Define our directory paths
if( ! defined(FLAGSHIP_DIR_PATH) )
	define(FLAGSHIP_DIR_PATH, get_template_directory());
if( ! defined(FLAGSHIP_INC_PATH) )
	define(FLAGSHIP_INC_PATH, FLAGSHIP_DIR_PATH . '/includes');
if( ! defined(FLAGSHIP_TPL_PATH) )
	define(FLAGSHIP_TPL_PATH, FLAGSHIP_DIR_PATH . '/templates');

//Define our URL paths
if( ! defined(FLAGSHIP_URL_PATH) )
	define(FLAGSHIP_URL_PATH,  get_template_directory_uri());
if( ! defined(FLAGSHIP_CSS_PATH) )
	define(FLAGSHIP_CSS_PATH, FLAGSHIP_URL_PATH . '/css');
if( ! defined(FLAGSHIP_JS_PATH) )
	define(FLAGSHIP_JS_PATH, FLAGSHIP_URL_PATH . '/js');

require(FLAGSHIP_INC_PATH . '/template.handler.php');
require(FLAGSHIP_INC_PATH . '/enqueue.handler.php');

add_action( 'after_setup_theme', array('Flagship', 'launch_theme') );
add_action( 'admin_menu', array('Flagship', 'create_framework_menu_page'));

class Flagship {
	
	//Arrays to hold theme options
	protected static $theme_options = array();
	protected static $theme_zones 	= array();
	protected static $theme_areas 	= array();
	
	//Various template variable storage, direct access for now.
	public static $current_zone = null;
	
	//Public info on current theme setup.
	public static $config_source = 'core';
	
	/**
	 * Grabs our theme variables from database, populates class variables.
	 */
	public static function launch_theme() {
		self::load_theme_variables();
		self::$theme_areas = self::$theme_options['areas'];
		self::$theme_zones = self::$theme_options['zones'];
	}
	
	/**
	 * Class function to retrieve variables for a zone.
	 */
	public static function zone_variables($zone, $variable) {
		
		if( isset(self::$theme_zones[$zone][$variable]) || !empty(self::$theme_zones[$zone][$variable]) )
			return self::$theme_zones[$zone][$variable];
		return false;
	}
	
	/**
	 * Gets list of areas in config
	 */
	public static function config_area_list() {
		return self::$theme_areas;
	}
	
	/**
	 * Returns array of zones that belong to an area, by set weight
	 */
	public static function area_zone_list($area) {
		$area_zone_array = array();
		$sort_column = array();
		
		foreach( self::$theme_zones as $zone => $attr ) {
			if($attr['area'] == $area)
				$area_zone_array[$zone] = $attr;
		}
		
		foreach( $area_zone_array as $key => $value) {
			$sort_column[$key] = $value['weight'];
		}
		array_multisort($sort_column, SORT_ASC, $area_zone_array);
		
		return $area_zone_array;
	}
	
	protected static function load_theme_variables() {
		//@NOTE: Commenting out below forces theme to load config.json versus database values, which in turn saves new config!
		$theme_variables = get_option('flagship');
		
		//Lets make sure we didn't get an empty response.
		if(empty($theme_variables)) {
			$config_path = get_template_directory() . '/config.core.json';
			
			//If the child theme has a config, load that instead.
			if(file_exists(get_stylesheet_directory() . '/config.json') && filesize(get_stylesheet_directory() . '/config.json') > 0) {
				$config_path = get_stylesheet_directory() . '/config.json';
				self::$config_source = 'child';	
			}
			
			//Lets open up that config and build up our zones!
			$config_handler = fopen($config_path, 'r');
			$config_data = fread($config_handler, filesize($config_path));
			fclose($config_handler);
			$theme_variables = json_decode($config_data, true);

			//Save 'em.
			update_option('flagship', $theme_variables);
		}
		//Use 'em
		self::$theme_options = $theme_variables;
	} 
	
	/**
	 * Tells WordPress to add our theme's menu items to the dashboard
	 */
	public static function create_framework_menu_page() {
		add_menu_page('Dashboard', 'Flagship', 'manage_options', 'flagship', array('Flagship', 'get_admin_dashboard_page'), get_template_directory_uri().'/images/menu-icon-dashboard.png', '61');
		add_submenu_page('flagship', 'Theme Building', 'Theme Building', 'manage_options', 'theme-building', array('Flagship', 'get_admin_theme_building_page'));
	}
	public static function get_admin_dashboard_page() {
		require(FLAGSHIP_INC_PATH.'/admin/dashboard.php');
	}
	public static function get_admin_theme_building_page() {
		require(FLAGSHIP_INC_PATH.'/admin/theme-building.php');
	}
}



?>
