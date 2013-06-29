<?php
/**
 * Widget that will display current page's child pages.
 * 
 * @package Flagship
 * @since Flagship 0.3
 */
if(!class_exists('FS_Child_pages')) :
	
add_action( 'widgets_init', create_function( '', 'return register_widget( "FS_Child_Pages" );' ) );
class FS_Child_Pages extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_child_pages', 'description' => __( 'If a page is being viewed, this widget will display and child pages attached to it in a menu.') );
		parent::__construct('fs_child_pages', __('Child Pages'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $post;
		extract( $args );

		if(!is_post_type_hierarchical( get_post_type($post) ))
			return;

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? null : $instance['title'], $instance, $this->id_base);
		$sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];

		if ( $sortby == 'menu_order' )
			$sortby = 'menu_order, post_title';
		
		// Round up 'dem der children. 
		if(!$post->post_parent) {
			//Current page has no parent, this is top-level.
			$post_child = $post->ID;
		} else {
			$post_child = $post->post_parent;
			//Need to do check if parent has parent. If true use that
			$post_parent = get_post($post_child);
			if($post_parent->post_parent) {
				$post_child = $post_parent->post_parent;
			}
			
		}
		$out = wp_list_pages( array('child_of' => $post_child, 'title_li' => '', 'echo' => 0, 'sort_column' => $sortby) );

		if(!empty( $out )) {
			echo $before_widget;
			if($title)
				echo $before_title . $title . $after_title;
		?>
		<ul class="child-pages">
			<li class="parent"><a href="<?php echo get_permalink($post->post_parent) ?>"><?php echo get_the_title($post->post_parent); ?></a></li>
			<?php echo $out; ?>
		</ul>
		<?php
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( in_array( $new_instance['sortby'], array( 'post_title', 'menu_order', 'ID' ) ) ) {
			$instance['sortby'] = $new_instance['sortby'];
		} else {
			$instance['sortby'] = 'menu_order';
		}
		$instance['display_title'] = $new_instance['display_title'];
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'sortby' => 'post_title', 'title' => '', 'display_title' => 'yes') );
		$title = esc_attr( $instance['title'] );
		echo $display = $instance['display_title'];
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('sortby'); ?>"><?php _e( 'Sort by:' ); ?></label>
			<select name="<?php echo $this->get_field_name('sortby'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">
				<option value="post_title"<?php selected( $instance['sortby'], 'post_title' ); ?>><?php _e('Page title'); ?></option>
				<option value="menu_order"<?php selected( $instance['sortby'], 'menu_order' ); ?>><?php _e('Page order'); ?></option>
				<option value="ID"<?php selected( $instance['sortby'], 'ID' ); ?>><?php _e( 'Page ID' ); ?></option>
			</select>
		</p>
<?php
	}

}
endif;
?>