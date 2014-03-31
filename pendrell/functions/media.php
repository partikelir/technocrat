<?php // ==== MEDIA ==== //

// Image info wrapper
function pendrell_media_image_meta() {
  if ( is_attachment() && wp_attachment_is_image() ) {
    pendrell_media_image_info();
  }
}
add_filter( 'pendrell_entry_meta_after', 'pendrell_media_image_meta', 11 );



// Display EXIF data for photographs
function pendrell_media_image_info() {

	// Fetch metadata
	$metadata = wp_get_attachment_metadata();

	if ( $metadata['image_meta'] ) {
		?><div class="image-info">
			<h3><?php _e( 'Image Info', 'pendrell' ); ?></h3>
			<div class="image-description">
			<?php if ( $metadata['height'] && $metadata['width'] ) {
					printf( __( 'Full Size: <a href="%1$s" title="Link to full size image">%2$s &times; %3$s</a></br>', 'pendrell' ),
						esc_attr( wp_get_attachment_url() ),
						$metadata['width'],
						$metadata['height']
					);
				}
				if ( $metadata['image_meta']['created_timestamp'] ) { printf( __( 'Taken: %s<br/>', 'pendrell' ), date( get_option( 'date_format' ), $metadata['image_meta']['created_timestamp'] ) ); }
				if ( $metadata['image_meta']['camera'] ) { printf( __( 'Camera: %s</br>', 'pendrell' ), $metadata['image_meta']['camera'] ); }
				if ( $metadata['image_meta']['focal_length'] ) { printf( __( 'Focal Length: %s mm<br/>', 'pendrell' ), $metadata['image_meta']['focal_length'] ); }
				if ( $metadata['image_meta']['aperture'] ) { printf( __( 'Aperture: f/%s<br/>', 'pendrell' ), $metadata['image_meta']['aperture'] ); }
				if ( $metadata['image_meta']['shutter_speed'] ) {
					// Based on http://technology.mattrude.com/2010/07/display-exif-data-on-wordpress-gallery-post-image-2/
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
				if ( $metadata['image_meta']['iso'] ) { echo __( 'ISO/Film: ', 'pendrell') . $metadata['image_meta']['iso'] . '<br/>'; } ?>
			</div>
		</div>
<?php
	}
}



// Custom gallery shortcode function; some guidance from https://wordpress.stackexchange.com/questions/43558/how-to-manually-fix-the-wordpress-gallery-code-using-php-in-functions-php
function pendrell_gallery( $output, $attr ) {
  global $post;

  static $instance = 0;
  $instance++;

  // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
  if ( isset( $attr['orderby'] ) ) {
    $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
    if ( !$attr['orderby'] )
      unset( $attr['orderby'] );
  }

  extract(shortcode_atts(array(
    'order'      => 'ASC',
    'orderby'    => 'menu_order ID',
    'id'         => $post->ID,
    'itemtag'    => 'li',
    'icontag'    => 'figure',
    'captiontag' => 'figcaption',
    'columns'    => 3,
    'size'       => 'thumbnail',
    'include'    => '',
    'exclude'    => ''
  ), $attr));

  $id = intval($id);
  if ( 'RAND' == $order )
    $orderby = 'none';

  if ( !empty($include) ) {
    $include = preg_replace( '/[^0-9,]+/', '', $include );
    $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

    $attachments = array();
    foreach ( $_attachments as $key => $val ) {
      $attachments[$val->ID] = $_attachments[$key];
    }
  } elseif ( !empty($exclude) ) {
    $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
    $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
  } else {
    $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
  }

  if ( empty($attachments) )
    return '';

  if ( is_feed() ) {
    $output = "\n";
    foreach ( $attachments as $att_id => $attachment )
      $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
    return $output;
  }

  $itemtag = tag_escape($itemtag);
  $captiontag = tag_escape($captiontag);
  $columns = intval($columns);
  $itemwidth = $columns > 0 ? floor(100/$columns) : 100; // What is this for?

  // What about using $content_width to determine $size?
  global $content_width;
  $itemwidth = $content_width / $columns;
  if ( $itemwidth <= 300 ) {
    $size = 'medium-300-cropped';
  } elseif ( $itemwidth <= 465 ) {
    $size = 'medium-465-cropped';
  } elseif ( $itemwidth <= 624 ) {
    $size = 'medium-624-cropped';
  }

  // @TODO: fallback image in case there isn't one of the desired size

  // Determine what size of images to call upon
  $size_class = sanitize_html_class( apply_filters( 'pendrell_media_gallery_size', $size ) );

  // Don't mess around with this too much; the gallery_style filter usually needs a div element to play with
  $gallery_div = '<div id="gallery-' . $instance . '" class="gallery gallery-id-' . $id . ' gallery-columns-' .  $columns . ' gallery-size-' . $size_class . '"><ul>';
  $output = apply_filters( 'gallery_style', $gallery_div );

  foreach ( $attachments as $id => $attachment ) {
    $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
    $output .= '<' . $itemtag . ' class="gallery-item gallery-item-id-' . $id . '">';

    if ( $captiontag && ( $attachment->post_title || $attachment->post_excerpt ) ) {
      $output .= '<' . $icontag . ' aria-describedby="figcaption-' . $id . '" class="gallery-caption gallery-icon" itemscope itemtype="http://schema.org/ImageObject">' . $link . '</' . $icontag . '>';
      $output .= '<' . $captiontag . ' id="figcaption-' . $id . '" class="gallery-caption-text" itemprop="description">';
      if ( $attachment->post_title )
        $output .= '<div class="gallery-caption-title">' . wptexturize( $attachment->post_title ) . '</div>';
      if ( $attachment->post_excerpt )
        $output .= wptexturize( $attachment->post_excerpt );
      $output .= '</' . $captiontag . '>';
    } else {
      $output .= '<' . $icontag . ' class="gallery-icon" itemscope itemtype="http://schema.org/ImageObject">' . $link . '</' . $icontag . '>';
    }
    $output .= '</' . $itemtag . '>';
  }

  $output .= '</ul></div>';

  return $output;
}
// This will absolutely break the display on any theme but Pendrell; @TODO: consider removing this component from pendrell
if ( PENDRELL_MEDIA_GALLERY ) {
  add_filter( 'post_gallery', 'pendrell_gallery', 10, 2);
  // This enables compatibility with Jetpack Carousel
  add_filter( 'jp_carousel_force_enable', '__return_true' );
}
