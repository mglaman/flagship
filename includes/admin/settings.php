<?php
if(isset($_POST['action']) && $_POST['action'] == 'update') {
	$theme_variables = Flagship::get_theme_variables(true);
	$theme_variables['seo']['force_www'] = $_POST['flagship']['seo']['force_www'];
	
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
<div class="wrap">
<div id="icon-welcome" class="icon32" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/page-icon-settings.png);"></div>
<h2>Flagship Settings</h2>
<form method="post" action=""> 
	<?php settings_fields( 'flagship' ); 
			do_settings_sections( 'settings', 'flagship-seo' );?>
<?php submit_button(); ?>
</form>
</div>