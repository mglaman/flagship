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
 * -- loop-tag.php 		Tag archive page.
 *
 * Example templating for post type: loop-post-type.php
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
<?php endif; 

do_action('flagship_after_loop'); ?>