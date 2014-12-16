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
  $related_posts = ubik_related_posts( null, apply_filters( 'pendrell_related_taxonomies', array( 'post_tag' ) ) );

  if ( !empty( $related_posts ) ) {

    // We only want the first three results for this theme
    $related_posts = array_slice( $related_posts, 0, 3 );

    ?><section class="entry-after related-posts">
      <h3><?php _e( 'Related posts', 'pendrell' ); ?></h3>
      <div class="gallery gallery-columns-3">
      <?php foreach ( $related_posts as $related_post ) {
        echo ubik_imagery_markup(
          $html     = '',
          $id       = pendrell_thumbnail_id( $related_post ),
          $caption  = get_the_title( $related_post ),
          $title    = '',
          $align    = '',
          $url      = get_permalink( $related_post ),
          $size     = 'third-square',
          $alt      = '',
          $rel      = '',
          $class    = '',
          $group    = 1
        );
      } ?>
      </div>
    </section>
  <?php }

} endif;
add_action( 'pendrell_comment_template_before', 'pendrell_related_posts' );
