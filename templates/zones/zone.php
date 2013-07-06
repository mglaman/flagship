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
	  	* You can hook before a zone's widgets are displayed with "flagship_before_[zone]" or "flagship_after_[zone"
	  	*/
	  	flagship_zone_before_hook();
	  	flagship_zone_widgets();
	  	flagship_zone_after_hook(); 
	  	?>
	  </div>
	<?php flagship_zone_end_wrapper(); ?>
