<?php
/**
 * Template for comment entries.
 *
 * @package Flagship
 * @since Flagship 0.3
 */
global $fs_comment_args, $fs_comment_depth, $comment;

switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' : ?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<p><abbr class="timestamp" title="<?php echo get_comment_date(); ?>, <?php echo get_comment_time(); ?>"><?php echo apply_filters('flagship_pingback_text', 'Incomming: '); ?></abbr> <span class="author"><?php comment_author_link(); ?></span><?php edit_comment_link( '(Edit)', '<span class="edit-link">', '</span>' ); ?></p>

		<?php
		break;
		default: 
		?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment">
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="notice"><?php echo apply_filters('flagship_comment_moderate_notice', 'Your comment has yet to be moderated'); ?></p>
				<?php endif; ?>
					<header class="author ">
						<div class="gravatar"><?php echo get_avatar( $comment, 44 ); ?></div>
						<div class="name"><?php comment_author_link(); ?></div>
						<div class="timestamp"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php echo get_comment_date(); ?>, <?php echo get_comment_time(); ?></a></div>
					</header>
					<section class="body">
						<?php comment_text(); ?>
						<?php edit_comment_link( '(Edit)', '<span class="edit-link">', '</span>' ); ?>
					</section>
					<footer class="meta">
						<?php comment_reply_link( 
							array_merge( $fs_comment_args, array( '
									reply_text' => 'Reply', 
									'after' => ' <span>&darr;</span>', 
									'depth' => $fs_comment_depth, 
									'max_depth' => $fs_comment_args['max_depth'] ) ) 
							); ?>
					</footer>
		<?php
		break;
endswitch;

//global $comment;
//print_r($comment);
?>