<?php // === PENDRELL ADMIN FUNCTIONS === //

// Add custom images sizes to the media uploader dropdown; @TODO: revise these styles and move most of this to the portfolio module
if ( !function_exists( 'pendrell_image_sizes' ) ) : function pendrell_image_sizes( $sizes ) {

	// Cheap hack to keep "full size" at the bottom of the dropdown: unset and reset it after adding our custom sizes
	unset ( $sizes['medium'] );
	unset ( $sizes['large'] );
	unset ( $sizes['full'] );

	// The full list
  $sizes['medium-third'] = __( 'Medium third', 'pendrell');
  $sizes['medium-third-cropped'] = __( 'Medium third cropped', 'pendrell');
  $sizes['medium-half'] = __( 'Medium half', 'pendrell');
  $sizes['medium-half-cropped'] = __( 'Medium half cropped', 'pendrell');
	$sizes['medium'] = __( 'Medium', 'pendrell');
  $sizes['medium-cropped'] = __( 'Medium cropped', 'pendrell');
	$sizes['large'] = __( 'Large', 'pendrell');
  $sizes['large-cropped'] = __( 'Large cropped', 'pendrell');
	$sizes['full'] = __( 'Full', 'pendrell');
	return $sizes;

} endif;
add_filter( 'image_size_names_choose', 'pendrell_image_sizes' );
