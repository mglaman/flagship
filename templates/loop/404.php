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
 * @since Flagship 0.2
 */
$theme_variables = Flagship::get_theme_variables();
$error_title = (isset($theme_variables['error_page']['title'])) ? $theme_variables['error_page']['title'] : 'Uh Oh! 404';
$error_search = (isset($theme_variables['error_page']['search'])) ? true : false;
$error_text = (isset($theme_variables['error_page']['text'])) ? $theme_variables['error_page']['text'] : false; 
?>
	<article id="four-oh-four" class="error-page error">
		<header class="entry-header">
			<h1 class="entry-title"><?php echo $error_title; ?></h1>
		</header>
		<div class="entry-content">
			<?php if($error_text) echo apply_filters('the_content', $error_text); ?>
			<?php if($error_search) get_search_form(); ?>
		</div><!-- .entry-content -->
	</article><!-- #post -->