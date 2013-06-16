<div id="flagship-configuration" class="wrap">
<div id="icon-welcome" class="icon32" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/page-icon-config.png);"></div>
<h2>Flagship Theme Building</h2>
<p class="lead">This page is for theme builders. It allows you to see what configuration file has been loaded (core or child) and the differences between the two configurations. 
		You can export the configuation settings saved in WordPress to create a new config.json for child theme development. You may also paste in a configuation JSON below and import it.</p>
<div class="divider">
	<h4><span class="capitalize"><?php echo Flagship::$config_source; ?></span> Configuration Defaults</h4>
	<p>This is the configuration JSON data the theme was packaged with and used. Allows you to make sure your child theme is loading correctly.</p>
	<pre class="pre-scrollable">
<?php 
			//Lets open up that config and build up our zones!
			$config_path = (Flagship::$config_source == 'core') ? get_template_directory() . '/config.core.json' : $config_path = get_stylesheet_directory() . '/config.json';
			$config_handler = fopen($config_path, 'r');
			$config_data = fread($config_handler, filesize($config_path));
			fclose($config_handler);
			$theme_variables = json_decode($config_data, true);
			print_r($config_data);
?>
	</pre>
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
</div>
<br style="clear: both;" />
</div>