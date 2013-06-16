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
	#@TODO: if(Flagship::zone_variables($zone, 'is_active'))
	Flagship::$current_zone = $zone;
	get_template_part('templates/zones/zone', $zone);
}
	function flagship_zone_attribtutes() {
		$zone = Flagship::$current_zone;
		$columns = flagship_zone_columns();
		$classes = (flagship_zone_extra_classes() 	!= '') ? flagship_zone_extra_classes() : '';
		$left	 = (flagship_zone_left() != '0' && flagship_zone_left() != '') ? 'left-' . flagship_zone_left() : '';
		$right	 = (flagship_zone_right() != '0' && flagship_zone_right() != '') ? 'right-' . flagship_zone_right() : '';
		# @TODO: I don't like the extra empty whitespace. Make if/else printf
		printf('id="%1$s" class="zone %1$s columns-%2$s %3$s %4$s %5$s"', $zone, $columns, $left, $right, $classes);
	}

function flagship_zone_extra_classes() {
	return Flagship::zone_variables(Flagship::$current_zone, 'classes');
}
function flagship_zone_columns() {
	return Flagship::zone_variables(Flagship::$current_zone, 'columns');
}
function flagship_zone_left() {
	return Flagship::zone_variables(Flagship::$current_zone, 'left');
}
function flagship_zone_right() {
	return Flagship::zone_variables(Flagship::$current_zone, 'right');
}	
function flagship_zone_start_wrapper() {
	$zone = Flagship::$current_zone;
	if( Flagship::zone_variables($zone, 'wrapper') ) : ?>
		<div id="<?php echo $zone ?>-wrapper" class="zone-wrappper">
	<?php endif;
}
function flagship_zone_end_wrapper() {
	$zone = Flagship::$current_zone;
	if( Flagship::zone_variables($zone, 'wrapper') ) : ?>
	</div>
	<?php endif;
}

/** Misc Functions **/

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
	else {
		return 'index';
	}
}

?>