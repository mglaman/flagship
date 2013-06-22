<?php
wp_enqueue_script('postbox');
$theme_variables = Flagship::get_theme_variables();

if(isset($_POST['action']) && $_POST['action'] == 'update') {
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
				<?php settings_fields( 'flagship' ); ?>
				<div class="zone-navigation ui-sortable">
					<div class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle fancy-title"><span>Navigation</span></h3>
						<div class="inside">
							<table class="form-table">
								<tr align="top">
									<th scope="row"><label for="orientation">Menu's Orientation</label></th>
									<td>
										<select id="orientation" name="flagship[navigation][type]" style="width:80%;">
											<option value="horizontal" <?php selected($theme_variables['navigation']['type'], 'horizontal'); ?>>Horizontal</option>
											<option value="vertical" <?php selected($theme_variables['navigation']['type'], 'vertical'); ?>>Vertical</option>
										</select>
										<p>These settings allow you to adjust the built-in WordPress navigation menu settings.</p>
									</td>
								</tr>
							</table>
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