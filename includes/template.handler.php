<?php
/**
 * Handler to build out template functions.
 *
 * @package Flagship
 * @since Flagship 0.1
 */
 

/** Area Functions **/ 
function flagship_area_template($section) {
	get_template_part('templates/areas/area', $section);
}

function flagship_header_zones() {
	return Flagship::area_zone_list('header');
}
function flagship_content_zones() {
	return Flagship::area_zone_list('content');
}
function flagship_footer_zones() {
	return Flagship::area_zone_list('footer');
}


/** Zone Functions **/
function flagship_zone_template($zone) {
	if(!ZoneHandler::zone_variables($zone, 'enabled'))
		return false;
	
	Flagship::$current_zone = $zone;
	get_template_part('templates/zones/zone', $zone);
}
	function flagship_zone_attribtutes() {
		ZoneHandler::get_attributes();
	}

function flagship_zone_extra_classes() {
	return ZoneHandler::zone_variables(Flagship::$current_zone, 'classes');
}
function flagship_zone_columns() {
	return ZoneHandler::zone_variables(Flagship::$current_zone, 'columns');
}
function flagship_zone_left() {
	return ZoneHandler::zone_variables(Flagship::$current_zone, 'left');
}
function flagship_zone_right() {
	return ZoneHandler::zone_variables(Flagship::$current_zone, 'right');
}	
function flagship_zone_start_wrapper() {
	$zone = Flagship::$current_zone;
	if( ZoneHandler::zone_variables($zone, 'wrapper') ) : ?>
		<div id="<?php echo $zone ?>-wrapper" class="zone-wrappper">
	<?php endif;
}
function flagship_zone_end_wrapper() {
	$zone = Flagship::$current_zone;
	if( ZoneHandler::zone_variables($zone, 'wrapper') ) : ?>
	</div>
	<?php endif;
}

function flagship_zone_widgets() {
	dynamic_sidebar( Flagship::$current_zone );
}
 
/** Misc Functions **/

function flagship_post_class() {
	if(has_post_thumbnail()) :
		 post_class('featured-image'); 
	else: 
		post_class();
	endif;
}

function flagship_link_pages() {
	$args = array(
		'before'           => '<div class="pagination">' . __('Pages:'),
		'after'            => '</div>',
		'link_before'      => '<span class="page-link">',
		'link_after'       => '</span>',
		'next_or_number'   => 'number', #@TODO: Set this to be fined within settings
		'nextpagelink'     => __('Next page'),
		'previouspagelink' => __('Previous page'),
	);
	wp_link_pages($args);
}

function flagship_primary_navigation() {
	$theme_variables = Flagship::get_theme_variables();
	$inline = $theme_variables['navigation']['type'];
	
	$defaults = array(
		'theme_location'  => 'primary',
		'container'=> 'false',
		'menu_class'=> ($inline == 'horizontal') ? 'primary menu inline' : 'primary menu', 
		'menu_id'=> 'primary-menu',
		'echo'=> true
	);
	do_action('flagship_alter_navigation_args', $defaults);
	wp_nav_menu( $defaults );
}

/**
 * Flagship will not utilize typical use of archive.php for archive pages. We need to let zone-content know what loop to load.
 */
function flagship_current_view() {
	if( is_home() ) {
		return 'blog';
	}
	elseif( is_archive() ) {
		#@TODO: Check if loop-archive-name exists, set template as that file. Else fallback to this
		return 'archive';
	}
	elseif( is_author() ) {
		return 'author';
	}
	elseif( is_category() ) {
		#@TODO: Check if loop-category-name exists, set template as that file. Else fallback to this
		return 'category';
	}
	elseif( is_attachment() ) {
		return 'attachment';
	}
	elseif( is_page() ) {
		return 'page';
	}
	elseif( is_single() ) {
		return 'single';
	}
	elseif( is_tag() ) {
		#@TODO: Check if loop-tag-name exists, set template as that file. Else fallback to this
		return 'tag';
	}
	elseif( is_tax() ) {
		#@TODO: Check if loop-taxonomy-name exists, set template as that file. Else fallback to this
		return 'taxonomy';
	}
	elseif( is_404() ) {
		return '404';
	}
	else {
		return 'index';
	}
}

?>