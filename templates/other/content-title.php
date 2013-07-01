<?php if ( is_singular() ) : ?>
<h1 class="entry-title"><?php the_title(); ?></h1>
<?php else : ?>
<h2 class="entry-title">
	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentytwelve' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
</h2>
<?php endif; // is_singular() ?>