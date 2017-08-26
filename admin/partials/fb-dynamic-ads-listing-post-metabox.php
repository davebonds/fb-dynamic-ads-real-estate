<?php
/**
 * The metabox for fields specific to Facebook Dynamic Ads
 *
 * @link       https://github.com/davebonds/
 * @since      1.1.0
 * @package    FB_Dynamic_Ads_Real_Estate
 * @subpackage FB_Dynamic_Ads_Real_Estate/admin/partials
 * @author     Dave Bonds <db@davebonds.com>
 */

global $post;

/**
 * The array of accepted 'availability' values.
 *
 * @var array
 */
$availability_values = array(
	__( '', 'fb-dare' )                                => '',
	__( 'For Sale [for_sale]', 'fb-dare' )             => 'for_sale',
	__( 'For Rent [for_rent]', 'fb-dare' )             => 'for_rent',
	__( 'Sale Pending [sale_pending]', 'fb-dare' )     => 'sale_pending',
	__( 'Recently Sold [recently_sold]', 'fb-dare' )   => 'recently_sold',
	__( 'Off Market [off_market]', 'fb-dare' )         => 'off_market',
	__( 'Available Soon [available_soon]', 'fb-dare' ) => 'available_soon',
);

/**
 * The array of accepted 'property_type' values.
 *
 * @var array
 */
$property_type_values = array(
	__( '', 'fb-dare' )             => '',
	__( 'Apartment', 'fb-dare' )    => 'apartment',
	__( 'Condo', 'fb-dare' )        => 'condo',
	__( 'House', 'fb-dare' )        => 'house',
	__( 'Land', 'fb-dare' )         => 'land',
	__( 'Manufactured', 'fb-dare' ) => 'manufactured',
	__( 'Townhouse', 'fb-dare' )    => 'townhouse',
);

/**
 * The array of accepted 'listing_type' values.
 *
 * @var array
 */
$listing_type_values = array(
	__( '', 'fb-dare' )                  => '',
	__( 'For Rent by Agent', 'fb-dare' ) => 'for_rent_by_agent',
	__( 'For Rent by Owner', 'fb-dare' ) => 'for_rent_by_owner',
	__( 'For Sale by Agent', 'fb-dare' ) => 'for_sale_by_agent',
	__( 'For Sale by Owner', 'fb-dare' ) => 'for_sale_by_owner',
	__( 'Foreclosed', 'fb-dare' )        => 'foreclosed',
	__( 'New Construction', 'fb-dare' )  => 'new_construction',
	__( 'New Listings', 'fb-dare' )      => 'new_listing',
);

?>

<p><?php _e( 'Use these fields to select values formatted specifically for <A href="https://developers.facebook.com/docs/marketing-api/dynamic-ads-for-real-estate/catalog" target="_blank">Listing Catalogs</a>', 'fb-dare' ); ?></p>

<?php
// Availability. 
echo '<p><label for="fb-listing-availability">' . __( 'Select an <code>availability</code> value', 'fb-dare' ) . ' <span class="required" style="color: red;">*</span></label>';
echo '<select id="fb-listing-availability" name="wp_listings[_fb_listing_availability]">';
foreach ( $availability_values as $availability => $value ) {
	echo '<option value="' . esc_attr( $value ) . '" ' . selected( get_post_meta( $post->ID, '_fb_listing_availability', true ), $value, false ) . '">' . esc_attr( $availability ) . '</option>';
}
echo '</select></p>';

// Property Type.
echo '<p><label for="fb-listing-property_type">' . __( 'Select a <code>property_type</code> value', 'fb-dare' ) . '</label>';
echo '<select id="fb-listing-property_type" name="wp_listings[_fb_listing_property_type]">';
foreach ( $property_type_values as $property_type => $value ) {
	echo '<option value="' . esc_attr( $value ) . '" ' . selected( get_post_meta( $post->ID, '_fb_listing_property_type', true ), $value, false ) . '">' . esc_attr( $property_type ) . '</option>';
}
echo '</select></p>';

// Listing Type.
echo '<p><label for="fb-listing-type">' . __( 'Select a <code>listing_type</code> value', 'fb-dare' ) . '</label>';
echo '<select id="fb-listing-type" name="wp_listings[_fb_listing_type]">';
foreach ( $listing_type_values as $listing_type => $value ) {
	echo '<option value="' . esc_attr( $value ) . '" ' . selected( get_post_meta( $post->ID, '_fb_listing_type', true ), $value, false ) . '">' . esc_attr( $listing_type ) . '</option>';
}
echo '</select></p>';
?>
<em><span class="required" style="color: red;">* </span><?php _e( 'Denotes required field', 'fb-dare' ); ?></em>
