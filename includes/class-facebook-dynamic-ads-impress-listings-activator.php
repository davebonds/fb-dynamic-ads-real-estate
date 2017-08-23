<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    Facebook_Dynamic_Ads_Impress_Listings
 * @subpackage Facebook_Dynamic_Ads_Impress_Listings/includes
 * @author     Dave Bonds <db@davebonds.com>
 */
class Facebook_Dynamic_Ads_Impress_Listings_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		flush_rewrite_rules();
	}

}
