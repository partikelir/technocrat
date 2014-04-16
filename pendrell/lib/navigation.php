<?php // === NAVIGATION === //

// Hack: switch navigation links from "newer" and "older" to "next" and "previous"; solves UI problems with custom post ordering
function pendrell_content_nav( $html_id ) {
  global $wp_query;

  $html_id = esc_attr( $html_id );

  // Don't display top navigation on page 1; it creates unnecessary visual clutter
  if (
    ( is_paged() && $html_id === 'nav-above' )
    || $html_id !== 'nav-above'
  ) {

    if ( $wp_query->max_num_pages > 1 ) {
    ?><nav id="<?php echo $html_id; ?>" class="page-navigation" role="navigation">
      <h3 class="assistive-text"><?php _e( 'Post navigation', 'pendrell' ); ?></h3>
      <div class="nav-previous"><?php previous_posts_link( __( '<span class="nav-arrow">&larr; </span>Previous', 'pendrell' ) ); ?></div>
      <div class="nav-next"><?php next_posts_link( __( 'Next<span class="nav-arrow"> &rarr;</span>', 'pendrell' ) ); ?></div>
    </nav><!-- #<?php echo $html_id; ?> .navigation --><?php
    }
  }
}



// Wrapper for wp_link_pages(); @TODO: separate by comma or something; built-in separator functionality is coded by crazy people
function pendrell_link_pages() {
  wp_link_pages( array(
    'before' => '<div class="page-links">' . __( 'Pages:', 'pendrell' ),
    'after' => '</div>'
  ) );
}