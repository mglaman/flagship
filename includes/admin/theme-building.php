<?php
if(isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['export_config'])) {
	$options_config = get_option('flagship');
	$options_config = json_encode($options_config, JSON_PRETTY_PRINT);
	$write_handle = fopen(get_stylesheet_directory().'/config.json', 'w+');
	fwrite($write_handle, $options_config);
	fclose($write_handle);
}

//Get details about our config file
// We call this after the above $_POST check, incase we just made the config file ;)
$config_source = 'core';
$config_path = get_template_directory() . '/config.core.json';
if(file_exists(get_stylesheet_directory() . '/config.json') && filesize(get_stylesheet_directory() . '/config.json') > 0) {
	$config_source = 'child';
	$config_path = get_stylesheet_directory() . '/config.json';
}

if(isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['revert_config'])) {
	Flagship::load_theme_variables(true);
}
?>

<div id="flagship-configuration" class="wrap">
<div id="icon-welcome" class="icon32" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/page-icon-config.png);"></div>
<h2>Flagship Theme Building</h2>
<p class="lead">This page is for theme builders. It allows you to see what configuration file has been loaded (core or child) and the differences between the two configurations. 
		You can export the configuation settings saved in WordPress to create a new config.json for child theme development. You may also paste in a configuation JSON below and import it.</p>
<div class="divider">
	<h4><span class="capitalize"><?php echo $config_source; ?></span> Configuration Defaults</h4>
	<p>This is the configuration JSON data the theme was packaged with and used. Allows you to make sure your child theme is loading correctly.</p>
	<pre class="pre-scrollable">
<?php 
			$config_handler = fopen($config_path, 'r');
			$config_data = fread($config_handler, filesize($config_path));
			fclose($config_handler);
			$theme_variables = json_decode($config_data, true);
			print_r($config_data);
?>
	</pre>
	<form action="admin.php?page=fs-theme-building" method="post">
		<?php settings_fields( 'theme-building' ); ?>
		<input type="submit" name="revert_config" value="Revert theme to original configuration"  class="button"/>
	</form>
</div>
<div class="divider">
	<h4>Configuration Settings Saved in WordPress</h4>
	<p>This is the current configuration JSON data your site is using. Copy this and save to a new config.json to use on child themes.</p>
	<pre class="pre-scrollable">
<?php 
			$options_config = get_option('flagship');
			$options_config = json_encode($options_config, JSON_PRETTY_PRINT);
			print_r($options_config);
?>
	</pre>
	<?php if(is_child_theme()) : ?>
	<form action="admin.php?page=fs-theme-building" method="post">
		<?php settings_fields( 'theme-building' ); ?>
		<input type="submit" name="export_config" value="Export and Write Config for Child Theme"  class="button"/>
	</form>
	<?php endif; ?>
</div>
<br style="clear: both;" />
</div>