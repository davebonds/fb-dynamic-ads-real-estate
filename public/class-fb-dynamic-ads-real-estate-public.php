<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    FB_Dynamic_Ads_Real_Estate
 * @subpackage FB_Dynamic_Ads_Real_Estate/public
 * @author     Dave Bonds <db@davebonds.com>
 */
class FB_Dynamic_Ads_Real_Estate_Public {

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
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		global $fb_dare_events;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.2.0
	 */
	public function enqueue_scripts() {
		/**
		 * An instance of this class should be passed to the run() function
		 * defined in FB_Dynamic_Ads_Real_Estate_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The FB_Dynamic_Ads_Real_Estate_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		// Get our options to get the FB Pixel ID and don't load our script if there is none.
		$options = get_site_option( 'fb-dare-settings' );

		if ( ! $options['pixel_id'] ) {
			return;
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fb-dare-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add event with params to global events list.
	 *
	 * @param string $event  Event name, eg. "PageView"
	 * @param array  $params Optional. Associated array of event parameters in 'param_name' => 'param_value' format.
	 * @param int    $delay  Optional. If set, event will be fired with desired delay in seconds.
	 *
	 * @since  1.2.0
	 */
	public function add_event( $event, $params = array(), $delay = 0 ) {
		global $fb_dare_events;

		$params = apply_filters( 'fb_dare_event_params', $params, $event );

		$sanitized = array();

		// sanitize param names and its values
		foreach ( $params as $name => $value ) {

			// skip empty but not zero values.
			if ( empty( $value ) && ! is_numeric( $value ) ) {
				continue;
			}
			$key               = esc_js( $name );
			$sanitized[ $key ] = $value;

		}

		$fb_dare_events[] = array(
			'type'   => 'track',
			'name'   => $event,
			'params' => $sanitized,
			'delay'  => $delay,
		);

	}

	/**
	 * Add init event to global events list.
	 *
	 * @since  1.2.0
	 */
	public function pixel_init_event() {
		global $fb_dare_events;

		// Get our options to get the FB Pixel ID.
		$options = get_site_option( 'fb-dare-settings' );

		if ( ! $options['pixel_id'] ) {
			return;
		}

		// Add the init event.
		$fb_dare_events[] = array(
			'type'   => 'init',
			'name'   => $options['pixel_id'],
			'params' => array(),
		);
	}

	/**
	 * Add standard events to global events list.
	 *
	 * @since  1.2.0
	 */
	public function pixel_events() {
		global $fb_dare_events;
		global $post;

		// Get our options to get the FB Pixel ID.
		$options = get_site_option( 'fb-dare-settings' );

		if ( ! $options['pixel_id'] ) {
			return;
		}

		// Build array of possible listing taxonomies.
		$taxonomies = array_merge(
			array( 'locations', 'property-types', 'status', 'features' ),
			(array) get_option( 'wp_listings_taxonomies' )
		);

		// Add standard PageView event.
		$this->add_event( 'PageView' );

		// If singular listing add ViewContent event with required params.
		if ( is_singular( array( 'listing' ) ) ) {
			$event = 'ViewContent';
			$params = array(
				'content_type' => 'home_listing',
				'content_ids'   => array( get_post_meta( $post->ID, '_listing_mls', true ) ),
			);
			$this->add_event( $event, $params, 500 );
		} elseif ( is_post_type_archive( array( 'listing' ) ) || is_tax( $taxonomies ) ) {
			// Get the post IDs in the current query.
			global $wp_query;
			$ids = wp_list_pluck( $wp_query->posts, 'ID' );

			$event = 'Search';
			$params = array(
				'content_type' => 'home_listing',
				'content_ids'  => $this->get_listing_search_mls_ids( $ids ),
				'city'         => $this->get_listing_search_city( $ids ),
				'region'       => $this->get_listing_search_region( $ids ),
				'country'      => $this->get_listing_search_country( $ids ),
			);
			$this->add_event( $event, $params, 500 );
		} else {
			// Add standard ViewContent event with post/page title.
			$event = 'ViewContent';
			$params = array(
				'content_name' => get_the_title( $post->ID ),
				'content_type' => get_post_type( $post->ID ),
				'content_ids'  => array( $post->ID ),
			);
			$this->add_event( $event, $params, 500 );
		}
	}

	/**
	 * Add our events to our script using wp_localize_script
	 * Applies fb_dare_prepared_events filter.
	 *
	 * @since  1.2.0
	 */
	public function output_pixel_events() {
		global $fb_dare_events;

		// Allow external plugins modify events.
		$fb_dare_events = apply_filters( 'fb_dare_prepared_events', $fb_dare_events );

		if ( empty( $fb_dare_events ) ) {
			return;
		}

		wp_localize_script( $this->plugin_name, 'dare_events', $fb_dare_events );
	}

	/**
	 * Returns an array of listing ID values from _listing_mls post meta key
	 *
	 * @param  array $ids         Post IDs
	 * @return array $listing_ids MLS Listing IDs
	 *
	 * @since  1.2.0
	 */
	public function get_listing_search_mls_ids( $ids ) {
		foreach ( $ids as $id ) {
			$listing_ids[] = get_post_meta( $id, '_listing_mls', true );
		}

		return $listing_ids;
	}

	/**
	 * Returns the value of _listing_city post meta key for the first post ID in the array.
	 *
	 * @param  array  $ids  Post IDs
	 * @return string       City
	 *
	 * @since  1.2.0
	 */
	public function get_listing_search_city( $ids ) {
		// Return the first one, since only one value is allowed
		return get_post_meta( $ids[0], '_listing_city', true );
	}

	/**
	 * Returns the value of _listing_state post meta key for the first post ID in the array.
	 *
	 * @param  array  $ids  Post IDs
	 * @return string       State
	 *
	 * @since  1.2.0
	 */
	public function get_listing_search_region( $ids ) {
		// Return the first one, since only one value is allowed
		return get_post_meta( $ids[0], '_listing_state', true );
	}

	/**
	 * Returns the value of _listing_country post meta key for the first post ID in the array.
	 *
	 * @param  array  $ids  Post IDs
	 * @return string       Country
	 *
	 * @since  1.2.0
	 */
	public function get_listing_search_country( $ids ) {
		// Return the first one, since only one value is allowed
		return get_post_meta( $ids[0], '_listing_country', true );
	}
}
