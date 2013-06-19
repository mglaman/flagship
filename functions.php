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
require(FLAGSHIP_INC_PATH . '/widgets.handler.php');
require(FLAGSHIP_INC_PATH . '/zones.handler.php');

add_action( 'after_setup_theme', array('Flagship', 'launch_theme') );
add_action( 'admin_menu', array('Flagship', 'create_framework_menu_page'));
add_action( 'flagship_export_json', array('Flagship', 'export_theme_config'));
if( !is_admin() ) {
	add_action( 'admin_bar_menu', array('FLagship', 'toolbar_zones_menu_item'), 999 );
}

class Flagship {
	
	//Arrays to hold theme options
	protected static $theme_options = array();
	public static $theme_zones 	= array();
	public static $theme_areas 	= array();
	
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
		
		//Register our navigation menu
		register_nav_menu( 'primary', 'Main Navigation' );
		
		//Adds our canonical htaccess rule.
		if(isset(self::$theme_options['seo']['force_www']) && !empty(self::$theme_options['seo']['force_www'])) {
			# @TODO: Fix this. Throwing 500
			//add_action('mod_rewrite_rules', array('Flagship', 'modify_rewrite_rules'));
		}
		
		//Loads our widget extender plugin.
		WidgetsHandler::initialize();
		
		//Theme support
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' ); #@TODO: Include by default, add option to disable
		add_theme_support( 'post-formats', array( #@TODO: Enable/Disable from settings
			'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
		) );
		
		if(is_user_logged_in() && current_user_can('manage_options') && isset($_GET['fs-rebuild'])) {
			flagship_rebuild_minify_stylesheets();
		}
		
	}
	
	public static function get_theme_variables($refresh = false) {
		if($refresh)
			self::launch_theme();
		return self::$theme_options;
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
			if($attr['area'] == $area && !empty($attr['enabled']))
				$area_zone_array[$zone] = $attr;
		}
		
		foreach( $area_zone_array as $key => $value) {
			$sort_column[$key] = $value['weight'];
		}
		array_multisort($sort_column, SORT_ASC, $area_zone_array);
		
		return $area_zone_array;
	}
	public static function disabled_zone_list($area) {
		$area_zone_array = array();
		foreach( self::$theme_zones as $zone => $attr) {
			if($attr['area'] == $area && empty($attr['enabled']))
				$area_zone_array[$zone] = $attr;
		}
		return $area_zone_array;
	}
	
	protected static function load_theme_variables() {
		//@NOTE: Commenting out below forces theme to load config.json versus database values, which in turn saves new config!
		# @TODO: Create option to force refresh from config.json
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
	
	public static function update_flagship_options($theme_variables) {
		update_option('flagship', $theme_variables);
		Flagship::get_theme_variables(true);
	}
	
	/**
	 * Tells WordPress to add our theme's menu items to the dashboard
	 */
	public static function create_framework_menu_page() {
		add_menu_page('Dashboard', 'Flagship', 'edit_theme_options', 'flagship', array('Flagship', 'get_admin_dashboard_page'), get_template_directory_uri().'/images/menu-icon-dashboard.png', '61');
			add_submenu_page('flagship', 'Settings', 'Advanced Settings', 'edit_theme_options', 'fs-settings', array('Flagship', 'get_admin_settings_page'));
			add_submenu_page('flagship', 'Theme Building', 'Theme Building', 'manage_options', 'fs-theme-building', array('Flagship', 'get_admin_theme_building_page'));
			
		add_theme_page('Flagship Zones', 'Zones', 'edit_theme_options', 'fs-zones', array('Flagship', 'get_admin_zones_page'));
		add_theme_page('Flagship Navigation', 'Navigation', 'edit_theme_options', 'fs-navigation', array('Flagship', 'get_admin_nav_page'));
	}
	public static function get_admin_dashboard_page() {
		require(FLAGSHIP_INC_PATH.'/admin/dashboard.php');
	}
	public static function get_admin_theme_building_page() {
		require(FLAGSHIP_INC_PATH.'/admin/theme-building.php');
	}
	public static function get_admin_settings_page() {
		require(FLAGSHIP_INC_PATH.'/admin/advanced.settings.php');
	}
	public static function get_admin_zones_page() {
		require(FLAGSHIP_INC_PATH.'/admin/zones.php');
	}
	public static function get_admin_nav_page() {
		require(FLAGSHIP_INC_PATH.'/admin/navigation.php');
	}

	/**
	 * Helper for disabling default widgets, just an array
	 */
	public static function default_wordpress_widgets() {
		$default_widgets = array(
			'WP_Widget_Pages'                   => 'Pages Widget',
			'WP_Widget_Calendar'                => 'Calendar Widget',
			'WP_Widget_Archives'                => 'Archives Widget',
			'WP_Widget_Links'                   => 'Links Widget',
			'WP_Widget_Meta'                    => 'Meta Widget',
			'WP_Widget_Search'                  => 'Search Widget',
			'WP_Widget_Text'                    => 'Text Widget',
			'WP_Widget_Categories'              => 'Categories Widget',
			'WP_Widget_Recent_Posts'            => 'Recent Posts Widget',
			'WP_Widget_Recent_Comments'         => 'Recent Comments Widget',
			'WP_Widget_RSS'                     => 'RSS Widget',
			'WP_Widget_Tag_Cloud'               => 'Tag Cloud Widget',
			'WP_Nav_Menu_Widget'                => 'Menus Widget'
		);
		return $default_widgets;
	}
	
	/**
	 * Hooks into rewrite rules.
	 */
	 public static function modify_rewrite_rules( $rules ) {
	 	$force_preferred = 
	 	"<IfModule mod_rewrite.c>
			RewriteEngine On";
	 	if(self::$theme_options['seo']['force_www'] == "true") {
	 		$force_preferred .= "RewriteCond %{HTTP_HOST} !^www\.
								RewriteRule ^(.*)$ http://www.%{HTTP_HOST}/$1 [R=301,L]";
	 	} else {
	 		$force_preferred .= "RewriteCond %{HTTP_HOST} !^%{HTTP_HOST}$ [NC]
								RewriteRule ^(.*)$ http://%{HTTP_HOST}/$1 [R=301,L]";
	 	}
		$force_preferred .= 
		"</IfModule>";
		echo $new_rules = $force_preferred . $rules;
		
		return $new_rules;
	 }
	 
	 public static function toolbar_zones_menu_item( $wp_admin_bar ) {
		  $args = array(
		    'id' => 'zones',
		    'title' => 'Zones',
		    'href' => admin_url('themes.php?page=fs-zones'),
		    'meta' => array('class' => 'flagship-zones'),
			'parent' => 'site-name',
		  );
		
		  $wp_admin_bar->add_node($args);
		}
}

?>
