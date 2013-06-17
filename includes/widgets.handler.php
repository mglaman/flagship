<?php
/**
 * Handler for widgets
 *
 * @package Flagship
 * @since Flagship 0.1
 */
 

class WidgetsHandler {
	public static $widget_filters = array();
	public static $widget_classes = array();
	
	public static function initialize() {
		if(($widget_filters = get_option('fs_widgetfilters')) || is_array($block_filters) ) 
			self::$widget_filters = $widget_filters;
		if(($widget_classes = get_option('fs_widgetclasses')) || is_array($widget_classes) )
			self::$widget_classes = $widget_classes;

		
		//Load our filters
		if( is_admin() ) {
			//Load admin filters
			add_filter( 'widget_update_callback', array('WidgetsHandler' , 'extend_widgets_update_callback'), 10, 3);
			add_action( 'sidebar_admin_setup', array('WidgetsHandler' , 'extend_widgets_sidebar_admin_setup'));
		} else {
			//Load public filters
			add_filter( 'sidebars_widgets', array('WidgetsHandler' , 'extend_widgets_sidebars_widgets'), 10);
			add_filter( 'dynamic_sidebar_params', array('WidgetsHandler' , 'extend_widgets_sidebar_params'), 10);
		}
	}
	/**
	 * Callback on widget_update_callback hook
	 */
	public static function extend_widgets_update_callback($instance, $new_instance, $widget) {
		$widget_id = $this_widget->id;
		//Check if our input was posted
		if ( isset($_POST[$widget_id.'-filter'])) {
			self::$widget_filters[$widget_id]= trim($_POST[$widget_id.'-filter']);
			update_option('fs_widgetfilters', self::$widget_filters);
		}
		if ( isset($_POST[$widget_id.'-classes'])) {
			self::$widget_classes[$widget_id]= trim($_POST[$widget_id.'-classes']);
			update_option('fs_widgetclasses', self::$widget_classes);
		}
		return $instance;
	}	
	/**
	 * Called on Widgets page, cycles through each widget to set up form.
	 */
	public static function extend_widgets_sidebar_admin_setup() {
		global $wp_registered_widgets, $wp_registered_widget_controls;
	
		//Adds extra fields to the widget
		foreach($wp_registered_widgets as $id => $widget) {
			if (!$wp_registered_widget_controls[$id])
				wp_register_widget_control($id,$widget['name'], array('WidgetsHandler', 'extend_widgets_empty_control'));
			
			//Saves original callback as _filter_redirect, then plugs in our add_control
			$wp_registered_widget_controls[$id]['width'] = 400;
			$wp_registered_widget_controls[$id]['callback_filter_redirect']=$wp_registered_widget_controls[$id]['callback'];
			$wp_registered_widget_controls[$id]['callback']= array('WidgetsHandler', 'extend_widgets_add_fields');
			array_push($wp_registered_widget_controls[$id]['params'],$id);	
		}
		
		// UPDATE WIDGET LOGIC WIDGET OPTIONS (via accessibility mode?)
		if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) )
		{	foreach ( (array) $_POST['widget-id'] as $widget_number => $widget_id )
				if (isset($_POST[$widget_id.'-filter'])) {
					self::$widget_filters[$widget_id]=trim($_POST[$widget_id.'-filter']);
				}
				if (isset($_POST[$widget_id.'-classes'])) {
					self::$widget_classes[$widget_id]=trim($_POST[$widget_id.'-classes']);
				}
		}
		update_option('fs_widgetfilters', self::$widget_filters);
		update_option('fs_widgetclasses', self::$widget_classes);
	}
	public static function extend_widgets_empty_control() {
		//TODO: Debug this?
	}

	public static function extend_widgets_add_fields() {
		global $wp_registered_widget_controls;
		$params = func_get_args();
		$id = array_pop($params);
	
		// go to the original control function
		$callback = $wp_registered_widget_controls[$id]['callback_filter_redirect'];
		if (is_callable($callback))
			call_user_func_array($callback, $params);
	
		//Checks if widget has value set in options
		$filter = !empty( self::$widget_filters[$id] ) ? esc_html( stripslashes( self::$widget_filters[$id] ),ENT_QUOTES ) : '';
		$classes = !empty( self::$widget_classes[$id] ) ? esc_html( stripslashes( self::$widget_classes[$id] ),ENT_QUOTES ) : '';
		
		// dealing with multiple widgets - get the number. if -1 this is the 'template' for the admin interface
		$number = $params[0]['number'];
		if ($number==-1) {
			$number="__i__"; 
			$value="";
		}
		$id_disp=$id;
		
		if (isset($number)) 
			$id_disp = $wp_registered_widget_controls[$id]['id_base'].'-'.$number;
	
		printf('<p><label for="%1$s-classes">Custom classes: 
				<input class="widefat" type="text" name="%1$s-classes" id="%1$s-classes" value="%2$s"></label></p>', $id_disp, $classes);
		printf('<p><label for="%1$s-filter">Block Filter: 
				<textarea class="widefat" type="text" name="%1$s-filter" id="%1$s-filter">%2$s</textarea></label></p>', $id_disp, $filter);
	
	
	}

	public static function extend_widgets_sidebars_widgets($widgets) {
		global $wp_reset_query_is_done;
	
		//echo '<pre>';print_r($widgets);echo'</pre>';
		
		foreach( $widgets as $area => $list) {
			if($area == 'wp_inactive_widgets' || empty($list))
				continue;
			
			foreach( $list as $item => $id) {
	
				if(empty(self::$widget_filters[$id]))
					continue;
				
				$filter = stripcslashes(trim(self::$widget_filters[$id]));
				if(empty($filter))
					continue;
				
				$filter = apply_filters('block_filter_eval', $filter);
				//Needs to have return appended for eval()
				if(stristr($filter, 'return') === FALSE)
					$filter = "return ({$filter});";
				if(!eval($filter))
					unset($widgets[$area][$item]);
			}
		}
		return $widgets;
	}
	
	
	/** Params callback on setting widget params **/
	public static function extend_widgets_sidebar_params($params) {
		global $wp_registered_widgets;
	
		$widget_id = $params[0]['widget_id'];
	
		if(!isset(self::$widget_classes[$widget_id]) || self::$widget_classes[$widget_id] == '')
			return $params;
		
		$before_widget_string = $params[0]['before_widget'];
		$before_widget_replace = 'class="' . $widget_classes[$widget_id] .' ';
		$params[0]['before_widget'] = str_replace('class="', $before_widget_replace, $before_widget_string);
	
		return $params;
	}

}
?>
