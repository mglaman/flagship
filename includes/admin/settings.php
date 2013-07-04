<?php
wp_enqueue_script('postbox');

$theme_variables = Flagship::get_theme_variables();
if(isset($_POST['action']) && $_POST['action'] == 'update') {
	$theme_variables = Flagship::get_theme_variables(true);
	$theme_variables['seo'] = $_POST['flagship']['seo'];
	$theme_variables['disable_widgets'] = $_POST['flagship']['disable_widgets'];
	$theme_variables['error_page'] = $_POST['flagship']['error_page'];
	$theme_variables['google_font'] = $_POST['flagship']['google_font'];
	$theme_variables['exerpt_display'] = $_POST['flagship']['exerpt_display'];
	$theme_variables['excerpt_read_more'] = $_POST['flagship']['excerpt_read_more'];
	Flagship::update_flagship_options($theme_variables);
}
?>
<div class="wrap flagship flagship-seo">
<div id="icon-welcome" class="icon32" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/page-icon-settings.png);"></div>
<!--<pre><?php global $wp_registered_widgets; print_r($wp_registered_widgets); ?></pre>-->
<h2>Flagship Settings</h2>
<form method="post" action=""> 
<div id="poststuff">
	<div class="metabox-holder">
		<div class="flagship-left-column post-box-container ">
			<?php settings_fields( 'flagship' ); ?>
			<section id="seo" class="seo ui-sortable">
				<div class="postbox">
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3 class="hndle fancy-title"><span>SEO &amp; Webmaster Tools</span></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr align="top">
								<th scope="row"><label>Canonical (Preferred) Domain</label>
									<p style="font-size: 85%; font-style: italic;">Currently doesn't work, needs further development.</p></th>
								<td>
									<?php $stripped_domain = str_replace('http://', '', home_url()); ?>
									<p><label class="radio"><input type="radio" value="<?php echo $stripped_domain ?>" name="flagship[seo][force_www]" <?php checked($theme_variables['seo']['force_www'], $stripped_domain); ?>/> <?php echo $stripped_domain ?></label></p>
									<p><label class="radio"><input type="radio" value="www.<?php echo $stripped_domain ?>" name="flagship[seo][force_www]" <?php checked($theme_variables['seo']['force_www'], 'www.'.$stripped_domain); ?>/> www.<?php echo $stripped_domain ?></label></p>
								</td>
							</tr>
							<tr align="top" class="webmaster-tools">
								<th scope="row"><label for="google_verify">Google Verify Code:</label></th>
								<td><input type="text" id="google_verify" name="flagship[seo][google_verify]" value="<?php echo $theme_variables['seo']['google_verify'] ?>" /></p></td>
							</tr>
							<tr align="top" class="webmaster-tools">
								<th scope="row"><label for="bing_verify">Bing Verify Code:</label></th>
								<td><input type="text" id="bing_verify" name="flagship[seo][bing_verify]" value="<?php echo $theme_variables['seo']['bing_verify'] ?>" /></td>
							</tr>
							<tr align="top" class="webmaster-tools">
								<th scope="row"><label>Google+</label></th>
									<td><input type="text" id="google_plus" name="flagship[seo][google_plus]" value="<?php echo $theme_variables['seo']['google_plus'] ?>" />
										<p>Allows Google to associate your Google+ profile and your website.
									</td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>
			</section> 
			<section id="four-oh-four" class="four-oh-four ui-sortable">
				<div class="postbox closed">
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3 class="hndle fancy-title"><span>404 Error Page</span></h3>
				<div class="inside">
					<div class="default-widgets clear">
						<table class="form-table">
							<tr align="top">
								<th scope="row"><label for="error_title">Title</label></th>
								<td><input id="error_title" name="flagship[error_page][title]" value="<?php echo $theme_variables['error_page']['title']; ?>" /></td>
							</tr>
							<tr align="top">
								<th scope="row"><label for="error_search">Search</label></th>
								<td><label><input type="checkbox" id="error_search" name="flagship[error_page][search]" value="on" <?php checked($theme_variables['error_page']['search'], 'on'); ?>/> Show the WordPress search form</label></td>
							</tr>
							<tr align="top">
								<th scope="row"><label for="error_text">Message</label></th>
								<td><textarea id="error_text" name="flagship[error_page][text]" style="width: 100%; height: 200px;"><?php echo $theme_variables['error_page']['text']; ?></textarea></td>
							</tr>
						</table>
					</div>
				</div>
				</div>
			</section> 
			<section id="widgets" class="widgets ui-sortable">
				<div class="postbox closed">
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3 class="hndle fancy-title"><span>Widget Preferences</span></h3>
				<div class="inside">
					<div class="default-widgets clear">
						<div class="controls-text">
							<strong>Check to disable built-in default WordPress widgets</strong>
						</div>
						<div class="controls">
							<?php foreach(Flagship::default_wordpress_widgets() as $default_widget => $default_name) : ?>
								<p class="pull-left" style="width: 32%;"><label class="checkbox"><input type="checkbox" value="true" name="flagship[disable_widgets][<?php echo $default_widget; ?>]" <?php checked($theme_variables['disable_widgets'][$default_widget], 'true'); ?>/><?php echo $default_name; ?></label></p>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				</div>
			</section> 
		<?php submit_button(); ?>
		</div>
		<div class="post-box-container flagship-right-column">
			<div class="inside meta-box-sortables  ui-sortable">
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle fancy-title"><span>Google Fonts Support</span></h3>
					<div class="inside">
						<ul>
							<li><label class="checkbox"><input type="radio" value="_none" name="flagship[google_font]" <?php checked($theme_variables['google_font'], '_none'); ?>/> None</label></li>
						<?php $supported_fonts = array(
								'fauna-one' => 'Fauna One',
								'noto-serif' => 'Noto Serif',
								'raleway' => 'Raleway',
								'open-sans' => 'Open Sans',
							); 
						foreach($supported_fonts as $font_handle => $font_name) :?>
							<li><label class="checkbox"><input type="radio" value="<?php echo $font_handle ?>" name="flagship[google_font]" <?php checked($theme_variables['google_font'], $font_handle); ?>/> <?php echo $font_name; ?></label></li>
						<?php endforeach; ?>
						</ul>
						<?php if(isset($theme_variables['google_font']) && $theme_variables['google_font'] != '_none') : ?>
						<p>Your website's default font has been set to <?php echo $supported_fonts[$theme_variables['google_font']]; ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="inside meta-box-sortables ui-sortable">
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle fancy-title"><span>Post Excerpt Options</span></h3>
					<div class="inside">
						<p>When should a post's excerpt be displayed?</p>
						<ul>
							<li><label class="checkbox">
								<input type="checkbox" value="on" name="flagship[exerpt_display][is_home]" <?php checked($theme_variables['exerpt_display']['is_home'], 'on'); ?> />
								 Blog landing page</label>
							</li>
							<li><label class="checkbox">
								<input type="checkbox" value="on" name="flagship[exerpt_display][is_search]" <?php checked($theme_variables['exerpt_display']['is_search'], 'on'); ?> />
								 Search results</label>
							</li>
							<li><label class="checkbox">
								<input type="checkbox" value="on" name="flagship[exerpt_display][is_archive]" <?php checked($theme_variables['exerpt_display']['is_archive'], 'on'); ?> />
								 Archives page</label>
							</li>
							<li><label class="checkbox">
								<input type="checkbox" value="on" name="flagship[exerpt_display][is_category]" <?php checked($theme_variables['exerpt_display']['is_category'], 'on'); ?> />
								 Category page</label>
							</li>
						</ul>
						<p><label for="excerpt_read_more">Change "read more" link text on trimmed post excerpts.</a></p>
						<input type="text" name="flagship[excerpt_read_more]" id="excerpt_read_more" value="<?php echo $theme_variables['excerpt_read_more']; ?>"  class="widefat"/>
					</div>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready( function($) { 
             postboxes.add_postbox_toggles(pagenow);
        });
    //]]>
</script>
</form>
</div>
</div>