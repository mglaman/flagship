<?php 
/**
 * Built-in updater for Flagship
 * 
 *
 * @package Flagship
 * @since Flagship 0.4
 */

class FlagshipUpdater {
	public static $version = '0.4';

	/**
	* Checks if version is current
	*/
	public static function check_updates($cache = true) {
		$info = self::get_version_info($cache);
		if(version_compare(self::$version, $info->version, '<' ))
			return $info;
		return false;
	}
	/**
	* Checks flagshiptheme.com for latest version info. Allows other plugins or addons to specify their own URL parameters
	*/
	public static get_version_info($cache = true) {
		//Checks if we have a cached response, save server some stress
		$response = get_transient("flagship_version_info");
		if(!$cache)
			$response = null;

		if(!$response) {
			$optionts = array(
				'method' => 'POST',
				'timeout' => 20
				);
			$options['headers'] = array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
				'User-Agent' => 'WP_ Flagship/' . get_bloginfo("version"),
				'Referer' => get_bloginfo("url")
				);
			$manager_url = 'http://flagshiptheme.com/wp-content/flagship-updater/ver.php?for=Flagship&ver='.urlencode(self::$version);
			$response = wp_remote_request($manager_url, $options);

			//Logs response for 24 hours.
			set_transient($project . "_version_info", $response, 86400);
		}
		if(is_wp_error($response) || 200 != $response['response']['code'])	
			return false;

		return = json_decode($response['body']);

	}

}

?>