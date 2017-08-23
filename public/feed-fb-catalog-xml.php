<?php
/**
 * Template for XML Catalog formatted for Facebook Dynamic Ads for Real Estate
 * Required fields and documentation at https://developers.facebook.com/docs/marketing-api/dynamic-ads-for-real-estate/catalog
 * 
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    Facebook_Dynamic_Ads_Impress_Listings
 * @subpackage Facebook_Dynamic_Ads_Impress_Listings/public
 * @author     Dave Bonds <db@davebonds.com>
 */

// The number of posts to show in the feed.
$num_posts = ( isset( $_GET['numposts'] ) ) ? intval( $_GET['numposts'] ) : get_option( 'posts_per_rss' );

// Optionally query by status taxonomy
$status    = ( isset( $_GET['status'] ) ) ? $_GET['status'] : '';

// Prepare the query args.
$query_args = array(
	'post_type' => 'listing',
	'showposts' => $num_posts,
	'status'    => $status,
);

// Get the posts.
$posts = query_posts( $query_args );

// Facebook required fields.
$required_meta = array(
	'_listing_mls',
	'_listing_address',
	'_listing_city',
	'_listing_state',
	'_listing_country',
	'_listing_zip',
	'_listing_latitude',
	'_listing_longitude',
	'_listing_price',

);

// Get IMPress Listings settings.
$wpl_options = get_option( 'plugin_wp_listings_settings' );

// The template.
echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '" ?' . '>' . "\r\n";
echo '<listings>' . "\r\n";
echo '<title>' . get_bloginfo( 'name' ) . ' Dynamic Ads for Real Estate Feed</title>' . "\r\n";
echo '<link rel="self" href="' . get_bloginfo( 'url' ) . '">' . "\r\n";

while ( have_posts() ) : the_post();
	$post_id = get_the_id();

	// Loop through FB required fields and set invalid to true if any return false.
	$invalid = false;
	foreach ( $required_meta as $required ) {
		if ( ! get_post_meta( $post_id, $required, true ) ) {
			$invalid = true;
		}
	}

	// If any required field is not present, break out of loop and do not output.
	if ( true === $invalid ) {
		continue;
	}

	// Get formatted availability string.
	$availability = wp_listings_fb_catalog_get_availability( $post_id );
	?>

	<listing>
		<home_listing_id><?php echo get_post_meta( $post_id, '_listing_mls', true ); ?></home_listing_id>
		<name><?php the_title(); ?></name>
		<availability><?php echo $availability; ?></availability>
		<description><?php echo wp_strip_all_tags( get_the_excerpt(), true ); ?></description>
		<address format="simple">
			<component name="addr1"><?php echo get_post_meta( $post_id, '_listing_address', true ); ?></component>
			<component name="city"><?php echo get_post_meta( $post_id, '_listing_city', true ); ?></component>
			<component name="region"><?php echo get_post_meta( $post_id, '_listing_state', true ); ?></component>
			<component name="country"><?php echo get_post_meta( $post_id, '_listing_country', true ); ?></component>
			<component name="postal_code"><?php echo get_post_meta( $post_id, '_listing_zip', true ); ?></component>
		</address>
		<latitude><?php echo get_post_meta( $post_id, '_listing_latitude', true ); ?></latitude>
		<longitude><?php echo get_post_meta( $post_id, '_listing_longitude', true ); ?></longitude>
		<neighborhood><?php echo get_post_meta( $post_id, '_listing_city', true ); ?></neighborhood>
		<image>
			<url><?php echo get_the_post_thumbnail_url( $post_id, 'listings-full' ); ?></url>
		</image>
		<!-- Not currently required \\ <listing_type></listing_type> -->
		<?php echo ( ! get_post_meta( $post_id, '_listing_bedrooms', true ) ) ? '' : '<num_beds>' . get_post_meta( $post_id, '_listing_bedrooms', true ) . "</num_beds>\r\n"; ?>
		<?php echo ( ! get_post_meta( $post_id, '_listing_bathrooms', true ) ) ? '' : '<num_baths>' . get_post_meta( $post_id, '_listing_bathrooms', true ) . "</num_baths>\r\n"; ?>
		<price><?php echo str_replace( '$', '', get_post_meta( $post_id, '_listing_price', true ) ); ?> <?php echo ( isset( $wpl_options['wp_listings_display_currency_code'] ) ) ? $wpl_options['wp_listings_display_currency_code'] : 'USD'; ?></price>
		<!-- Not currently required \\ <property_type>house</property_type> -->
		<url><?php the_permalink(); ?></url>
		<?php echo ( ! get_post_meta( $post_id, '_listing_year_built', true ) ) ? '' : '<year_built>' . get_post_meta( $post_id, '_listing_year_built', true ) . "</year_built>\r\n"; ?>

	</listing>

<?php
endwhile;
echo '</listings>';

/**
 * Returns the formatted availability based on status taxonomy.
 * TODO: 
 *
 * @param  string $post_id The post ID.
 * @return string    The accepted values for availability.
 */
function wp_listings_fb_catalog_get_availability( $post_id ) {
	if ( null === $post_id ) {
		return;
	}

	return 'for_sale';
}
