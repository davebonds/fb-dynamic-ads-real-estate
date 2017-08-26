<?php
/**
 * Helper functions used for various plugin tasks.
 *
 * This class contains helper functions used within admin or public.
 *
 * @link       https://github.com/davebonds/
 * @since      1.1.0
 * @package    Facebook_Dynamic_Ads_Real_Estate
 * @subpackage Facebook_Dynamic_Ads_Real_Estate/includes
 * @author     Dave Bonds <db@davebonds.com>
 */
class Facebook_Dynamic_Ads_Real_Estate_Helpers {

	/**
	 * Facebook required meta fields for listing catalog.
	 *
	 * @var array
	 */
	public $required_meta = array(
		'_listing_mls',
		'_listing_address',
		'_listing_city',
		'_listing_state',
		'_listing_country',
		'_listing_zip',
		'_listing_latitude',
		'_listing_longitude',
		'_listing_price',
		'_fb_listing_availability',
	);

	/**
	 * Returns the formatted terms based on given taxonomy.
	 *
	 * @since  1.1.0
	 * @param  string $post_id   The post ID.
	 * @param  string $taxonomy  The taxonomy to get terms for.
	 * @param  bool   $single    True to return a single term or false for comma separated list. Default false.
	 * @return string            The comma separated string of terms or a single term.
	 */
	public function get_terms_for_catalog( $post_id = null, $taxonomy = null, $single = false ) {
		if ( null === $post_id || null === $taxonomy ) {
			return;
		}

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( false === $terms || is_wp_error( $terms ) ) {
			return false;
		}

		$terms_array = array();
		foreach ( $terms as $term ) {
			if ( true === $single ) {
				return $term->name;
			} else {
				$terms_array[] = $term->name;
			}
		}

		$terms_csv = implode( ', ', $terms_array );

		return $terms_csv;
	}

}
