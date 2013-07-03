<?php
/**
 * Header section HTML formatting
 *
 * @package Flagship
 * @since Flagship 0.1
 */
?>

<section id="area-content" class="section clear">
	<div class="grid-container clear">
		<?php  
		 /**
		  * Cycles through each zone that is attached to the header area.
		  */
		 foreach(flagship_content_zones() as $zone => $attr )
		  	flagship_zone_template($zone); 
		?>
	</div>
</section>
