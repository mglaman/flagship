<?php
$theme_variables = Flagship::get_theme_variables();
if(isset($_POST['action']) && $_POST['action'] == 'update') {
	$theme_variables = Flagship::get_theme_variables(true);
	$theme_variables['seo'] = $_POST['flagship']['seo'];
	$theme_variables['disable_widgets'] = $_POST['flagship']['disable_widgets'];
	Flagship::update_flagship_options($theme_variables);
}
?>
<div class="wrap flagship flagship-seo">
<div id="icon-welcome" class="icon32" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/page-icon-settings.png);"></div>
<!--<pre><?php global $wp_registered_widgets; print_r($wp_registered_widgets); ?></pre>-->
<h2>Flagship Settings</h2>
<form method="post" action=""> 
	<div class="metabox-holder">
		<div class="flagship-left-column post-box-container">
			<?php settings_fields( 'flagship' ); ?>
			<section id="seo" class="postbox seo">
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3 class="hndle fancy-title"><span>Search Engine Optimization</span></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr align="top">
								<th scope="row"><label>Canonical (Preferred) Domain</label></th>
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
			</section> 
			<section id="seo" class="postbox widgets">
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
			</section> 
<?php submit_button(); ?>
</form>
</div>