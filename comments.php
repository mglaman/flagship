<?php
/**
 * Comments template
 *
 * @package Flagship
 * @since Flagship 0.1
 */
if ( post_password_required() )
	return;
?>
<div id="comments" class="comments-area">
	<?php do_action('flagship_before_comments'); ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title"><?php get_comments_number(); ?> </h2>

		<ol class="commentlist">
			<?php
				/**
				* You can filter how comments are displayed.
				* Ex: by attaching a filter to flagship_comment_type you can choose what type of entries to display, comments, pingbacks or trackbacks (or pings for both)
				* Default framework comment template uses OL. If you override, provide your own template.
				**/
				$args = array(
					'style' => apply_filters('flagship_comment_style', 'ol'),
					'avatar_size' => apply_filters('flagship_comment_style', '32'),
					'type' => apply_filters('flagship_comment_type', 'all'),
					'callback' => apply_filters('flagship_comment_callback', 'flagship_comment_entry_template')
				);
				wp_list_comments( $args );
			 ?>
		</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<?php
		if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.' , 'twentytwelve' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php do_action('flagship_after_comments'); ?>

	<?php comment_form(); ?>

</div><!-- #comments .comments-area -->