<?php
$theme_variables = Flagship::get_theme_variables();
if(isset($_POST['action']) && $_POST['action'] == 'update') {
	$theme_variables = Flagship::get_theme_variables(true);
	$theme_variables['seo']['force_www'] = $_POST['flagship']['seo']['force_www'];
	$theme_variables['disable_widgets'] = $_POST['flagship']['disable_widgets'];
	Flagship::update_flagship_options($theme_variables);
}
add_settings_section('flagship-seo', 'Search Engine Optimization', 'flagship_seo_settings_section', 'settings');
	add_settings_field('force-www', 'Force www.', 'flagship_seo_force_www', 'settings', 'flagship-seo',  'flagship-force-www');	

function flagship_seo_settings_section() {
	?><p>Flagship provides some basic SEO support. WordPress SEO by Yoast is highly recommended for your SEO needs.</p><?php
}
function flagship_seo_force_www() {
	$theme_variables = Flagship::get_theme_variables(true);
	?> <p><label><input type="radio" value="true" name="flagship[seo][force_www]" <?php checked($theme_variables['seo']['force_www'], 'true'); ?>/> Yes, use www.example.com</label></p> <?php
	?> <p><label><input type="radio" value="false" name="flagship[seo][force_www]" <?php checked($theme_variables['seo']['force_www'], 'false'); ?>/> No, use example.com</label></p> <?php
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
					<div class="force-www clear">
						<div class="controls-text">
							<strong>Canonical (Preferred) Domain</strong>
						</div>
						<div class="controls">
							<p><label class="radio"><input type="radio" value="true" name="flagship[seo][force_www]" <?php checked($theme_variables['seo']['force_www'], 'true'); ?>/> Yes, use www.example.com</label></p>
							<p><label class="radio"><input type="radio" value="false" name="flagship[seo][force_www]" <?php checked($theme_variables['seo']['force_www'], 'false'); ?>/> No, use example.com</label></p>
						</div>
					</div>
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