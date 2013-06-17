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
		<?php if(!flagship_zone_widgets()) : //Default navigation if no widgets?>
	  		<?php get_template_part('templates/navigation', 'horizontal'); ?>
	  	<?php endif; ?>
	 </div>
<?php flagship_zone_end_wrapper(); ?>
