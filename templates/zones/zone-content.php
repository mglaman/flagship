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
	  	<?php 
	  		/**
	  		 * Themers and plugin developers can override default loop template selected via the flagship_{flagship_current_view}_template filter
	  		 */
	  		flagship_zone_before_hook();
			flagship_loop_template();
			flagship_zone_after_hook(); 
		?>
	  </div>
	<?php flagship_zone_end_wrapper(); ?>