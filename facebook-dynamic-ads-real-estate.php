<?php
/**
 * @link              https://github.com/davebonds/
 * @since             1.0.0
 * @package           Facebook_Dynamic_Ads_Real_Estate
 *
 * @wordpress-plugin
 * Plugin Name:       Facebook Dynamic Ads for Real Estate
 * Plugin URI:        https://github.com/davebonds/facebook-dynamic-ads-real-estate
 * Description:       Adds XML feed formatted for Facebook Dynamic Ads for Real Estate to IMPress Listings.
 * Version:           1.0.0
 * Author:            Dave Bonds
 * Author URI:        https://github.com/davebonds/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       facebook-dynamic-ads-real-estate
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-facebook-dynamic-ads-real-estate-activator.php
 */
function activate_Facebook_Dynamic_Ads_Real_Estate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-facebook-dynamic-ads-real-estate-activator.php';
	Facebook_Dynamic_Ads_Real_Estate_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-facebook-dynamic-ads-real-estate-deactivator.php
 */
function deactivate_Facebook_Dynamic_Ads_Real_Estate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-facebook-dynamic-ads-real-estate-deactivator.php';
	Facebook_Dynamic_Ads_Real_Estate_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_Facebook_Dynamic_Ads_Real_Estate' );
register_deactivation_hook( __FILE__, 'deactivate_Facebook_Dynamic_Ads_Real_Estate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-facebook-dynamic-ads-real-estate.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Facebook_Dynamic_Ads_Real_Estate() {

	$plugin = new Facebook_Dynamic_Ads_Real_Estate();
	$plugin->run();

}
run_Facebook_Dynamic_Ads_Real_Estate();
