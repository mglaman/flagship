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
			'core.css' 			=> 'flagship.core.styles',
			'formalize.css' 	=> 'formalize',
			'typography.css'	=> 'flagship.typography.styles',
			'responsive.css'	=> 'flagship.responsive.styles'
		);
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
			//'html5shiv.js' => array(
			//	'handle'	=> 'html5shiv',
			//	'deps'		=> null,
			//	'ver'		=> '1.0',
			//	'condition' => 'lt IE 9'
			//),
			
		);
		self::$scripts = $flagship_scripts;
	 }
	
	/**
	 * Enqueues framework styles
	 */
	public static function enqueue_styles() {
		global $wp_styles;
		
		//Check if we have our minified stylesheets
		if(file_exists(FLAGSHIP_DIR_PATH.'/css/flagship.min.css')) {
			wp_enqueue_style('/css/flagship.min',  FLAGSHIP_CSS_PATH. '/flagship.min.css', false, false);
		} else {
			//Try to build minified stylsheets and enqueue them.
			if(self::build_minified_styles()) {
				wp_enqueue_style('/css/flagship.min', FLAGSHIP_URL_PATH . '/flagship.min.css', false, false);
			} else {
				if(!isset(self::$styles) || empty(self::$styles))
					self::gather_styles();
				//We're having issues, load raw stylesheet files.
				foreach(self::$styles as $stylesheet => $handle) {
					wp_enqueue_style($handle, FLAGSHIP_CSS_PATH . '/' . $stylesheet, false, false);
				}
			}
		}
		//We know about the ie.css
		# @TODO: How is this going to work in child theme...
		wp_enqueue_style('flagship.ie.styles', FLAGSHIP_CSS_PATH . '/ie.css', false, false);
		$wp_styles->add_data( 'flagship.ie.styles', 'conditional', 'lt IE 9' );
		
	}

	public static function enqueue_scripts() {
		global $wp_scripts;
		
		if(!isset(self::$scripts) || empty(self::$scripts))
			self::gather_scripts();
		
		foreach(self::$scripts as $javascripts => $args) {
			wp_enqueue_script($args['handle'], FLAGSHIP_JS_PATH . '/' . $javascripts, $args['deps'], $args['ver']);
			
			//If we have a conditional load.
			if(isset($args['condition']) && !empty($args['conditions']))
				$wp_scripts->add_data( $args['handle'], 'conditional', $args['condition'] );
				
		}
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
			$current_stylesheet = FLAGSHIP_DIR_PATH.'/css/'.$stylesheet;
			
			//Just incase, verify files are CSS. Don't want any hijackers ruining our fun.
			$stylesheet_info = pathinfo($current_stylesheet);
			if($stylesheet_info['extension'] == 'css' && filesize($current_stylesheet) != false) {
				$handle = fopen($current_stylesheet, 'r');
				$minified_css .= "/* --- {$stylesheet} --- */";
				$minified_css .= fread($handle, filesize($current_stylesheet));
				fclose($handle);
			}
		}
		//Strip out line breaks.
		$minified_css = str_replace(array("\r\n", "\r", "\n"), '', $minified_css);
		//Save it.
		$write_handle = fopen(FLAGSHIP_DIR_PATH.'/css/flagship.min.css', 'w+');
		if(!fwrite($write_handle, $minified_css))
			return false; //Uh oh couldn't save :(
		fclose($write_handle);
		
		return true;
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
?>