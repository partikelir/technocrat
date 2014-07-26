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
      <h2><?php _e( 'Image Info', 'pendrell' ); ?></h2>
      <p class="image-description">
      <?php
      if ( $metadata['image_meta']['created_timestamp'] )
        printf( __( 'Taken: %s.<br/>', 'pendrell' ), date( get_option( 'date_format' ), $metadata['image_meta']['created_timestamp'] ) );
      if ( $metadata['image_meta']['camera'] )
        printf( __( 'Camera: %s.</br>', 'pendrell' ), $metadata['image_meta']['camera'] );
      if ( $metadata['image_meta']['focal_length'] )
        printf( __( 'Focal length: %s mm.<br/>', 'pendrell' ), $metadata['image_meta']['focal_length'] );
      if ( $metadata['image_meta']['aperture'] )
        printf( __( 'Aperture: f/%s.<br/>', 'pendrell' ), $metadata['image_meta']['aperture'] );

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
        printf( __( 'Shutter speed: %s.<br/>', 'pendrell' ), $pshutter );
      }

      if ( $metadata['image_meta']['iso'] )
        printf( __( 'ISO: %s.<br/>', 'pendrell' ), $metadata['image_meta']['iso'] );

      // Credit/copyright info is contained within $metadata['image_meta']['credit'] and $metadata['image_meta']['copyright'] but isn't used here
      if ( PENDRELL_IMAGE_LICENSE )
        echo pendrell_image_license();

      if ( $metadata['height'] && $metadata['width'] ) {
        printf( __( 'Full size: <a href="%1$s" title="Link to full size image" rel="enclosure">%2$s &times; %3$s px</a>.</br>', 'pendrell' ),
          esc_attr( wp_get_attachment_url( $attachment ) ),
          $metadata['width'],
          $metadata['height']
        );
      }
      ?>
      </p>
    </div>
<?php
  }
} endif;



// Ugly image licensing handler; if WordPress handled licensing information this wouldn't be such a hack!
if ( !function_exists( 'pendrell_image_license' ) ) : function pendrell_image_license() {
  global $post; // Required so we can reference $post->post_parent while testing attachments

  // An array of arrays containing information about various licenses that may be applied to different content
  $licenses = array(
    'cc-by-nc' => array(
      'name'  => 'Creative Commons BY-NC 4.0'
    , 'url'   => 'https://creativecommons.org/licenses/by-nc/4.0/'
    )
  , 'cc-by-nc-nd' => array(
      'name'  => 'Creative Commons BY-NC-ND 4.0'
    , 'url'   => 'https://creativecommons.org/licenses/by-nc-nd/4.0/'
    )
  , 'copyright' => array(
      'name'  => 'Under copyright control'
    , 'url'   => ''
    )
  , 'public-domain' => array(
      'name'  => 'Public domain'
    , 'url'   => 'https://creativecommons.org/publicdomain/zero/1.0/'
    )
  );

  // Test various conditions here... these are always going to be specific to your blog as long as you post different types of content; as such, you will need to custom code conditionals to meet your needs
  if ( has_tag( 'design' ) || ( is_attachment() && has_tag( 'design', $post->post_parent ) ) ) {
    $license = $licenses['cc-by-nc-nd'];
  } elseif ( has_tag( 'photography' ) || ( is_attachment() && has_tag( 'photography', $post->post_parent ) ) ) {
    $license = $licenses['cc-by-nc'];
  } else {
    $license = '';
  }

  if ( PENDRELL_IMAGE_LICENSE_TERMS ) {
    $terms = sprintf( __( 'see my <a href="%s">terms of use</a> for more info', 'pendrell' ), PENDRELL_IMAGE_LICENSE_TERMS );
  } else {
    $terms = '';
  }

  if ( !empty( $license ) && !empty( $terms ) ) {
    $html = sprintf( '<a href="%1$s" itemprop="license" rel="license">%2$s</a>; %3$s', $license['url'], $license['name'], $terms );
  } else {
    $html = '';
    if ( !empty( $license ) )
      $html = sprintf( '<a href="%1$s" itemprop="license" rel="license">%2$s</a>', $license['url'], $license['name'] );
    if ( !empty( $terms ) )
      $html = $terms;
  }

  if ( !empty( $html ) )
    $html = __( 'License: ', 'pendrell' ) . $html . '.<br/>';

  return $html;
} endif;