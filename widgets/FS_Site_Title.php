<?php
/**
 * Widget that will display current page's child pages.
 *
 * @package Flagship
 * @since Flagship 0.3
 */
if(!class_exists('FS_Site_Title')) :

add_action( 'widgets_init', create_function( '', 'return register_widget( "FS_Site_Title" );' ) );
class FS_Site_Title extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_site_title', 'description' => __( 'Displays your WordPress website\'s title and tagline.') );
		parent::__construct('fs_site_title', __('Site Title'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget; ?>
		      <h1 id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                        <?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>
                </a></h1>
                <?php if(!empty($instance['show_desc'])) : ?><h2 id="site-description"><?php esc_attr( get_bloginfo('description', 'display')); ?></h2><?php endif; ?>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['show_desc'] = ($new_instance['show_desc']);
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'show_desc' => 'true') );
		$show_desc = ( $instance['show_desc'] );
	?>
		<p><label for="<?php echo $this->get_field_id('show_desc'); ?>">
		      <input type="checkbox" id="<?php echo $this->get_field_id('show_desc'); ?>" name="<?php echo $this->get_field_name('show_desc'); ?>" value="true" <?php checked($show_desc, 'true') ?>/><?php _e( 'Show site description' ); ?></label>
		</p>
<?php
	}

}
endif;
?>