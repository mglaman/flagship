<?php
/**
 * A bunch of goodness.
 * 
 * @package Flagship
 * @since Flagship 0.1
 */
 
define(FLAGSHIP_DEBUG, TRUE);
 
//Define our directory paths
if( ! defined(FLAGSHIP_DIR_PATH) )
	define(FLAGSHIP_DIR_PATH, dirname(__FILE__));
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

class Flagship {
	
	//Arrays to hold theme options
	protected static $theme_options = array();
	protected static $theme_zones 	= array();
	
	
	//Arrays to hold base configuration.
	protected static $core_zones 	= array();
	
	//Various template variable storage, direct access for now.
	public static $current_zone = null;
	
	/**
	 * Grabs our theme variables from database, populates class variables.
	 */
	public static function launch_theme() {
		self::$theme_options = get_option('flagship');
		self::$core_zones = self::core_zones();
		self::check_theme_zones_variables();
	}
	
	/**
	 * Class function to retrieve variables for a zone.
	 */
	public static function zone_variables($zone, $variable) {
		self::check_theme_zones_variables();
		
		if( isset(self::$theme_zones[$zone][$variable]) || !empty(self::$theme_zones[$zone][$variable]) )
			return self::$theme_zones[$zone][$variable];
		return false;
	}
	
	/**
	 * Returns array of zones that belong to an area, by set weight
	 */
	public static function area_zone_list($area) {
		if( !isset(self::$core_zones) || empty(self::$core_zones))
			self::$core_zones = self::core_zones();
		
		$area_zone_array = array();
		$sort_column = array();
		
		foreach( self::$core_zones as $zone => $attr ) {
			if($attr['area'] == $area)
				$area_zone_array[$zone] = $attr;
		}
		
		foreach( $area_zone_array as $key => $value) {
			$sort_column[$key] = $value['weight'];
		}
		array_multisort($sort_column, SORT_ASC, $area_zone_array);
		
		return $area_zone_array;
	}

	/**
	 * Check if we have theme_options['zones'] else load core_zones 
	 */
	 protected static function check_theme_zones_variables() {
	 	if(isset(self::$theme_options['zones']) && !empty(self::$theme_options['zones']))
			self::$theme_zones = self::$theme_options['zones'];
		else
			self::$theme_zones = self::core_zones();
	 }
	 
	/**
	 * Core config for our zones and where they are assigned.
	 */
	protected static function core_zones() {
		#@TODO: Check if this data saved in options, else use these defaults. Allows better customization
		$zone_config = array(
			'top-bar-one' => array(
				'enabled'	=> true,
				'weight' 	=> -10,
				'area'	=> 'header',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '8',
				'left'		=> '',
				'right'		=> ''
			),
			'top-bar-two' => array(
				'enabled'	=> true,
				'weight' 	=> -10,
				'area'	=> 'header',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '8',
				'left'		=> '',
				'right'		=> ''
			),
			'branding' => array(
				'enabled'	=> true,
				'weight' 	=> 0,
				'area'	=> 'header',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '12',
				'left'		=> '2',
				'right'		=> '2'
			),
			'navigation' => array(
				'enabled'	=> true,
				'weight' 	=> 0,
				'area'	=> 'header',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '16',
				'left'		=> '',
				'right'		=> ''
			),
			'preface' => array(
				'enabled'	=> true,
				'weight' 	=> 10,
				'area'	=> 'header',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '16',
				'left'		=> '',
				'right'		=> ''
			),
			'breadcrumb' => array(
				'enabled'	=> true,
				'weight' 	=> -10,
				'area'	=> 'content',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '12',
				'left'		=> '4',
				'right'		=> ''
			),
			'content' => array(
				'enabled'	=> true,
				'weight' 	=> 0,
				'area'	=> 'content',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '8',
				'left'		=> '',
				'right'		=> ''
			),
			'sidebar-one' => array(
				'enabled'	=> true,
				'weight' 	=> -5,
				'area'	=> 'content',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '4',
				'left'		=> '',
				'right'		=> ''
			),
			'sidebar-two' => array(
				'enabled'	=> true,
				'weight' 	=> 10,
				'area'	=> 'content',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '3',
				'left'		=> '',
				'right'		=> ''
			),
			'postscript' => array(
				'enabled'	=> true,
				'weight' 	=> -10,
				'area'	=> 'footer',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '16',
				'left'		=> '',
				'right'		=> ''
			),
			'footer-one' => array(
				'enabled'	=> true,
				'weight' 	=> 0,
				'area'	=> 'footer',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '16',
				'left'		=> '',
				'right'		=> ''
			),
			'footer-two' => array(
				'enabled'	=> true,
				'weight' 	=> 10,
				'area'	=> 'footer',
				'wrapper'	=> false,
				'classes'	=> '',
				'columns'	=> '8',
				'left'		=> '8',
				'right'		=> ''
			),
			
			
		);
		return $zone_config;
	}
}



?>
