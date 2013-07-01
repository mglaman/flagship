<?php
/**
 * This is a basic loop file that is called from zone-content.
 * 
 * Available options (template.handler.php function flagship_current_view()):
 * -- loop-blog.php for 	Blogs Index Page (http://codex.wordpress.org/Template_Hierarchy#Home_Page_display)
 * -- loop-archive.php 		Post Type Archives
 * -- loop-author.php 		Author Archives 
 * -- loop-category.php 	Default blog category archive page.
 * -- loop-taxonomy.php 	For custom taxnomies on custom post types
 * -- loop-attachment.php   Media Attachment Pages
 * -- loop-page.php 		Single Page
 * -- loop-single.php 		Single Post
 * -- loop-tag.php 			Tag archive page.
 * 
 * Currently does not support single-my-postype.php templating.
 *
 * @package Flagship
 * @since Flagship 0.1
 */
 
do_action('flagship_before_loop');
?>
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'templates/content/content', get_post_format() ); ?>
	<?php endwhile; ?>
	
	<nav id="loop-pages" role="navigation">
		<div class="nav-previous alignleft"><?php next_posts_link( '<span class="pages-nav">&larr;</span> Previous' ); ?></div>
		<div class="nav-next alignright"><?php previous_posts_link( 'More Recent <span class="pages-nav">&rarr;</span>'); ?></div>
	</nav>
<?php endif; 

do_action('flagship_after_loop'); ?>