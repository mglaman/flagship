<?php
/**
 * Handler for zones
 *
 * @package Flagship
 * @since Flagship 0.1
 */

add_action( 'widgets_init', array('ZoneHandler', 'initiate_zones') );

class ZoneHandler {
	
	public static function initiate_zones() {
		# @TODO: Allow ovveride of default befores and afters from settings page.
		foreach(Flagship::config_area_list() as $area => $attr) {
			foreach(Flagship::area_zone_list($area) as $zone => $attr) {
				if($zone == 'content') continue;
				register_sidebar( array(
					'name' => $attr['title'], 
					'id' => $zone, 
					'description' 	=> '', 
					'before_widget' => '<aside id="%1$s" class="widget %2$s">', 
					'after_widget' => "</aside>", 
					'before_title' => '<h3 class="widget-title">', 
					'after_title' => '</h3>') );
			}
		}
	}
	
	protected static function unregister_widgets() {
		$theme_variables = Flagship::get_theme_variables();
		if(is_array($theme_variables['disabled_widgets']) && !empty($theme_variables['disabled_widgets'])) {
			foreach($theme_variables['disabled_widgets'] as $widget) {
				unregister_widget($widget);
			}
		}
	}
	
	public static function get_attributes() {
		$zone = Flagship::$current_zone;
		$columns = flagship_zone_columns();
		$classes = (flagship_zone_extra_classes() 	!= '') ? flagship_zone_extra_classes() : '';
		$left	 = (flagship_zone_left() != '0' && flagship_zone_left() != '') ? 'left-' . flagship_zone_left() : '';
		$right	 = (flagship_zone_right() != '0' && flagship_zone_right() != '') ? 'right-' . flagship_zone_right() : '';
		# @TODO: I don't like the extra empty whitespace. Make if/else printf
		printf('id="%1$s" class="zone %1$s columns-%2$s %3$s %4$s %5$s"', $zone, $columns, $left, $right, $classes);
	}
	/**
	 * Class function to retrieve variables for a zone.
	 */
	public static function zone_variables($zone, $variable) {
		
		if( isset(Flagship::$theme_zones[$zone][$variable]) || !empty(Flagship::$theme_zones[$zone][$variable]) )
			return Flagship::$theme_zones[$zone][$variable];
		return false;
	}
}
 
?>