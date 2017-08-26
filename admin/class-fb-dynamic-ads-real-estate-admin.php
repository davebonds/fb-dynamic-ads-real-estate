<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    FB_Dynamic_Ads_Real_Estate
 * @subpackage FB_Dynamic_Ads_Real_Estate/admin
 * @author     Dave Bonds <db@davebonds.com>
 */
class FB_Dynamic_Ads_Real_Estate_Admin {

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

		$this->settings = array(
			'pixel_id' => array(
				'id'      => 'pixel_id',
				'title'   => __( 'Facebook Pixel ID', 'fb-dare' ),
				'tooltip' => __( 'The Facebook Pixel ID', 'fb-dare' ),
			),
			'access_token' => array(
				'id'      => 'access_token',
				'title'   => __( 'Facebook API Access Token', 'fb-dare' ),
				'tooltip' => __( 'The Facebook API Access Token', 'fb-dare' ),
			),
			'application_id' => array(
				'id'      => 'application_id',
				'title'   => __( 'Facebook Application ID', 'fb-dare' ),
				'tooltip' => __( 'The Facebook Application ID', 'fb-dare' ),
			),
			'ad_account_id' => array(
				'id'      => 'ad_account_id',
				'title'   => __( 'Facebook Ad Account ID', 'fb-dare' ),
				'tooltip' => __( 'The Facebook Ad Account ID', 'fb-dare' ),
			),
		);

	}

	/**
	 * Register the XML feed with WordPress.
	 *
	 * @since  1.0.0
	 */
	public function fb_dynamic_ads_feed() {
		add_feed( 'fb-catalog', array( $this, 'listing_catalog_template' ) );
	}

	/**
	 * Require the feed template.
	 * Allows for custom template in theme folder.
	 *
	 * @since   1.0.0
	 */
	public function listing_catalog_template() {
		if ( file_exists( get_stylesheet_directory() . '/feed-fb-catalog-xml.php' ) ) {
			$template = get_stylesheet_directory() . '/feed-fb-catalog-xml.php';
		} else {
			$template = plugin_dir_path( dirname( __FILE__ ) ) . '/public/feed-fb-catalog-xml.php';
		}
		require( $template );
	}

	/**
	 * Register metabox for the listings post type.
	 *
	 * @since  1.1.0
	 */
	public function register_meta_box() {
		add_meta_box( 'fb_listing_catalog_metabox', __( 'Facebook Dynamic Ads', 'fb-dare' ), array( $this, 'fb_listing_catalog_metabox' ), 'listing', 'side', 'high' );
	}

	/**
	 * Require metabox HTML.
	 *
	 * @since  1.1.0
	 */
	public function fb_listing_catalog_metabox() {
		require( plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/facebook-dynamic-ads-listing-post-metabox.php' );
	}

	/**
	 * Register submenu page.
	 *
	 * @since  1.1.0
	 * @return void
	 */
	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=listing', 'Facebook Dynamic Ads', 'FB Dynamic Ads', 'manage_options', 'fb-dare-settings', array( $this, 'settings_page' ) );
	}

	/**
	 * Render settings page fields and sections.
	 *
	 * @since  1.1.0
	 */
	public function settings_page() {
		?>

		<div class="wrap">
		<form action="options.php" method="post">
		<?php settings_fields( 'fb-dare-settings' ); ?>
		<?php do_settings_sections( 'fb-dare-settings' ); ?>
		<?php submit_button( 'Save' ); ?>
		</form></div><!-- end .wrap -->
		<?php
	}

	/**
	 * Register plugin settings and add settings fields.
	 *
	 * @since  1.1.0
	 * @return  void
	 */
	public function settings_init() {
		register_setting(
			'fb-dare-settings',
			'fb-dare-settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize' ),
			)
		);

		add_settings_section(
			'fb_dare_settings_section',
			__( 'Facebook Account Settings', 'fb-dare' ),
			array( $this, 'settings_section_callback' ),
			'fb-dare-settings'
		);

		// Loop through settings array and add settings fields.
		foreach ( $this->settings as $setting => $key ) {
			add_settings_field(
				$key['id'],
				$key['title'],
				array( $this, 'settings_field_render' ),
				'fb-dare-settings',
				'fb_dare_settings_section',
				array(
					'id'    => $key['id'],
					'title' => $key['title'],
					'label_for' => 'fb-dare-settings[' . $key['id'] . ']',
				)
			);
		}
	}

	/**
	 * Callback for settings section. Contains description.
	 *
	 * @since  1.1.0
	 * @return void
	 */
	public function settings_section_callback() {
		_e( 'Enter settings specific to your Facebook Ad account.', 'fb-dare' );
	}

	/**
	 * Sanitize inputs.
	 *
	 * @since  1.1.0
	 * @param  array $post_data Posted form data.
	 * @return array            Sanitized form data.
	 */
	public function sanitize( $post_data ) {
		$post_data['pixel_id']       = sanitize_text_field( $post_data['pixel_id'] );
		$post_data['access_token']   = sanitize_text_field( $post_data['access_token'] );
		$post_data['application_id'] = sanitize_text_field( $post_data['application_id'] );
		$post_data['ad_account_id']  = sanitize_text_field( $post_data['ad_account_id'] );
		return $post_data;
	}

	/**
	 * Render the setting fields.
	 *
	 * @since  1.1.0
	 * @return void
	 */
	public function settings_field_render( $args ) {
		$options = get_option( 'fb-dare-settings' ); ?>
		<input type="text" name="fb-dare-settings[<?php echo esc_attr( $args['id'] ); ?>]" value="<?php echo esc_html( $options[ $args['id'] ] ); ?>">
		<?php
		// TODO: Add Tooltip
	}

}
