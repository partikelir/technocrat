<?php // ==== UBIK SERIES ==== //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-series/ubik-series.php' );

// Display series metadata
function pendrell_series() {

  // Constraints on displaying post series: not on pages, attachments, password-protected posts, or certain post formats
  if ( !is_singular() || post_password_required() || is_page() || is_attachment() || has_post_format( array( 'aside', 'link', 'quote', 'status' ) ) )
    return;

  // Get an array of formatted lists representing various series
  $series = ubik_series_list();

  // Display the list of posts in the series only if there is more than one post in that series
  if ( !empty( $series ) ) { ?>
    <div class="entry-extras series series-list">
      <?php foreach ( $series as $series_id => $series_list ) {
        $term = get_term( $series_id, 'series' );
        if ( !empty( $term ) ) {
          ?><h3><?php printf( __( 'Part of a series: %s', 'pendrell' ), '<a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . '</a>' ); ?></h3><?php
        }
        echo $series_list;
      } ?>
    </div>
  <?php }
}
add_action( 'pendrell_comment_template_before', 'pendrell_series', 15 );
