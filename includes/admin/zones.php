<?php
wp_enqueue_script('postbox');
$theme_variables = Flagship::get_theme_variables();

if(isset($_POST['action']) && $_POST['action'] == 'update') {
	$theme_variables['zones'] 		= $_POST['flagship']['zones'];
	$theme_variables['navigation']	= $_POST['flagship']['navigation'];
	Flagship::update_flagship_options($theme_variables);
}

function select_one_sixteen($current_value) { ?>
	<?php for($i=0;$i<=16;$i++) : ?>
		<option value="<?php echo $i; ?>" <?php selected($current_value, $i); ?>><?php echo $i; ?> Columns</option>
	<?php endfor;
}

?>

<div class="wrap flagship flagship-zones">
<div id="icon-welcome" class="icon32" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/page-icon-dashboard.png);"></div>
<h2>Flagship Zones</h2>
<div id="poststuff">
	<form method="post" action="">
	<div class="metabox-holder">
		<div class="flagship-left-column post-box-container">
			<div class="areas-container"> 
				<?php settings_fields( 'flagship' ); 
					foreach(Flagship::config_area_list() as $area => $attr) : ?>
						<section id="<?php echo $area; ?>" class="postbox area">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle fancy-title"><span><?php echo $attr['title']; ?></span></h3>
							<!--<div class="zones-container inside meta-box-sortables  ui-sortable"> Not supporting drag/drop weights yet. -->
								<div class="zones-container inside ui-sortable">
								<?php foreach(Flagship::area_zone_list($area) as $zone => $attr) : 
									if(!$attr['enabled']) continue;?>
									<div class="postbox closed">
										<div class="handlediv" title="Click to toggle"><br></div>
										<h3 class="hndle"><span><?php echo $attr['title']; ?></span></h3>
										<div class="inside">
											<div class="zone-options-heading clear">
												<p class="title pull-left"><input type="text" name="flagship[zones][<?php echo $zone; ?>][title]" class="widefat" value="<?php echo $attr['title']; ?>" /></p>
												<p class="enabled pull-right"><label class="checkbox"><input type="checkbox" name="flagship[zones][<?php echo $zone; ?>][enabled]" value="1" <?php checked($attr['enabled'], '1'); ?>/>Enabled</label></p>
											</div>
											<div class="zone-options-display clear">
												<div class="option zone">
													<label>Zone</label>
													<select name="flagship[zones][<?php echo $zone; ?>][area]">
														<?php foreach(Flagship::config_area_list() as $area => $area_attr) : ?>
															<option value="<?php echo $area; ?>" <?php selected($attr['area'], $area) ?>><?php echo $area_attr['title']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="option left">
													<label>Pad Left</label>
													<select name="flagship[zones][<?php echo $zone; ?>][left]">
														<?php select_one_sixteen($attr['left']); ?>
													</select>
												</div>
												<div class="option columns">
													<label>Columns</label>
													<select name="flagship[zones][<?php echo $zone; ?>][columns]">
														<?php select_one_sixteen($attr['columns']); ?>
													</select>
												</div>
												<div class="option right">
													<label>Pad Right</label>
													<select name="flagship[zones][<?php echo $zone; ?>][right]">
														<?php select_one_sixteen($attr['right']); ?>
													</select>
												</div>
												<div class="option weight">
													<label>Weight</label>
													<select name="flagship[zones][<?php echo $zone; ?>][weight]">
														<?php for($i=-10;$i<=10;$i++) : ?>
															<option value="<?php echo $i; ?>" <?php selected($attr['weight'], $i); ?>><?php echo $i; ?></option>
														<?php endfor;?>
													</select>													
												</div>
											</div>
											<div class="zone-options-css clear">
												<p class="classes">
													<label>Additional Styling</label>
													<input type="text" name="flagship[zones][<?php echo $zone; ?>][classes]" class="widefat" value="<?php echo $attr['classes']; ?>" placeholder="Enter class names here"/></p>
												<p class="wrapper"><label class="checkbox"><input type="checkbox" name="flagship[zones][<?php echo $zone; ?>][wrapper]" value="true" <?php checked($attr['wrapper'], 'true'); ?>/>Add Wrapper</label></p>
											</div>
										</div>								
									</div>
								<?php endforeach; ?>
							</div>
						</section>
				<?php endforeach; ?>
						<section id="disabled" class="postbox area closed">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle fancy-title"><span>Disabled Zones</span></h3>
							<div class="zones-container inside meta-box-sortables  ui-sortable">
							<?php foreach(Flagship::config_area_list() as $area => $area_attr) : //Wrapper loop so we know area name. @TODO: Fix this. ?>
								<?php foreach(Flagship::disabled_zone_list($area) as $zone => $attr) : 
									//if(!empty($disabled_attr['enabled'])) continue;?>
									<div class="postbox closed">
										<div class="handlediv" title="Click to toggle"><br></div>
										<h3 class="hndle"><span><?php echo $attr['title']; ?></span></h3>
										<div class="inside">
											<div class="zone-options-heading clear">
												<p class="title pull-left"><input type="text" name="flagship[zones][<?php echo $zone; ?>][title]" class="widefat" value="<?php echo $attr['title']; ?>" /></p>
												<p class="enabled pull-right"><label class="checkbox"><input type="checkbox" name="flagship[zones][<?php echo $zone; ?>][enabled]" value="1" <?php checked($attr['enabled'], '1'); ?>/>Enabled</label></p>
											</div>
											<div class="zone-options-display clear">
												<div class="option zone">
													<label>Zone</label>
													<select name="flagship[zones][<?php echo $zone; ?>][area]">
														<?php foreach(Flagship::config_area_list() as $area => $area_attr) : ?>
															<option value="<?php echo $area; ?>" <?php selected($attr['area'], $area) ?>><?php echo $area_attr['title']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="option left">
													<label>Pad Left</label>
													<select name="flagship[zones][<?php echo $zone; ?>][left]">
														<?php select_one_sixteen($attr['left']); ?>
													</select>
												</div>
												<div class="option columns">
													<label>Columns</label>
													<select name="flagship[zones][<?php echo $zone; ?>][columns]">
														<?php select_one_sixteen($attr['columns']); ?>
													</select>
												</div>
												<div class="option right">
													<label>Pad Right</label>
													<select name="flagship[zones][<?php echo $zone; ?>][right]">
														<?php select_one_sixteen($attr['right']); ?>
													</select>
												</div>
												<div class="option weight">
													<label>Weight</label>
													<select name="flagship[zones][<?php echo $zone; ?>][weight]">
														<?php for($i=-10;$i<=10;$i++) : ?>
															<option value="<?php echo $i; ?>" <?php selected($attr['weight'], $i); ?>><?php echo $i; ?></option>
														<?php endfor;?>
													</select>													
												</div>
											</div>
											<div class="zone-options-css clear">
												<p class="classes">
													<label>Additional Styling</label>
													<input type="text" name="flagship[zones][<?php echo $zone; ?>][classes]" class="widefat" value="<?php echo $attr['classes']; ?>"  placeholder="Enter class names here"/></p>
												<p class="wrapper"><label class="checkbox"><input type="checkbox" name="flagship[zones][<?php echo $zone; ?>][wrapper]" value="true" <?php checked($attr['wrapper'], 'true'); ?>/>Add Wrapper</label></p>
											</div>
										</div>								
									</div>
								<?php endforeach; ?>
							<?php endforeach; ?>
							</div>
						</section>
			</div>
		</div>
		<div class="post-box-container flagship-right-column">
			<div class="helper-text">
				<div class="zone-navigation ui-sortable">
					<div class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle fancy-title"><span>Navigation</span></h3>
						<div class="inside">
							These settings allow you to adjust the built-in WordPress navigation menu settings.
							<p><label>Style: <select name="flagship[navigation][type]" style="width:80%;">
													<option value="horizontal" <?php selected($theme_variables['navigation']['type'], 'horizontal'); ?>>Horizontal</option>
													<option value="vertical" <?php selected($theme_variables['navigation']['type'], 'vertical'); ?>>Vertical</option>
											</select>
							</label></p>
						</div>
					</div>
				</div>
				<div class="zones-container inside meta-box-sortables  ui-sortable">
					<div class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle fancy-title"><span>Help</span></h3>
						<div class="inside">
							Zones are grouped by header and displayed by their weight.
						</div>
					</div>
				</div>
				<?php submit_button(); ?>
			</div>
		</div>
	</div>
	</form>
	<script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready( function($) { 
             postboxes.add_postbox_toggles(pagenow);
        });
    //]]>
</script>
</div>
</div>