<?php
/**
 * A bunch of goodness.
 * 
 * @package Flagship
 * @since Flagship 0.2
 */

//Load default theme hooks.
#@todo: Find cleaner way to do this. If hooks file added to system in parent theme, child theme cannot remove_action as they have not yet been called.
require(get_template_directory() . '/includes/theme.hooks.php');

/**
 * Theme functions
 * - Organize your child theme by placing theme specific functions in this section
 */


/**
 * Flagship hooks
 * - Organize your child theme by modifying the Flagship Framework hooks in this section
 */
add_action('flagship_add_enqueue_styles', 'flagship_child_enqueue_styles', 10, 1);

function flagship_child_enqueue_styles( &$styles_array ) {
	
	$styles_array['child.css'] = array(
		'handle' => 'child-theme',
		'location' => 'child'
	);
	return $styles_array;
}

/**
 * WordPress hooks
 */
?>