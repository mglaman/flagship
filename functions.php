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
?>
