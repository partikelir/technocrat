<?php // ==== POST FORMATS ==== //

// == SIDEBAR == //

// Sidebar filter; removes sidebar for certain post formats
if ( !function_exists( 'pendrell_post_formats_sidebar' ) ) : function pendrell_post_formats_sidebar( $sidebar ) {
  if ( ( is_singular() && has_post_format( array( 'aside', 'link', 'quote', 'status' ) ) ) )
    $sidebar = false;
  return $sidebar;
} endif;
if ( PENDRELL_POST_FORMATS )
	add_filter( 'pendrell_sidebar', 'pendrell_post_formats_sidebar' );



// == VIEWS == //

// Add post formats to the views taxonomy
function pendrell_post_formats_views( $taxonomies ) {
  $taxonomies[] = 'post_format';
  return $taxonomies;
}
if ( PENDRELL_POST_FORMATS )
	add_filter( 'ubik_views_taxonomies', 'pendrell_post_formats_views' );



// == LINKS == //

// Get link metadata; assumes WP-Post-Formats or equivalent is in use
if ( !function_exists( 'pendrell_link_metadata' ) ) : function pendrell_link_meta( $contents ) {

  // Link post format handling
  if ( has_post_format( 'link' ) ) {
    $link = get_post_meta( get_the_ID(), '_format_link_url', true );
    if ( !empty( $link ) )
      $contents = '<div class="link"><a href="' . $link . '" rel="bookmark">' . str_replace( array( 'http://', 'https://' ), '', $link ) . '</a></div>';
  }
  return $contents;
} endif;
if ( PENDRELL_POST_FORMATS )
	add_action( 'pendrell_entry_header_meta', 'pendrell_link_meta', 99 );



// == QUOTATIONS == //

// Generate markup for quotations; assumes WP Post Formats or equivalent is in use
if ( !function_exists( 'pendrell_quotation_markup' ) ) : function pendrell_quotation_markup( $content ) {

	// Return early if a password is required or this is not a quote or quotation post format post
	if ( post_password_required() || ( !has_post_format( 'quote' ) && !has_post_format( 'quotation' ) ) )
		return $content;

	global $post;

	// Fetch metadata
	$name 	= get_post_meta( $post->ID, '_format_quote_source_name', true );
	$title 	= get_post_meta( $post->ID, '_format_quote_source_title', true );
	$date 	= get_post_meta( $post->ID, '_format_quote_source_date', true );
	$url 		= get_post_meta( $post->ID, '_format_quote_source_url', true );

	// Check to see if we have what we need to build the source
	if ( !empty( $name ) || !empty( $title ) || !empty( $url ) ) {

		$source = array();

		if ( !empty( $name ) ) {
			if ( empty( $title ) && !empty( $url ) ) {
				$source[] = '<a href="' . $url . '">' . $name . '</a>';
			} else {
				$source[] = $name;
			}
		}

		if ( !empty( $title ) ) {
			if ( !empty( $url ) ) {
				$source[] = '<cite><a href="' . $url . '">' . $title . '</a></cite>';
			} else {
				$source[] = '<cite>' . $title . '</cite>';
			}
		}

		// If we only have the URL at least make it look nice
		if ( !empty( $url ) && empty( $name ) && empty( $title ) )
			$source[] = '<a href="' . $url . '">' . parse_url( $url, PHP_URL_HOST ) . '</a>';

		if ( $date )
			$source[] = $date;

		// Concatenate with commas
		$source = implode( ', ', $source );
	} else {
		$source = '';
	}

	// Cite attribute for blockquote element
	if ( !empty( $url ) ) {
		$cite = ' cite="' . $url . '"';
	} else {
		$cite = '';
	}

	// Opening blockquote element
	$before = '<blockquote' . $cite . '>';

	// Source markup
	if ( !empty( $source ) ) {
		$after = '<footer>' . $source . '</footer></blockquote>';
	} else {
		$after = '</blockquote>';
	}

	return $before . $content . $after;

} endif;
if ( PENDRELL_POST_FORMATS )
	add_filter( 'the_content', 'pendrell_quotation_markup' );
