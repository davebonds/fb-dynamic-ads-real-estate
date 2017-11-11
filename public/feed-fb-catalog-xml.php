<?php
/**
 * Template for XML Catalog formatted for FB Dynamic Ads for Real Estate
 * Required fields and documentation at https://developers.facebook.com/docs/marketing-api/dynamic-ads-for-real-estate/catalog
 * 
 * @link       https://github.com/davebonds/
 * @since      1.0.0
 * @package    FB_Dynamic_Ads_Real_Estate
 * @subpackage FB_Dynamic_Ads_Real_Estate/public
 * @author     Dave Bonds <db@davebonds.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Instantiate helper class.
$helpers = new FB_Dynamic_Ads_Real_Estate_Helpers();

// Get IMPress Listings settings.
$wpl_options = get_option( 'plugin_wp_listings_settings' );

// The number of posts to show in the feed.
$num_posts = ( isset( $_GET['numposts'] ) ) ? intval( $_GET['numposts'] ) : get_option( 'posts_per_rss' );

// Optionally query by location taxonomy.
$location = ( isset( $_GET['location'] ) ) ? wp_unslash( sanitize_title_for_query( $_GET['location'] ) ) : '';

// Prepare the query args.
$query_args = array(
	'post_type' => 'listing',
	'showposts' => $num_posts,
	'location'  => $location,
);

// Get the posts.
$posts = query_posts( $query_args );

// The template.
echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '" ?' . '>' . "\r\n";
echo '<listings>' . "\r\n";
echo '<title>' . get_bloginfo( 'name' ) . ' ' . __( 'Dynamic Ads for Real Estate Listing Catalog', 'fb-dare' ) . '</title>' . "\r\n";
echo '<link rel="self" href="' . get_bloginfo( 'url' ) . '" />' . "\r\n";

// The loop.
while ( have_posts() ) : the_post();

	$post_id = get_the_id();

	// Loop through FB required fields and set invalid to true if any return false.
	$invalid = false;
	foreach ( $helpers->required_meta as $required ) {
		if ( ! get_post_meta( $post_id, $required, true ) ) {
			$invalid = true;
		}
	}

	// If any required field is not present, break out of loop and do not output.
	if ( true === $invalid ) {
		continue;
	}

	// Get formatted neighborhood string.
	$neighborhood = $helpers->get_terms_for_catalog( $post_id, 'locations' );
	?>

	<listing>
		<home_listing_id><?php echo esc_html( get_post_meta( $post_id, '_listing_mls', true ) ); ?></home_listing_id>
		<name><?php the_title(); ?></name>
		<availability><?php echo esc_html( get_post_meta( $post_id, '_fb_listing_availability', true ) ); ?></availability>
		<description><?php echo apply_filters( 'fb_dare_feed_description', wp_trim_words( strip_shortcodes( get_the_content() ), 55, '...' ), $post_id ); ?></description>
		<address format="simple">
			<component name="addr1"><?php echo esc_html( get_post_meta( $post_id, '_listing_address', true ) ); ?></component>
			<component name="city"><?php echo esc_html( get_post_meta( $post_id, '_listing_city', true ) ); ?></component>
			<component name="region"><?php echo esc_html( get_post_meta( $post_id, '_listing_state', true ) ); ?></component>
			<component name="country"><?php echo esc_html( get_post_meta( $post_id, '_listing_country', true ) ); ?></component>
			<component name="postal_code"><?php echo esc_html( get_post_meta( $post_id, '_listing_zip', true ) ); ?></component>
		</address>
		<latitude><?php echo esc_html( get_post_meta( $post_id, '_listing_latitude', true ) ); ?></latitude>
		<longitude><?php echo esc_html( get_post_meta( $post_id, '_listing_longitude', true ) ); ?></longitude>
		<?php
		// Neighborhood (uses locations taxonomy).
		echo ( $neighborhood ) ? '<neighborhood>' . esc_html( $neighborhood ) . "</neighborhood>\r\n" : ''; ?>
		<image>
			<url><?php echo esc_url( get_the_post_thumbnail_url( $post_id, 'listings-full' ) ); ?></url>
		</image>
		<?php
		// Listing type.
		echo ( false !== get_post_meta( $post_id, '_fb_listing_type', true ) || '' !== get_post_meta( $post_id, '_fb_listing_type', true ) ) ? '<listing_type>' . esc_html( get_post_meta( $post_id, '_fb_listing_type', true ) ) . "</listing_type>\r\n" : '';
		?>
		<?php
		// Bedrooms.
		echo ( false !== get_post_meta( $post_id, '_listing_bedrooms', true ) || '' !== get_post_meta( $post_id, '_listing_bedrooms', true ) ) ? '<num_beds>' . esc_html( get_post_meta( $post_id, '_listing_bedrooms', true ) ) . "</num_beds>\r\n" : '';
		?>
		<?php
		// Bathrooms.
		echo ( false !== get_post_meta( $post_id, '_listing_bathrooms', true ) || '' !== get_post_meta( $post_id, '_listing_bathrooms', true ) ) ? '<num_baths>' . esc_html( get_post_meta( $post_id, '_listing_bathrooms', true ) ) . "</num_baths>\r\n" : '';
		?>
		<price><?php
		// Price.
		echo esc_html( str_ireplace( array( ',', '$', '&#36;', '£', '&#163;', '€', '&#8364;', '¥', '&#165;', '₱', '&#8369;' ), '', get_post_meta( $post_id, '_listing_price', true ) ) ); ?> <?php echo ( isset( $wpl_options['wp_listings_currency_code'] ) ) ? esc_html( $wpl_options['wp_listings_currency_code'] ) : 'USD'; ?></price>
		<?php
		// Property type.
		echo ( false !== get_post_meta( $post_id, '_fb_listing_property_type', true ) || '' !== get_post_meta( $post_id, '_fb_listing_property_type', true ) ) ? '<property_type>' . esc_html( get_post_meta( $post_id, '_fb_listing_property_type', true ) ) . "</property_type>\r\n" : '';
		?>
		<url><?php the_permalink(); ?></url>
		<?php
		// Year built.
		echo ( false !== get_post_meta( $post_id, '_listing_year_built', true ) || '' !== get_post_meta( $post_id, '_listing_year_built', true ) ) ? '<year_built>' . esc_html( get_post_meta( $post_id, '_listing_year_built', true ) ) . "</year_built>\r\n" : '';
		?>
	</listing>

<?php
endwhile;
echo '</listings>';
