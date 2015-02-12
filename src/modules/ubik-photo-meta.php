<?php // ==== UBIK PHOTO META ==== //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-photo-meta/ubik-photo-meta.php' );

// A simple wrapper for the photo meta function
function pendrell_photo_meta() {

  // Only display in the singular context; everything else is handled by the component
  if ( !is_singular() )
    return;

  $photo_meta = ubik_photo_meta();
  if ( !empty( $photo_meta ) ) {
    ?><section class="entry-after photo-meta">
      <h3><?php _e( 'Image metadata', 'pendrell' ); ?></h3>
      <ul><?php
        foreach ( $photo_meta as $key => $value ) {
          ?><li><?php echo $value; ?></li><?php
        }
      ?></ul>
    </section><?php
  }
}
add_action( 'pendrell_comment_template_before', 'pendrell_photo_meta', 1 );



// Use conditional statements to assign licenses to different types of content with this filter
function pendrell_photo_meta_license( $license ) {
  return $license;
}
//add_filter( 'ubik_photo_meta_license', 'pendrell_photo_meta_license' );



// Use conditional statements to assign a terms of use statement to different types of content with this filter
function pendrell_photo_meta_license_terms( $terms ) {
  return $terms;
}
//add_filter( 'ubik_photo_meta_license_terms', 'pendrell_photo_meta_license_terms' );
