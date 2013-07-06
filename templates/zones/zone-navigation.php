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
		<?php if(!flagship_zone_widgets()) : //Default navigation if no widgets?>
			<div id="nav-toggle" class="clear">
				<hr />
				<hr />
				<hr />
			</div>
	  		<?php flagship_primary_navigation(); ?>
	  	<?php endif; ?>
	  	<?php flagship_zone_after_hook(); ?>
	 </div>
<?php flagship_zone_end_wrapper(); ?>
