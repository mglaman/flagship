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
	<!-- Breadcrumb, if no dynamic sidebar use framework breadcrumb function (to be created); -->
	<?php if(!flagship_zone_widgets()) : //Default text if no widgets?>
		<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } ?>
	<?php endif; ?>	  	
 </div>
<?php flagship_zone_end_wrapper(); ?>