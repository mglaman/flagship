<?php
/**
 * A bunch of goodness.
 * 
 * @package Flagship
 * @since Flagship 0.1
 */
 
add_action('flagship_add_enqueue_styles', 'flagship_child_enqueue_styles', 10, 1);

function flagship_child_enqueue_styles( &$styles_array ) {
	
	$styles_array['child.css'] = array(
		'handle' => 'child-theme',
		'location' => 'child'
	);
	return $styles_array;
}
?>