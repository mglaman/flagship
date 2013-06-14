<?php
/**
 * Header section HTML formatting
 *
 * @package Flagship
 * @since Flagship 0.1
 */
 
 #@TODO: Move to DB;
 $header_zones = array('masthead', 'branding', 'navigation', 'preface');
  foreach($header_zones as $zone ) {
  	flagship_zone_template($zone); 
  }?>
