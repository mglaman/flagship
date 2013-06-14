<?php
/**
 * Handler to build out template functions.
 *
 * @package Flagship
 * @since Flagship 0.1
 */
 
function flagship_section_template($section) {
	get_template_part('templates/sections/section', $section);
}
function flagship_zone_template($zone) {
	#@TODO: if(Flagship::zone_variables($zone, 'is_active'))
	get_template_part('templates/zones/zone', $zone);
}
	function flagship_zone_attribtutes($zone) {
		printf('id="%1$s" class="zone %1$s grid-container clearfix"', $zone);
	}




function flagship_zone_start_wrapper($zone) {
	if( Flagship::zone_variables($zone, 'wrapper') ) : ?>
		<div id="<?php echo $zone ?>-wrapper" class="zone-wrappper">
	<?php endif;
}
function flagship_zone_end_wrapper($zone) {
	if( Flagship::zone_variables($zone, 'wrapper') ) : ?>
	</div>
	<?php endif;
}

?>