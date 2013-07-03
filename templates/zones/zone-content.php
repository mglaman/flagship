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
			<?php get_template_part('templates/loop/loop', flagship_current_view()); ?> 		
	  </div>
	<?php flagship_zone_end_wrapper(); ?>