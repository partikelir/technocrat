<?php // === PENDRELL ADMIN FUNCTIONS === //

// Add custom images sizes to the media uploader dropdown; @TODO: revise these styles and move most of this to the portfolio module
function pendrell_image_sizes( $sizes ) {

	// Cheap hack to keep "full size" at the bottom of the dropdown: unset and reset it after adding our custom sizes
	unset ( $sizes['medium'] );
	unset ( $sizes['large'] );
	unset ( $sizes['full'] );

	// The full list
  $sizes['medium-300'] = __( 'Medium 300', 'pendrell');
  $sizes['medium-300-cropped'] = __( 'Medium 300 cropped', 'pendrell');
  $sizes['medium-465'] = __( 'Medium 465', 'pendrell');
  $sizes['medium-465-cropped'] = __( 'Medium 465 cropped', 'pendrell');
	$sizes['medium'] = __( 'Medium', 'pendrell');
  $sizes['medium-624-cropped'] = __( 'Medium cropped', 'pendrell');
	$sizes['large'] = __( 'Large', 'pendrell');
  $sizes['large-960-cropped'] = __( 'Large cropped', 'pendrell');
	$sizes['full'] = __( 'Full', 'pendrell');
	return $sizes;

}
add_filter( 'image_size_names_choose', 'pendrell_image_sizes' );
