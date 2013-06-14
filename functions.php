<?php
/**
 * A bunch of goodness.
 * 
 * @package Flagship
 * @since Flagship 0.1
 */
 
//Define our directory paths
if( ! defined(FLAGSHIP_DIR_PATH) )
	define(FLAGSHIP_DIR_PATH, dirname(__FILE__));
if( ! defined(FLAGSHIP_INC_PATH) )
	define(FLAGSHIP_INC_PATH, FLAGSHIP_DIR_PATH . '/includes');
if( ! defined(FLAGSHIP_TPL_PATH) )
	define(FLAGSHIP_TPL_PATH, FLAGSHIP_DIR_PATH . '/templates');

//Define our URL paths
if( ! defined(FLAGSHIP_URL_PATH) )
	define(FLAGSHIP_URL_PATH,  plugins_url('', __FILE__));
if( ! defined(FLAGSHIP_CSS_PATH) )
	define(FLAGSHIP_CSS_PATH, FLAGSHIP_URL_PATH . 'css');
if( ! defined(FLAGSHIP_JS_PATH) )
	define(FLAGSHIP_JS_PATH, FLAGSHIP_URL_PATH . 'js');

require(FLAGSHIP_INC_PATH . '/template.handler.php');

class Flagship {
	protected static $theme_options = array();
	protected static $theme_zones 	= array();
	
	/**
	 * Grabs our theme variables from database, populates class variables.
	 */
	public static function launch_theme() {
		self::$theme_options = get_option('flagship');
		self::$theme_zones = self::$theme_options['zones'];
	}
	
	/**
	 * Class function to retrieve variables for a zone.
	 */
	public static function zone_variables($zone, $variable) {
		if( isset(self::$theme_zones[$zone][$variable]) && !empty(self::$theme_zones[$zone][$variable]) )
			return self::$theme_zones[$zone][$variable];
		return false;
	}
}



?>
