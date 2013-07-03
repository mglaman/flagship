<?php
/**
 * Default hooks used by theme, also list for theme builders.
 *
 * @package Flagship
 * @since Flagship 0.3
 */

/**
 * Loop Templates
 */
add_action('flagship_before_loop', 'flagship_before_loop');
function flagship_before_loop() {
}
add_action('flagship_after_loop', 'flagship_after_loop');
function flagship_after_loop() { ?>
	<nav id="loop-pages" role="navigation">
		<div class="nav-previous alignleft"><?php next_posts_link( '<span class="pages-nav">&larr;</span> Previous' ); ?></div>
		<div class="nav-next alignright"><?php previous_posts_link( 'More Recent <span class="pages-nav">&rarr;</span>'); ?></div>
	</nav>
<?php }
 
/**
 * Content Templates
 */

//Places content before <header> tags
add_action('flagship_content_before_header', 'flagship_content_before_header', 10);
function flagship_content_before_header() {
	if ( is_sticky() && is_home() && ! is_paged() ) : ?>
	<div class="featured-post">Featured</div>
	<?php endif;
}
//Executes before entry title
add_action('flagship_content_before_title', 'flagship_content_before_title', 10);
function flagship_content_before_title() {
	
}
//This hook is used to display the content title, by default uses a templated file.
add_action('flagship_content_title', 'flagship_content_title', 10);
function flagship_content_title() {
	get_template_part('templates/other/content', 'title');
}
//Executes after entry title
add_action('flagship_content_after_title', 'flagship_content_after_title', 10);
function flagship_content_after_title() {
	the_post_thumbnail();
}
//Executes before the_content
add_action('flagship_content_before_content', 'flagship_content_before_content', 10);
function flagship_content_before_content() {
	
}
//Executes after the_content
add_action('flagship_content_after_content', 'flagship_content_after_content', 10);
function flagship_content_after_content() {
	flagship_link_pages();
}
//Executes before <footer>
add_action('flagship_content_before_footer', 'flagship_content_before_footer', 10);
function flagship_content_before_footer() {
	comments_template( '', true );
}
//Executes within footer
add_action('flagship_content_footer', 'flagship_content_footer', 10);
function flagship_content_footer() {
	edit_post_link( 'Edit', '<span class="edit-link">', '</span>' );
}
//Executes after <footer>
add_action('flagship_content_after_footer', 'flagship_content_after_footer', 10);
function flagship_content_after_footer() {
	
}
?>
