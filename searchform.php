<?php
/**
 * Flagship theme search form
 *
 * @package Flagship
* @since Flagship 0.2
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label for="s" class="screen-reader-text">Search</label>
		<input type="text" class="field" name="s" id="s" placeholder="Search" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="Search" />
	</form>
