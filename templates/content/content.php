<?php
/**
 * The main content template file.
 *
 * @package Flagship
 * @since Flagship 0.1
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php flagship_post_class() ?>>
		<?php do_action('flagship_content_before_header'); ?>
		<header class="entry-header">
			<?php
				/**
				 * The header section of a content post can be customized using hooks.
				 */ 
				do_action('flagship_content_before_title');
				do_action('flagship_content_title');
				do_action('flagship_content_after_title'); 
			?>
		</header><!-- .entry-header -->
		
		<?php do_action('flagship_content_after_header'); ?>
		
		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php do_action('flagship_content_before_excerpt'); ?>
			<?php the_excerpt(); ?>
			<?php do_action('flagship_content_after_excerpt'); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php do_action('flagship_content_before_content'); ?>
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
			<?php do_action('flagship_content_after_content'); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>
		
		<?php do_action('flagship_content_before_footer'); ?>
		
		<footer class="entry-meta">
			<?php do_action('flagship_content_footer'); ?>
		</footer><!-- .entry-meta -->
		
		<?php do_action('flagship_content_after_footer'); ?>
	</article><!-- #post -->