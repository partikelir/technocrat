<?php // ==== MARKUP ==== //

// Generalized image markup generator; used by captioned images and image shortcodes; alternate markup presented on feeds is intended to validate
// Note: the $title variable is *not* used at all; it's WordPress legacy code; images don't need titles anyhow, only alt attributes
function ubik_imagery_markup(
  $html     = '',
  $id       = '',
  $caption  = '',
  $title    = '',
  $align    = 'none',
  $url      = '',
  $size     = 'medium',
  $alt      = '',
  $rel      = '',
  $class    = ''
) {

  // Sanitize $id and ensure it points to an existing image attachment; if not, spit out $html
  $id = (int) $id;
  if ( !empty( $id ) ) {
    $post = get_post( $id );
    if ( empty( $post ) || !wp_attachment_is_image( $id ) )
      return $html;
  }

  // Default alignment
  if ( empty( $align ) )
    $align = 'none';

  // Default size
  if ( empty( $size ) )
    $size = 'medium';

  // If the $html variable is empty let's generate our own markup from scratch
  if ( empty( $html ) ) {

    // Clean up the alt attribute; it may contain HTML and other things
    $alt = esc_attr( strip_tags( $alt ) );

    // Default back to post title if alt attribute is empty
    if ( empty( $alt ) && !empty( $post ) )
      $alt = $post->post_title;

    // No fancy business in the feed
    if ( is_feed() ) {

      // The get_image_tag function requires a simple alignment e.g. "none", "left", etc.
      $align = str_replace( 'align', '', $align );

      // Default img element generator from WordPress core
      $html = get_image_tag( $id, $alt, $title, $align, $size );

    } else {

      // Dynamic image size hook; see Pendrell for an example of usage
      // Use case: you have full-width content on a blog with a sidebar but you don't want to waste bandwidth by loading those images in feeds or in the regular flow of posts
      // Just filter this and return 'medium' when $size === 'large'
      $size = apply_filters( 'ubik_imagery_markup_size', $size );

      // If we have an ID to work with we can roll our own image element...
      if ( !empty( $id ) ) {

        // Custom replacement for get_image_tag(); roll your own instead of using $html = get_image_tag( $id, $alt, $title, $align, $size );
        list( $src, $width, $height, $is_intermediate ) = image_downsize( $id, $size );

        // If the image isn't resized then it is obviously the original; set $size to 'full' unless $width matches medium or large
        if ( !$is_intermediate ) {

          // Check if the size requested is a hard-cropped square
          $size_metadata = ubik_imagery_get_sizes( $size );
          if ( $size_metadata['width'] == $size_metadata['height'] && $size_metadata['crop'] == true ) {
            // Now check if the original image is square; if not, return a thumbnail, which is definitely square (but low quality)
            if ( $width != $height ) {
              $size = 'thumbnail';
              list( $src, $width, $height ) = image_downsize( $id, $size );
            }

          // Test to see whether the presumably "full" sized image matches medium or large for consistent styling
          } else {
            $medium = get_option( 'medium_size_w' );
            $large = get_option( 'large_size_w' );
            if ( $width === $medium ) {
              $size = 'medium';
            } elseif ( $width === $large ) {
              $size = 'large';
            } else {
              $size = 'full';
            }
          }
        }

        // With all the pieces in place let's generate the img element
        $html = '<img itemprop="contentUrl" src="' . esc_attr( $src ) . '" ' . image_hwstring( $width, $height ) . 'class="wp-image-' . $id . ' size-' . esc_attr( $size ) . '" alt="' . $alt . '" />';

      // No ID was passed; let's make a placeholder...
      } else {
        $html = '<div class="no-image size-' . esc_attr( $size ) . '"></div>';
      }
    }

    // If no URL is set let's default back to an attachment link (unless we're dealing with an attachment already); for no URL use url="none"
    if ( empty( $url ) && !wp_attachment_is_image() )
      $url = 'attachment';

    // Generate the link from the contents of the $url variable; optionally generates URL and rel attribute for images explicitly identified as attachments
    if ( !empty( $url ) ) {
      if ( $url === 'attachment' ) {
        $url = get_attachment_link( $id );
        $rel = ' rel="attachment wp-att-' . $id . '"';
      } elseif ( $url === 'none' ) {
        $url = '';
      }
    }

    // Now wrap everything in a link (whether it's an actual image element or just a div placeholder)
    if ( !empty( $url ) ) {
      $html = '<a href="' . $url . '"' . $rel . '>' . $html . '</a>';
    }

  // But if the $html variable has been passed (e.g. from caption shortcode, post thumbnail functions, or legacy code) we don't do much...
  } else {

    // Add itemprop="contentURL" to image element when $html variable is passed to this function; ugly hack but it works
    if ( !is_feed() )
      $html = str_replace( '<img', '<img itemprop="contentUrl"', $html );
  }

  // Don't generate double the markup; deals with edge cases in which content is fed through this function twice
  if ( !strpos( $html, '<figure class="wp-caption' ) ) {

    // Initialize ARIA attributes
    $aria = '';

    // Caption processing
    if ( !empty( $caption ) ) {
      // Strip tags from captions but preserve some text formatting elements; this is mainly used to get rid of stray paragraph and break tags
      $caption = strip_tags( $caption, '<a><abbr><acronym><b><bdi><bdo><cite><code><del><em><i><ins><mark><q><rp><rt><ruby><s><small><strong><sub><sup><time><u>' );

      // Replace excess white space and line breaks with a single space to neaten things up
      $caption = trim( str_replace( array("\r\n", "\r", "\n"), ' ', $caption ) );

      // Do shortcodes and texturize (since shortcode contents aren't texturized by default)
      $caption = wptexturize( do_shortcode( $caption ) );

      // Conditionally generate ARIA attributes for the figure element
      if ( !empty( $id ) )
        $aria = 'aria-describedby="figcaption-' . $id . '" ';
    }

    // Initialize class array
    if ( empty( $class ) || !is_array( $class ) )
      $class = (array) $class;

    // Prefix $align with "align"; saves us the trouble of writing it out all the time
    if ( $align === 'none' || $align === 'left' || $align === 'right' || $align === 'center' ) {
      $class[] = 'align' . $align;
    } else {
      $class[] = $align;
    }

    // There's a chance $size will have been wiped clean by the `ubik_imagery_markup_size` filter
    if ( !empty( $size ) )
      $class[] = 'size-' . $size;

    // Create class string
    $class = ' ' . esc_attr( trim( implode( ' ', $class ) ) );

    // Return stripped down markup for feeds
    if ( is_feed() ) {
      $content = $html;
      if ( !empty( $caption ) )
        $content .= '<br/><small>' . $caption . '</small> '; // Note the trailing space

    // Generate image wrapper markup used everywhere else
    } else {

      // Edge case where ID is not set
      if ( empty( $id ) ) {
        $content = '<figure class="wp-caption' . $class . '" itemscope itemtype="http://schema.org/ImageObject">' . $html;
        if ( !empty( $caption ) )
          $content .= '<figcaption class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';

      // Regular output
      } else {
        $content = '<figure id="attachment-' . $id . '" ' . $aria . 'class="wp-caption wp-caption-' . $id . $class . '" itemscope itemtype="http://schema.org/ImageObject">' . $html;
        if ( !empty( $caption ) )
          $content .= '<figcaption id="figcaption-' . $id . '" class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';
      }
      $content .= '</figure>' . "\n";
    }
  }
  return $content;
}
