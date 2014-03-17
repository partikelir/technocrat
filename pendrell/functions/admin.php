<?php // === PENDRELL ADMIN FUNCTIONS === //

// Add custom images sizes to the media uploader dropdown
function pendrell_image_sizes( $sizes ) {
	// Cheap hack to keep "full size" at the bottom of the dropdown: unset and reset it after adding our custom sizes
	unset ( $sizes['medium'] );
	unset ( $sizes['large'] );
	unset ( $sizes['full'] );
	$sizes['third-width'] = __( 'Third Width', 'pendrell');
	$sizes['third-width-cropped'] = __( 'Third Width Cropped', 'pendrell');
	$sizes['medium'] = __( 'Medium Size', 'pendrell');
	$sizes['half-width'] = __( 'Half Width', 'pendrell');
	$sizes['half-width-cropped'] = __( 'Half Width Cropped', 'pendrell');
	$sizes['large'] = __( 'Large Size', 'pendrell');
	$sizes['full-width'] = __( 'Full Width', 'pendrell');
	$sizes['full-width-cropped'] = __( 'Full Width Cropped', 'pendrell');
	$sizes['full'] = __( 'Full Size', 'pendrell');
	return $sizes;
}
add_filter( 'image_size_names_choose', 'pendrell_image_sizes' );

?>