<?php
/**
 * wooCommerce integration
 *
 * @package Flagship
 * @since Flagship 0.3
 */
 
do_action('flagship_before_loop'); ?>

<?php woocommerce_content(); ?>

<?php do_action('flagship_after_loop'); ?>