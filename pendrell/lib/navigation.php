<?php // === NAVIGATION === //

// Hack: switch navigation links from "newer" and "older" to "next" and "previous"; solves UI problems with custom post ordering
function pendrell_content_nav( $html_id = '' ) {

  // Don't print empty markup if there's only one page
  if ( $GLOBALS['wp_query']->max_num_pages < 2 )
    return;

  $html_id = esc_attr( $html_id );

  // Don't display top navigation on page 1; it creates unnecessary visual clutter
  if ( ( $html_id === 'nav-above' && is_paged() )
    || $html_id !== 'nav-above'
  ) {
    ?><nav class="<?php echo $html_id; ?> page-navigation" role="navigation">
      <h1 class="screen-reader-text"><?php _e( 'Post navigation', 'pendrell' ); ?></h1>
      <?php if ( get_previous_posts_link() ) { ?>
        <div class="nav-previous"><?php previous_posts_link( __( '<span class="nav-arrow">&larr; </span>Previous', 'pendrell' ) ); ?></div>
      <?php }

      if ( get_next_posts_link() ) { ?>
        <div class="nav-next"><?php next_posts_link( __( 'Next<span class="nav-arrow"> &rarr;</span>', 'pendrell' ) ); ?></div>
      <?php } ?>
    </nav><!-- .page-navigation --><?php
  }
}



// Wrapper for wp_link_pages(); @TODO: separate by comma or something; built-in separator functionality is coded by crazy people
function pendrell_link_pages() {
  wp_link_pages( array(
    'before' => '<nav class="page-links">' . __( 'Pages:', 'pendrell' ),
    'after' => '</nav>'
  ) );
}
