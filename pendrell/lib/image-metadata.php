<?php // ==== IMAGE METADATA ==== //

// Image info wrapper for image attachments and image format posts
if ( !function_exists( 'pendrell_image_meta' ) ) : function pendrell_image_meta() {
  if (
    ( is_attachment() && wp_attachment_is_image() )
    || ( is_singular() && has_post_format( 'image' ) )
  ) {
    pendrell_image_info();
  }
} endif;
add_filter( 'pendrell_entry_meta_after', 'pendrell_image_meta', 11 );



// Display EXIF data for photographs
if ( !function_exists( 'pendrell_image_info' ) ) : function pendrell_image_info() {
  global $post;

  // Two scenarios to handle: regular attachments and image format posts with a featured image
  if ( is_attachment() && wp_attachment_is_image() ) {
    $attachment = $post->ID;
  } else {
    $attachment = get_post_thumbnail_id();
  }

  // Fetch metadata
  if ( $attachment )
    $metadata = wp_get_attachment_metadata( $attachment );

  if ( !empty( $metadata['image_meta'] ) ) {
    ?><div class="image-info">
      <h3><?php _e( 'Image Info', 'pendrell' ); ?></h3>
      <div class="image-description">
      <?php if ( $metadata['height'] && $metadata['width'] ) {
        printf( __( 'Full Size: <a href="%1$s" title="Link to full size image" rel="enclosure">%2$s &times; %3$s px</a></br>', 'pendrell' ),
          esc_attr( wp_get_attachment_url( $attachment ) ),
          $metadata['width'],
          $metadata['height']
        );
      }
      if ( $metadata['image_meta']['created_timestamp'] )
        printf( __( 'Taken: %s<br/>', 'pendrell' ), date( get_option( 'date_format' ), $metadata['image_meta']['created_timestamp'] ) );
      if ( $metadata['image_meta']['camera'] )
        printf( __( 'Camera: %s</br>', 'pendrell' ), $metadata['image_meta']['camera'] );
      if ( $metadata['image_meta']['focal_length'] )
        printf( __( 'Focal Length: %s mm<br/>', 'pendrell' ), $metadata['image_meta']['focal_length'] );
      if ( $metadata['image_meta']['aperture'] )
        printf( __( 'Aperture: f/%s<br/>', 'pendrell' ), $metadata['image_meta']['aperture'] );

      // Based on http://technology.mattrude.com/2010/07/display-exif-data-on-wordpress-gallery-post-image-2/
      if ( $metadata['image_meta']['shutter_speed'] ) {

        $image_shutter_speed = $metadata['image_meta']['shutter_speed'];

        if ( $image_shutter_speed > 0 ) {
          if ( ( 1 / $image_shutter_speed ) > 1 ) {
            if ( ( number_format( (1 / $image_shutter_speed ), 1 ) ) == 1.3
            or number_format( ( 1 / $image_shutter_speed ), 1 ) == 1.5
            or number_format( ( 1 / $image_shutter_speed ), 1 ) == 1.6
            or number_format( ( 1 / $image_shutter_speed ), 1 ) == 2.5) {
              $pshutter = '1/' . number_format( ( 1 / $image_shutter_speed ), 1, '.', '') . ' ' . __( 'sec', 'pendrell');
            } else {
              $pshutter = '1/' . number_format( ( 1 / $image_shutter_speed ), 0, '.', '') . ' ' . __( 'sec', 'pendrell' );
            }
          } else {
            $pshutter = $image_shutter_speed . ' ' . __( 'sec', 'pendrell' );
          }
        }
        echo __( 'Shutter Speed: ', 'pendrell' ) . $pshutter . '<br/>';
      }

      if ( $metadata['image_meta']['iso'] )
        echo __( 'ISO/Film: ', 'pendrell') . $metadata['image_meta']['iso'] . '<br/>';

      // These don't work as expected and it's probably too much trouble to fix
      // $metadata['image_meta']['credit']
      // $metadata['image_meta']['copyright'] ?>
      </div>
    </div>
<?php
  }
} endif;
