<?php // ==== RELATED ==== //

// Display a list of related posts as thumbnails
// @filter: pendrell_related_display
// @filter: pendrell_related_taxonomies
if ( !function_exists( 'pendrell_related_posts' ) ) : function pendrell_related_posts() {

  // Check if this is singular content
  if ( !is_singular() )
    return;

  // Allow this function to be filtered
  $display = apply_filters( 'pendrell_related_display', true );

  // Additional constraints on displaying related posts: not on pages, attachments, password-protected posts, or certain post formats
  if ( $display === false || post_password_required() || is_page() || is_attachment() || has_post_format( array( 'aside', 'link', 'quote', 'status' ) ) )
    return;

  // Retrieve a list of related post IDs; null allows the function to use the current post
  // Format: array( 'taxonomy' => (int) weight )
  $related_posts = ubik_related_posts( null, apply_filters( 'pendrell_related_taxonomies', array( 'post_tag' => 1 ) ) );

  // Only proceed if related posts were found
  if ( !empty( $related_posts ) ) {

    // We only want the first three results for this theme
    $related_posts = array_slice( $related_posts, 0, 3 );

    ?><section class="entry-after related-posts">
      <h3><?php _e( 'Related posts', 'pendrell' ); ?></h3>
      <div class="gallery gallery-columns-3">
      <?php foreach ( $related_posts as $related_post ) {

        // Long title handler
        $related_title = get_the_title( $related_post );
        if ( str_word_count( $related_title ) > 10 )
          $related_title = ubik_text_truncate( $related_title, 8, '&hellip;', '' );

        echo ubik_imagery(
          $html     = '',
          $id       = pendrell_thumbnail_id( $related_post ),
          $caption  = $related_title,
          $title    = '',
          $align    = '',
          $url      = get_permalink( $related_post ),
          $size     = 'third-square',
          $alt      = '',
          $rel      = '',
          $class    = 'related-post overlay',
          $contents = pendrell_image_overlay_metadata( get_comments_number( $related_post ) . ' ' . pendrell_icon( 'ion-chatbubble', __( 'Comments', 'pendrell' ) ) ),
          $group    = 3
        );
      } ?>
      </div>
    </section>
  <?php }
} endif;
add_action( 'pendrell_comment_template_before', 'pendrell_related_posts' );
