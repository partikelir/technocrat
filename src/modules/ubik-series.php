<?php // ==== UBIK SERIES ==== //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-series/ubik-series.php' );

// Display series metadata
function pendrell_series() {

  // Check if this is singular content
  if ( !is_singular() )
    return;

  // Additional constraints on displaying post series: not on pages, attachments, password-protected posts, or certain post formats
  if ( post_password_required() || is_page() || is_attachment() || has_post_format( array( 'aside', 'link', 'quote', 'status' ) ) )
    return;

  // Get an array of formatted lists representing various series
  $series = ubik_series_list();

  // Display the list of posts in the series only if there is more than one post in that series
  if ( !empty( $series ) ) { ?>
    <div class="entry-after series">
      <h3><?php _e( 'Series', 'pendrell' ); ?></h3>
      <?php foreach ( $series as $series_id => $series_list ) {
        $term = get_term( $series_id, 'series' );
        if ( !empty( $term ) ) {
          ?><p><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo $term->name; ?></a></p><?php
        }
        echo $series_list;
      } ?>
    </div>
  <?php }
}
add_action( 'pendrell_comment_template_before', 'pendrell_series', 15 );
