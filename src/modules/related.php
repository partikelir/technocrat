<?php // ==== RELATED ==== //

// Display a list of related posts as thumbnails
if ( !function_exists( 'pendrell_related_posts' ) ) : function pendrell_related_posts() {

  // Check if this is singular content
  if ( !is_singular() )
    return;

  // Return if the post is password-protected and a password has not been entered
  if ( post_password_required() )
    return;

  $related_posts = ubik_related_posts( null, array( 'post_tag', 'places', 'category' ) );

  if ( !empty( $related_posts ) ) { ?>
    <section class="entry-after related-posts">
      <h3><?php _e( 'Related posts', 'pendrell' ); ?></h3>
      <div class="img-group img-group-3">
      <?php foreach ( $related_posts as $related_post ) {
        echo ubik_imagery_markup(
          $html = '',
          $id = pendrell_thumbnail_id( $related_post ),
          $caption = get_the_title( $related_post ),
          $title = '',
          $align = '',
          $url = get_permalink( $related_post ),
          $size = 'third-square',
          $alt = '',
          $rel = '',
          $classes = ''
        );
      } ?>
      </div>
    </section>
  <?php }

} endif;
add_action( 'pendrell_comment_template_before', 'pendrell_related_posts' );
