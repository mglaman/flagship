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
add_action('mod_rewrite_rules', array('Flagship', 'modify_rewrite_rules'));
add_action( 'wp_head', array('Flagship', 'hook_wp_head'));
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
		
		add_filter('the_generator', create_function( '', 'return "";' ) );
		
		//Adds first, last, middle classes to navigation menus along with other tweaks.
		add_filter('wp_nav_menu_objects', array('Flagship','modify_menus'));
		
		//Load our widgets
		#@TODO: Enable/Disable through settings.
		require(FLAGSHIP_DIR_PATH.'/widgets/FS_Child_Pages.php');
	}

	/**
	 * Allows theme to dump stuff into wp_head
	 */
	public static function hook_wp_head() {
		
		echo '<!-- Flagship Theme Framework -->'. PHP_EOL;
		if(isset(self::$theme_options['seo']['google_verify']) && !empty(self::$theme_options['seo']['google_verify']))
			echo '<meta name="google-site-verification" content="'.self::$theme_options['seo']['google_verify'].'">'. PHP_EOL;
		if(isset(self::$theme_options['seo']['bing_verify']) && !empty(self::$theme_options['seo']['bing_verify']))
			echo '<meta name="msvalidate.01" content="'.self::$theme_options['seo']['bing_verify'].'">' . PHP_EOL;
		if(isset(self::$theme_options['seo']['google_plus']) && !empty(self::$theme_options['seo']['google_plus']))
			echo '<link rel="publisher" href="'.self::$theme_options['seo']['google_plus'].'">' . PHP_EOL;
		if(isset(self::$theme_options['seo']['force_www']) && !empty(self::$theme_options['seo']['force_www']))
			echo '<link rel="canonical" href="http://'.self::$theme_options['seo']['force_www'].'">' . PHP_EOL;
		echo '<!-- End Flagship Theme Framework -->' .PHP_EOL;
	}
	
	public static function modify_menus($items) {
		$home_id = get_option('page_for_posts');
		
	    $items[1]->classes[] = 'first';
	    $items[count($items)]->classes[] = 'last';
		
		// foreach($items as &	$post_item) {
			// if($post_item->object_id == $home_id && ( 'post' == get_post_type() || is_category() ))
				// $post_item->classes[] = 'current-page-ancestor';
		// }
		
	    return $items;
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
	 	/*$force_preferred = 
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
		echo $new_rules = $force_preferred . $rules;*/
		
		$flagship_rewrite_rules = '
# This .htaccess file is used to speed up this website
# See https://github.com/sergeychernyshev/.htaccess


# ----------------------------------------------------------------------
# Proper MIME type for all files
#
# Copied from the HTML5 boilerplate project\'s .htaccess file
# https://github.com/h5bp/html5-boilerplate/blob/master/.htaccess
# ----------------------------------------------------------------------

# JavaScript
#   Normalize to standard type (it\'s sniffed in IE anyways)
#   tools.ietf.org/html/rfc4329#section-7.2
AddType	application/javascript			js jsonp
AddType	application/json			json

# Audio
AddType	audio/ogg				oga ogg
AddType	audio/mp4				m4a f4a f4b

# Video
AddType	video/ogg				ogv
AddType	video/mp4				mp4 m4v f4v f4p
AddType	video/webm				webm
AddType	video/x-flv				flv

# SVG
#   Required for svg webfonts on iPad
#   twitter.com/FontSquirrel/status/14855840545
AddType		image/svg+xml			svg svgz
AddEncoding	gzip				svgz

# Webfonts
AddType application/vnd.ms-fontobject		eot
AddType application/x-font-ttf			ttf ttc
AddType font/opentype				otf
AddType application/x-font-woff			woff

# Assorted types
AddType	image/x-icon				ico
AddType	image/webp				webp
AddType	text/cache-manifest			appcache manifest
AddType	text/x-component			htc
AddType	application/xml				rss atom xml rdf
AddType	application/x-chrome-extension		crx
AddType	application/x-opera-extension		oex
AddType	application/x-xpinstall			xpi
AddType	application/octet-stream		safariextz
AddType	application/x-web-app-manifest+json	webapp
AddType	text/x-vcard				vcf
AddType	application/x-shockwave-flash		swf
AddType	text/vtt				vtt

# --------------------------------------------------------------------------------------
# Compression: http://code.google.com/speed/page-speed/docs/payload.html#GzipCompression
# --------------------------------------------------------------------------------------
<IfModule mod_deflate.c>
	AddOutputFilterByType DEFLATE application/atom+xml
	AddOutputFilterByType DEFLATE application/json
	AddOutputFilterByType DEFLATE application/xhtml+xml
	AddOutputFilterByType DEFLATE application/xml
	AddOutputFilterByType DEFLATE text/css
	AddOutputFilterByType DEFLATE text/html
	AddOutputFilterByType DEFLATE text/plain
	AddOutputFilterByType DEFLATE text/x-component
	AddOutputFilterByType DEFLATE text/xml

	# The following MIME types are in the process of registration
	AddOutputFilterByType DEFLATE application/xslt+xml
	AddOutputFilterByType DEFLATE image/svg+xml

	# The following MIME types are NOT registered
	AddOutputFilterByType DEFLATE application/mathml+xml
	AddOutputFilterByType DEFLATE application/rss+xml

	# JavaScript has various MIME types
	AddOutputFilterByType DEFLATE application/javascript
	AddOutputFilterByType DEFLATE application/x-javascript
	AddOutputFilterByType DEFLATE text/ecmascript
	AddOutputFilterByType DEFLATE text/javascript

	# .ico files and other compressible images
	AddOutputFilterByType DEFLATE image/vnd.microsoft.icon
	AddOutputFilterByType DEFLATE image/x-icon
	AddOutputFilterByType DEFLATE image/bmp
	AddOutputFilterByType DEFLATE image/tiff
	AddOutputFilterByType DEFLATE application/pdf

	# compressible fonts (.woff is already compressed)
	AddOutputFilterByType DEFLATE font/opentype
	AddOutputFilterByType DEFLATE application/x-font-ttf
	AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
</IfModule>

# ----------------------------------------------------------------------
# Enabling filename rewriting (file.XXX.ext) if URL rewriting is enabled
# Otherwise URLs will use query strings (file.ext?v=XXX)
#
# More proxies cache assets if there is no query string
# ----------------------------------------------------------------------
<IfModule mod_rewrite.c>
	RewriteEngine On

	# Setting up an environment variable so your code can detect if mod_rewrite rules are executable
	# in this folder and you can use file.123.jpg or you need to fall back to file.jpg?123
	RewriteRule .					-	[E=URLVERSIONREWRITE:YES]

	# Rewrites a version in file.123.jpg as well as timestamped version file.123_m_12345123512354.jpg
	# to original file.jpg so you can use it instead of file.jpg?123 which isn\'t cached in some proxies.
	RewriteCond %{REQUEST_FILENAME}			!-f
	RewriteRule ^(.*)\.(\d+)(_m_\d+)?\.([^\.]+)$	$1.$4	[L,QSA]

	# Rewrites a version in file.ac123fe.jpg to original file.jpg
	# so you can use it instead of file.jpg?123 which isn\'t cached in some proxies.
	# Used for hash-based URLs where having a timestamp is not necessary.
	RewriteCond %{REQUEST_FILENAME}			!-f
	RewriteRule ^(.*)\.([a-z\d]+)\.([^\.]+)$	$1.$3	[L,QSA]
</IfModule>

# -------------------------------------------------------------------------------------------------
# Browser Caching: http://code.google.com/speed/page-speed/docs/caching.html#LeverageBrowserCaching
#
# Google recommends specifying the following for all cacheable resources:
#
#    1. Expires or Cache-Control max-age
#
# 	Set Expires to a minimum of one month, and preferably up to one year, in the future. (We
# 	prefer Expires over Cache-Control: max-age because it is is more widely supported.) Do not
# 	set it to more than one year in the future, as that violates the RFC guidelines.
#
#    2. Last-Modified or ETag
#
# 	Set the Last-Modified date to the last time the resource was changed. If the Last-Modified
#	date is sufficiently far enough in the past, chances are the browser won\'t refetch it. 
#
# Per Google: "it is redundant to specify both Expires and Cache-Control: max-age, or to specify
# both Last-Modified and ETag."
# --------------------------------------------------------------------------------------------------
<IfModule mod_expires.c>
	ExpiresActive On

	ExpiresByType application/json			"access plus 1 year"
	ExpiresByType application/pdf			"access plus 1 year"
	ExpiresByType application/x-shockwave-flash	"access plus 1 year"
	ExpiresByType image/bmp 			"access plus 1 year"
	ExpiresByType image/gif 			"access plus 1 year"
	ExpiresByType image/jpeg 			"access plus 1 year"
	ExpiresByType image/png 			"access plus 1 year"
	ExpiresByType image/svg+xml 			"access plus 1 year"
	ExpiresByType image/tiff 			"access plus 1 year"
	ExpiresByType image/vnd.microsoft.icon 		"access plus 1 year"
  	ExpiresByType image/x-icon			"access plus 1 year"
	ExpiresByType text/css 				"access plus 1 year"
	ExpiresByType video/x-flv 			"access plus 1 year"
	ExpiresByType application/vnd.bw-fontobject	"access plus 1 year"
	ExpiresByType application/x-font-ttf		"access plus 1 year"
	ExpiresByType application/font-woff		"access plus 1 year"
	ExpiresByType font/opentype			"access plus 1 year"
	ExpiresByType image/webp			"access plus 1 year"

	# The following MIME types are in the process of registration
	ExpiresByType application/xslt+xml		"access plus 1 year"
	ExpiresByType image/svg+xml			"access plus 1 year"

	# The following MIME types are NOT registered
	ExpiresByType application/mathml+xml		"access plus 1 year"
	ExpiresByType application/rss+xml		"access plus 1 year"

	# JavaScript has various MIME types
	ExpiresByType application/x-javascript 		"access plus 1 year"
	ExpiresByType application/javascript 		"access plus 1 year"
	ExpiresByType text/ecmascript 			"access plus 1 year"
	ExpiresByType text/javascript 			"access plus 1 year"
</IfModule>

# TODO: Set Last-Modified per Google\'s recommendation to complete browser caching

# -------------------------------------------------------------------------
# Disabling ETags as they are most likely misconfigured and
# do not add functionalit beyond Last-Modified
# -------------------------------------------------------------------------
<IfModule mod_headers.c>
	# Try removing etag headers (if it\'s coming from proxy for example)
	Header unset ETag
</IfModule>

# Remove ETags
FileETag None
		';
		$new_rules = $flagship_rewrite_rules . $rules;
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
		  
		  $args = array(
		    'id' => 'navigation',
		    'title' => 'Navigation',
		    'href' => admin_url('themes.php?page=fs-navigation'),
		    'meta' => array('class' => 'flagship-navigation'),
			'parent' => 'site-name',
		  );
		
		  $wp_admin_bar->add_node($args);
		}
}

?>
