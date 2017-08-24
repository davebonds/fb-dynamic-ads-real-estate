<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    Facebook_Dynamic_Ads_Real_Estate
 * @subpackage Facebook_Dynamic_Ads_Real_Estate/includes
 * @author     Dave Bonds <db@davebonds.com>
 */
class Facebook_Dynamic_Ads_Real_Estate_Activator {

	/**
	 * Add feed and flush rewrite rules.
	 *
	 * Upon activation, include admin class to register and add the feed
	 * then flush rewrite rules.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-facebook-dynamic-ads-real-estate-admin.php';
		add_feed( 'fb-catalog', array( 'Facebook_Dynamic_Ads_Real_Estate_Admin', 'fb_dynamic_ads_feed_output' ) );
		flush_rewrite_rules();
	}

}
