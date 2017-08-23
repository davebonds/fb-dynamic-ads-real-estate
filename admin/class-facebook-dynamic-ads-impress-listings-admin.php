<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    Facebook_Dynamic_Ads_Impress_Listings
 * @subpackage Facebook_Dynamic_Ads_Impress_Listings/admin
 * @author     Dave Bonds <db@davebonds.com>
 */
class Facebook_Dynamic_Ads_Impress_Listings_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the XML feed with WordPress.
	 *
	 * @since  1.0.0
	 */
	public function fb_dynamic_ads_feed() {
		add_feed( 'fb-catalog', array( $this, 'fb_dynamic_ads_feed_output' ) );
	}

	/**
	 * Require the feed template.
	 * Checks for existence in theme folder first.
	 *
	 * @since   1.0.0
	 */
	public function fb_dynamic_ads_feed_output() {
		if ( file_exists( get_stylesheet_directory() . '/feed-fb-catalog-xml.php' ) ) {
			$template = get_stylesheet_directory() . '/feed-fb-catalog-xml.php';
		} else {
			$template = plugin_dir_path( dirname( __FILE__ ) ) . '/public/feed-fb-catalog-xml.php';
		}
		require( $template );
	}

}
