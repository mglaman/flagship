
<div class="wrap flagship flagship-dashboard">
<div id="icon-welcome" class="icon32" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/page-icon-dashboard.png);"></div>
<h2>Flagship Dashboard</h2>
<div class="metabox-holder">
	<div class="post-box-container flagship-left-column">
		<div class="inside meta-box-sortables  ui-sortable">
			<div class="postbox">
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3 class="hndle fancy-title"><span>About Flagship</span></h3>
				<div class="inside">
					<p>Thank you for choosing Flagship Theme. This framework was developed and design to extend site building capabilties in WordPress</p>
					<p>WordPress is an extensive piece of software and flushing out to become a true CMS. This framework's goal is to bring over site building technqiues found over in the Drupal community, modeled after Omega Theme.</p>
					<!--<p>Flagship's development is sponsored by <a href="http://jcm-solutions.com" target="_blank">JCM Solutions.</a></p>-->
				</div>
			</div>
		</div>
		<div class="inside meta-box-sortables ui-sortable">
			<div class="postbox">
				<div class="handlediv" title="Click to toggle"><br /></div>
				<h3 class="hndle fancy-title"><span>Flagship News</span></h3>
				<div class="inside">
				<?php
					/*	$news_widget = array(
							'link' => 'http://flagshiptheme.com',
							'url' => 'http://flagshiptheme.com/feed/',
							'title' => 'Flagship News',
							'items' => 5,
							'show_summary' => 1,
							'show_author' => 0,
							'show_date' => 1
						);
					echo '<div class="rss-widget">';
					wp_widget_rss_output( $news_widget );
					echo "</div>";*/

					$flagship_news = fetch_feed('http://flagshiptheme.com/feed/');
					if ( is_wp_error($flagship_news) ) {
						if ( is_admin() || current_user_can('manage_options') )
							echo '<p>' . sprintf( __('<strong>RSS Error</strong>: %s'), $flagship_news->get_error_message() ) . '</p>';
						return;
					}
					if ( !$flagship_news->get_item_quantity() ) {
						echo '<ul><li>' . __( 'An error has occurred, which probably means the feed is down. Try again later.' ) . '</li></ul>';
						$flagship_news->__destruct();
						unset($flagship_news);
						return;
					}
					echo '<ul>';
					foreach ( $rss->get_items(0, 4) as $item ) {
						//Get the post link
						$link = $item->get_link();
						while ( stristr($link, 'http') != $link )
							$link = substr($link, 1);
						$link = esc_url(strip_tags($link));
						//Get the post title
						$title = esc_attr(strip_tags($item->get_title()));
						//Get timestamp
						$date = $item->get_date( 'U' );
						if ( $date )
							$date = ' <span class="rss-date">' . date_i18n( get_option( 'date_format' ), $date ) . '</span>';
						//Get the post body
						$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );
						$desc = esc_html( $desc );
						?>
						<li>
							<h2><?php echo $title; ?></h2>
							<?php echo $date; ?>
							<div class="news-text"><?php echo $desc; ?></div>
						</li>
						<?php

					}
					echo '</ul>';
					$rss->__destruct();
					unset($rss);
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="post-box-container flagship-right-column">

		<div class="inside meta-box-sortables  ui-sortable">
			<div class="postbox">
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3 class="hndle fancy-title"><span>Flagship Support</span></h3>
				<div class="inside">
					<p>
						<ul>
							<li><a href="https://github.com/mglaman/flagship/wiki" target="_blank">Documentation Wiki</a></li>
							<li><a href="#">Screencasts and Video Tutorials</a></li>
							<li><a href="#">Community Forums</a></li>
						</ul>
					</p>
					<!--<p>JCM Solutions provides premium Flagship support subscriptions. <a href="#">Get faster, more extensive support</a> with a yearly subscription.</p>-->
				</div>
			</div>
		</div>
	</div>
</div>
</div>