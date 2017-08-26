<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    FB_Dynamic_Ads_Real_Estate
 * @subpackage FB_Dynamic_Ads_Real_Estate/includes
 * @author     Dave Bonds <db@davebonds.com>
 */
class FB_Dynamic_Ads_Real_Estate_Activator {

	/**
	 * Add feed and flush rewrite rules.
	 *
	 * Upon activation, include admin class to register and add the feed
	 * then flush rewrite rules.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! class_exists( 'WP_Listings' ) ) {
			deactivate_plugins( __FILE__ );
			$error_message = __( 'This plugin requires <a href="https://wordpress.org/plugins/wp-listings/" target="_blank">IMPress Listings</a> installed and activated.', 'fb-dare' );
			die( $error_message );
		}

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fb-dynamic-ads-real-estate-admin.php';
		add_feed( 'fb-catalog', array( 'FB_Dynamic_Ads_Real_Estate_Admin', 'fb_dynamic_ads_feed_output' ) );
		flush_rewrite_rules();
	}

}
