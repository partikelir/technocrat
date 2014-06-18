<?php // ==== POST FORMATS ==== //

// == QUOTATIONS == //

// Get quotation metadata; assumes WP Post Formats or equivalent is in use
if ( !function_exists( 'pendrell_quotation_metadata' ) ) : function pendrell_quotation_metadata() {

	global $post;

	$source_name = get_post_meta( $post->ID, '_format_quote_source_name', true );
	$source_title = get_post_meta( $post->ID, '_format_quote_source_title', true );
	$source_date = get_post_meta( $post->ID, '_format_quote_source_date', true );
	$source_url = get_post_meta( $post->ID, '_format_quote_source_url', true );

	if ( $source_name || $source_title || $source_url ) {

		$citation = array();

		if ( $source_name ) {
			if ( !$source_title && $source_url ) {
				$citation[] = '<a href="' . $source_url . '">' . $source_name . '</a>';
			} else {
				$citation[] = $source_name;
			}
		}

		if ( $source_title ) {
			if ( $source_url ) {
				$citation[] = '<cite><a href="' . $source_url . '">' . $source_title . '</a></cite>';
			} else {
				$citation[] = '<cite>' . $source_title . '</cite>';
			}
		}

		// If we only have the URL at least make it look nice
		if ( $source_url && !$source_name && !$source_title ) {
			$citation[] = '<a href="' . $source_url . '">' . parse_url( $source_url, PHP_URL_HOST ) . '</a>';
		}

		if ( $source_date ) {
			$citation[] = $source_date;
		}

		// Concatenate with commas
		$citation = implode( ', ', $citation );
	} else {
		$citation = '';
	}

	$source = array(
		'citation'	=> $citation,
		'date'			=> $source_date,
		'name'			=> $source_name,
		'title'			=> $source_title,
		'url'				=> $source_url
	);

	return $source;
} endif;



// Get quotation metadata; assumes WP Post Formats or equivalent is in use
if ( !function_exists( 'pendrell_link_metadata' ) ) : function pendrell_link_metadata() {

	global $post;

	$link_url = get_post_meta( get_the_ID(), '_format_link_url', true );

	if ( !empty( $link_url ) ) {
		?><footer class="entry-link"><a href="<?php echo $link_url; ?>" rel="bookmark"><?php echo $link_url; ?></a></footer><?php
	}
} endif;
