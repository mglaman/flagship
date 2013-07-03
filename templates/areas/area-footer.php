<?php
/**
 * Header section HTML formatting
 *
 * @package Flagship
 * @since Flagship 0.1
 */
?>

<footer id="area-footer" class="section clear">
	<div class="grid-container clear">
		<?php  
		 /**
		  * Cycles through each zone that is attached to the header area.
		  */
		 foreach(flagship_footer_zones() as $zone => $attr )
		  	flagship_zone_template($zone); 
		?>
	</div>
</footer>
