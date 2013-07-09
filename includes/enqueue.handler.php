<?php
/**
 * Static class and functions to manage wp_enqueue_styles and wp_enqueue_scripts!
 *
 * @package Flagship
 * @since Flagship 0.1
 */
 
class EnqueueHandler {
	protected static $styles;
	protected static $scripts;
	
	/**
	 * Sets class $styles as an array of framework's stylesheets
	 */
	protected static function gather_styles() {
		# @TODO: Instead of hardcoded array, possibly have as framework option to enable and disable.
		$flagship_stylesheets = array(
			'core.css' 			=> array(
				'handle' => 'flagship.core.styles',
				'location' => 'core'),
			'formalize.css' 	=> array(
				'handle' => 'formalize',
				'location' => 'core'),
			'typography.css'	=> array(
				'handle' => 'flagship.typography.styles',
				'location' => 'core'),
			'responsive.css'	=> array(
				'handle' => 'flagship.responsive.styles', 
				'location' => 'core')
		);
		# @TODO: Need to add weight to stylesheets, otherwise things could get messy.
		// To register child theme style, create a similar array like above, but specify location as 'child'
		// To register plugin styles, specify location as 'plugin' and add value 'path' and provide a path to your images folder.
		do_action_ref_array('flagship_add_enqueue_styles', array( &$flagship_stylesheets ));
		# @TODO: Sort stylesheet array by weight value.
		self::$styles = $flagship_stylesheets;
	}
	/**
	 * Sets class $scripts as an array of framework's JavaScripts
	 */
	protected static function gather_scripts() {
		$flagship_scripts = array(
	 		'flagship.functions.js' => array(
	 			'handle'	=> 'flagship.functions',
	 			'deps'		=> array('jquery'),
	 			'ver'		=> '0.1'
			),
			'jquery.formalize.min.js' => array(
				'handle'	=> 'jquery.formalize',
				'deps'		=> array('jquery'),
				'ver'		=> '1.2'
			),
			//WordPress doesn't handle script conditionals like stylesheets :(
			//'html5shiv.js' => array(
			//	'handle'	=> 'html5shiv',
			//	'deps'		=> null,
			//	'ver'		=> '1.0',
			//	'condition' => 'lt IE 9'
			//),
			
		);
		do_action_ref_array('flagship_add_enqueue_scripts', array( &$flagship_scripts));
		self::$scripts = $flagship_scripts;
	 }
	
	/**
	 * Enqueues framework styles
	 */
	public static function enqueue_styles() {
		global $wp_styles;
		
		//Check if we have our minified stylesheets
		if(file_exists(FLAGSHIP_DIR_PATH.'/css/flagship.min.css')) {
			wp_enqueue_style('flagship.min',  FLAGSHIP_CSS_PATH. '/flagship.min.css', false, null);
		} else {
			//Try to build minified stylsheets and enqueue them.
			if(self::build_minified_styles()) {
				wp_enqueue_style('flagship.min', FLAGSHIP_URL_PATH . '/flagship.min.css', false, null);
			} else {
				if(!isset(self::$styles) || empty(self::$styles))
					self::gather_styles();
				//We're having issues, load raw stylesheet files.
				foreach(self::$styles as $stylesheet => $attr) {
					$location = ($attr['location'] == 'core') ? FLAGSHIP_CSS_PATH : get_stylesheet_directory_uri().'/css';
					wp_enqueue_style($attr['handle'], $location . '/' . $stylesheet, false, null);
				}
			}
		}
		//We know about the ie.css
		# @TODO: How is this going to work in child theme...
		wp_enqueue_style('flagship.ie.styles', FLAGSHIP_CSS_PATH . '/ie.css', false, false);
		$wp_styles->add_data( 'flagship.ie.styles', 'conditional', 'lt IE 9' );
		
	}
	/**
	 * Enqueues framework scripts
	 */
	public static function enqueue_scripts() {
		global $wp_scripts;
		
		if(!isset(self::$scripts) || empty(self::$scripts))
			self::gather_scripts();
		
		foreach(self::$scripts as $javascripts => $args) {
			#@TODO: Fix up like styles, child theme cannot hook into this.
			wp_enqueue_script($args['handle'], FLAGSHIP_JS_PATH . '/' . $javascripts, $args['deps'], null);
			
			//If we have a conditional load.
			if(isset($args['condition']) && !empty($args['conditions']))
				$wp_scripts->add_data( $args['handle'], 'conditional', $args['condition'] );
				
		}
		# WordPress comment script.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	}
	/**
	 * Enqueues selected Google Font(s)
	 */
	public static function enqueue_fonts() {
		$theme_variables = Flagship::get_theme_variables();
		switch($theme_variables['google_font']) {
			case 'fauna-one':
				wp_enqueue_style('fauna-one-font', 'http://fonts.googleapis.com/css?family=Fauna+One');
				add_action('wp_head', create_function( '', 'echo "<style type=\"text/css\"> html,body { font-family: \"Fauna One\"</style> ". PHP_EOL;' ) );
			break;
			case 'noto-serif':
				wp_enqueue_style('noto-serif-font', 'http://fonts.googleapis.com/css?family=Noto+Serif:400,700,400italic');
				add_action('wp_head', create_function( '', 'echo "<style type=\"text/css\"> html,body { font-family: \"Noto Serif\"</style> ". PHP_EOL;' ) );
			break;
			case 'raleway':
				wp_enqueue_style('raleway-font', 'http://fonts.googleapis.com/css?family=Raleway:400,600');
				add_action('wp_head', create_function( '', 'echo "<style type=\"text/css\"> html,body { font-family: \"Raleway\"</style> ". PHP_EOL;' ) );
			break;
			case 'open-sans':
				wp_enqueue_style('google.opensans.font', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600');
				add_action('wp_head', create_function( '', 'echo "<style type=\"text/css\"> html,body { font-family: \"Open Sans\"</style> ". PHP_EOL;' ) );
			break;
		}
		#@TODO: create hook so user only needs to add to an array with ?family= ?
	}
	
	/**
	 * Enqueues our admin scripts
	 */
	public static function enqueue_admin() {
		wp_enqueue_style('admin', get_template_directory_uri() . '/css/admin.css', false, false, 'screen');
	}

	/**
	 * Combines the system stylsheets into one concatenated file, minified! Returns true if file exists or success, returns false on error.
	 * @TODO: Error management.
	 * @return
	 */
	protected static function build_minified_styles($force = false) {
		 if(FLAGSHIP_DEBUG === TRUE)
			 return false;
		
		//Backs out if we have minified CSS and not being overriden
		if(file_exists(FLAGSHIP_DIR_PATH.'/css/flagship.min.css') && $force == false)
			return true;
		if(!isset(self::$styles) || empty(self::$styles))
			self::gather_styles();
		
		$minified_css = null;
		foreach(self::$styles as $stylesheet => $args) {
			//Set current directory path to stylesheet
			$location = ($args['location'] == 'core') ? FLAGSHIP_DIR_PATH : get_stylesheet_directory();
			$current_stylesheet = $location.'/css/'.$stylesheet;
			
			//Just incase, verify files are CSS. Don't want any hijackers ruining our fun.
			$stylesheet_info = pathinfo($current_stylesheet);
			if($stylesheet_info['extension'] == 'css' && filesize($current_stylesheet) != false) {
				$handle = fopen($current_stylesheet, 'r');
					$buffered_css = fread($handle, filesize($current_stylesheet));
					if($args['location'] == 'child')
						$buffered_css = preg_replace("/\.\.(?=[^url(*?\)]*)/", self::child_theme_dir(), $buffered_css);
					//\.\.(?=[^url(*?\)]*)	new
					//\.\.(?=[^url(]*?\)) old
					elseif($args['location'] != 'core' && isset($args['path']) && !empty($args['path']))
						$buffered_css = preg_replace("/\.\.(?=[^url(]*?\))/", $args['path'], $buffered_css);
				$minified_css .= $buffered_css;
				fclose($handle);
			}
		}
		//Strip out line breaks.
		//All at once...testing.
		//$minified_css = preg_replace("/(\/\*.*?\*\/)|\s+(?![^\{\}]*\})|(\t|\r|\r\n|\n)/s", "", $minified_css);
		
		//Remove comments
		$minified_css = preg_replace("/(\/\*.*?\*\/)/s", "", $minified_css);
		//Remove tabs, lime breaks.
		$minified_css = str_replace(array("\r\n", "\r", "\n", "\t"), '', $minified_css);
		//Remove spaces after colons and commas and before brackets. 
		$minified_css = str_replace(array(': ', ', ', ' {'), array(':',',', '{'), $minified_css);
		//Uhh
		$minified_css = preg_replace("/(?![^\{\}]*\})/x", "", $minified_css);
		
		//Save it.
		$write_handle = fopen(FLAGSHIP_DIR_PATH.'/css/flagship.min.css', 'w+');
		if(!fwrite($write_handle, $minified_css))
			return false; //Uh oh couldn't save :(
		fclose($write_handle);
		header('Location: '. get_home_url());
		//return true;
	}
	protected static function child_theme_dir() {
		$stylesheet_directory = get_stylesheet_directory_uri();
		$home_url = home_url();
		$relative_url_replace = str_replace($home_url, '', $stylesheet_directory);
		return $relative_url_replace;
	}


	/**
	 * Public access to build styles.
	 * @TODO: Build in capabilities, must be able to enable plugins or themes.
	 */
	public static function minify_framework_styles() {
		return self::build_minified_styles();
	}
	public static function rebuild_framework_styles() {
		return self::build_minified_styles(true);
	}
}


## Functions to make accessing class a little prettier.

/**
 * Runs the framework's stylesheet minifier, returns false if minified version exists.
 *
 * @return boolean
 */
function flagship_minify_stylesheets() {
	return EnqueueHandler::minify_framework_styles();
}

/**
 * Forces the framework's stylesheet minifier to rebuild stylesheets.
 * 
 * @return boolean
 */
function flagship_rebuild_minify_stylesheets() {
	return EnqueueHandler::rebuild_framework_styles();
}

## add_action and add_filter hooks
add_action( 'wp_enqueue_scripts', array('EnqueueHandler', 'enqueue_styles') );
add_action( 'wp_enqueue_scripts', array('EnqueueHandler', 'enqueue_scripts') );
add_action( 'wp_enqueue_scripts', array('EnqueueHandler', 'enqueue_fonts') );
add_action( 'admin_enqueue_scripts', array('EnqueueHandler', 'enqueue_admin'));
?>