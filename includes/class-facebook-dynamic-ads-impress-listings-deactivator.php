<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    Facebook_Dynamic_Ads_Impress_Listings
 * @subpackage Facebook_Dynamic_Ads_Impress_Listings/includes
 * @author     Dave Bonds <db@davebonds.com>
 */
class Facebook_Dynamic_Ads_Impress_Listings_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

}
