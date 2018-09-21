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
	 * The wp_options for the plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      array    $options    The plugin options from wp_options.
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/vendor/webdevstudios/cmb2/init.php';

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->options     = get_option( 'fbdare_options' );
		$this->post_type   = $this->options['fbdare_post_type'];

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
		require $template;
	}

	/**
	 * Register metabox for the listings post type.
	 *
	 * @since  1.1.0
	 */
	public function register_meta_box() {
		add_meta_box( 'fb_listing_catalog_metabox', __( 'Facebook Dynamic Ads', 'fb-dare' ), array( $this, 'fb_listing_catalog_metabox' ), $this->post_type, 'side', 'high' );
	}

	/**
	 * Require metabox HTML.
	 *
	 * @since  1.1.0
	 */
	public function fb_listing_catalog_metabox() {
		require plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/fb-dynamic-ads-listing-post-metabox.php';
	}

	/**
	 * Register submenu page.
	 *
	 * @since  1.1.0
	 */
	public function admin_menu() {
		$prefix = 'fbdare_';
		/**
		 * Registers options page menu item and form.
		 */
		$cmb_options = new_cmb2_box( array(
			'id'           => $prefix . 'options_page',
			'title'        => esc_html__( 'Facebook Dynamic Ads for Real Estate Options', 'fb-dare' ),
			'object_types' => array( 'options-page' ),
			'capability'   => 'manage_options',
			'option_key'   => $prefix . 'options', // The option key and admin menu page slug.
			'parent_slug'  => 'options-general.php', // Make options page a submenu item of the tools menu.
			'menu_title'   => esc_html__( 'Facebook Dynamic Ads', 'fb-dare' ),
			'display_cb'   => array( $this, 'admin_options_page_content' ),
		) );

		$cmb_options->add_field( array(
			'name'    => esc_html__( 'Facebook Pixel ID', 'fb-dare' ),
			'desc'    => esc_html__( 'Enter your Facebook Pixel ID.', 'fb-dare' ),
			'default' => '',
			'id'      => $prefix . 'pixel_id',
			'type'    => 'text_medium',
		) );

		$cmb_options->add_field( array(
			'name'    => esc_html__( 'InitiateCheckout Event Selector', 'fb-dare' ),
			'desc'    => esc_html__( 'Enter the selector(s), comma separated, of the element(s) for when a user "favorites" or saves a property. i.e. a save property button or share button.', 'fb-dare' ),
			'default' => '',
			'id'      => $prefix . 'initiate_checkout',
			'type'    => 'text_medium',
		) );

		$cmb_options->add_field( array(
			'name'    => esc_html__( 'Purchase Event Selector', 'fb-dare' ),
			'desc'    => esc_html__( 'Enter the selector(s), comma separated, of the element(s) for when a user contacts an agent about a property. i.e. a form submit button.', 'fb-dare' ),
			'default' => '',
			'id'      => $prefix . 'purchase',
			'type'    => 'text_medium',
		) );

		$cmb_options->add_field( array(
			'name'       => esc_html__( 'Select the post type for properties.', 'fb-dare' ),
			'id'         => $prefix . 'post_type',
			'type'       => 'select',
			'options_cb' => array( $this, 'get_post_types' ),
		) );

	}

	/**
	 * Save custom meta box data
	 *
	 * @param int $post_id The post ID.
	 * @since 1.3.0
	 * @return void
	 */
	public function save_property_meta( $post_id ) {
		$post_type = get_post_type( $post_id );
		if ( $this->post_type !== $post_type ) {
			return;
		}

		if ( isset( $_POST['_fb_listing_availability'] ) ) {
			update_post_meta( $post_id, '_fb_listing_availability', sanitize_text_field( $_POST['_fb_listing_availability'] ) );
		}

		if ( isset( $_POST['_fb_listing_property_type'] ) ) {
			update_post_meta( $post_id, '_fb_listing_property_type', sanitize_text_field( $_POST['_fb_listing_property_type'] ) );
		}

		if ( isset( $_POST['_fb_listing_type'] ) ) {
			update_post_meta( $post_id, '_fb_listing_type', sanitize_text_field( $_POST['_fb_listing_type'] ) );
		}

	}

	/**
	 * Returns public post types for selection.
	 *
	 * @since  1.3.0
	 * @return array The registered public post types with key value pair of name => label. Excludes posts, pages, and media.
	 */
	public function get_post_types() {
		$public_post_types = get_post_types( array( 'public' => true ), 'objects' );
		$post_types        = array();
		foreach ( $public_post_types as $post_type ) {
			if ( 'attachment' !== $post_type->name || 'post' !== $post_type->name || 'page' !== $post_type->name ) {
				$post_types[ $post_type->name ] = $post_type->label;
			}
		}
		return $post_types;
	}

}
