<?php
/**
 * Header section HTML formatting
 *
 * @package Flagship
 * @since Flagship 0.1
 */
?>

<header id="area-header" class="section clear">
	<div class="grid-container clear">
		<?php  
		 /**
		  * Cycles through each zone that is attached to the header area.
		  * Right now each zone-template needs to be defined in Flagship or else it won't have proper classes or IDs.
		  * #@TODO: Generate proper classes and IDs per zone with just basic template file.
		  */
		 foreach(flagship_header_zones() as $zone => $attr )
		  	flagship_zone_template($zone); 
		?>
	</div>
</header>
