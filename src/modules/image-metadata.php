<?php // ==== IMAGE METADATA ==== //

// Display EXIF data for photographs
if ( !function_exists( 'pendrell_image_meta' ) ) : function pendrell_image_meta() {

  // Exit early if we aren't displaying a singular image attachment or image format post
  if ( !is_singular() || ( is_attachment() && !wp_attachment_is_image() ) || ( is_singular() && !is_attachment() && !has_post_format( 'image' ) ) )
    return;

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
add_action( 'pendrell_entry_meta_after', 'pendrell_image_meta' );



// Ugly image licensing handler; if WordPress handled licensing information this wouldn't be such a hack!
if ( !function_exists( 'pendrell_image_license' ) ) : function pendrell_image_license() {
  global $post; // Required so we can reference $post->post_parent while testing attachments

  // These variables are set in functions-config.php
  global $pendrell_image_license_cats, $pendrell_image_license_tags, $pendrell_image_license_terms;

  // An array of arrays containing information about various licenses that may be applied to different content
  $licenses = array(
    'cc-by' => array(
      'name'  => 'Creative Commons BY 4.0'
    , 'url'   => 'https://creativecommons.org/licenses/by/4.0/'
    )
  , 'cc-by-nd' => array(
      'name'  => 'Creative Commons BY-ND 4.0'
    , 'url'   => 'https://creativecommons.org/licenses/by-nd/4.0/'
    )
  , 'cc-by-sa' => array(
      'name'  => 'Creative Commons BY-SA 4.0'
    , 'url'   => 'https://creativecommons.org/licenses/by-sa/4.0/'
    )
  , 'cc-by-nc' => array(
      'name'  => 'Creative Commons BY-NC 4.0'
    , 'url'   => 'https://creativecommons.org/licenses/by-nc/4.0/'
    )
  , 'cc-by-nc-nd' => array(
      'name'  => 'Creative Commons BY-NC-ND 4.0'
    , 'url'   => 'https://creativecommons.org/licenses/by-nc-nd/4.0/'
    )
  , 'cc-by-nc-sa' => array(
      'name'  => 'Creative Commons BY-NC-SA 4.0'
    , 'url'   => 'https://creativecommons.org/licenses/by-nc-sa/4.0/'
    )
  , 'public-domain' => array(
      'name'  => 'Public domain'
    , 'url'   => 'https://creativecommons.org/publicdomain/zero/1.0/'
    )
  , 'copyright' => array(
      'name'  => 'under copyright control'
    , 'url'   => 'https://en.wikipedia.org/wiki/Copyright'
    )
  );

  // Blank variables
  $html = '';
  $license = '';
  $terms = $pendrell_image_license_terms;

  // Test various conditions here... set category/tag and license pairs in `functions-config.php`
  if ( is_array( $pendrell_image_license_cats ) ) {
    foreach ( $pendrell_image_license_cats as $cat => $cat_license ) {
      if ( in_category( $cat ) || ( is_attachment() && in_category( $cat, $post->post_parent ) ) ) {
        $license = $licenses[$cat_license];
      }
    }
  }
  if ( is_array( $pendrell_image_license_tags ) ) {
    foreach ( $pendrell_image_license_tags as $tag => $tag_license ) {
      if ( has_tag( $tag ) || ( is_attachment() && has_tag( $tag, $post->post_parent ) ) ) {
        $license = $licenses[$tag_license];
      }
    }
  }

  // Construct the license statement
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
