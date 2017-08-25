<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    Facebook_Dynamic_Ads_Real_Estate
 * @subpackage Facebook_Dynamic_Ads_Real_Estate/includes
 * @author     Dave Bonds <db@davebonds.com>
 */
class Facebook_Dynamic_Ads_Real_Estate_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fb-dare',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
