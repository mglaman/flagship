<?php
/**
 * Masthead HTML
 *
 * @package Flagship
 * @since Flagship 0.1
 */
?>	
	<?php flagship_zone_start_wrapper(); ?>
	  <div <?php flagship_zone_attribtutes(); ?>>
	  	<?php flagship_zone_before_hook(); ?>
			<?php get_template_part('templates/loop/loop', flagship_current_view()); ?> 
		<?php flagship_zone_after_hook(); ?>
	  </div>
	<?php flagship_zone_end_wrapper(); ?>