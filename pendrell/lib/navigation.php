<?php // === NAVIGATION === //

// Content navigation for singular content as well as paged content archives
if ( !function_exists( 'pendrell_content_nav' ) ) : function pendrell_content_nav( $html_id = '' ) {

  // No funny business
  $html_id = esc_attr( $html_id );

  // Post navigation; adapted from https://github.com/mattbanks/WordPress-Starter-Theme/
  if ( is_singular() ) {

    // Don't print empty markup if there's nowhere to navigate.
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next = get_adjacent_post( false, '', false );

    if ( !$next && !$previous )
      return;
    ?><nav class="<?php echo $html_id; ?> post-navigation" role="navigation">
      <h2 class="screen-reader-text"><?php _e( 'Post navigation', 'pendrell' ); ?></h2>
      <div class="nav-links">
        <?php
          previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="nav-arrow">&larr;</span> %title', 'Previous post link', 'pendrell', true ) );
          next_post_link( '<div class="nav-next">%link</div>', _x( '%title <span class="nav-arrow">&rarr;</span>', 'Next post link', 'pendrell', true ) );
        ?>
      </div><!-- .nav-links -->
    </nav><!-- .navigation -->
  <?php

  // Page navigation
  } else {

    // Don't print empty markup if there's only one page
    if ( $GLOBALS['wp_query']->max_num_pages < 2 )
      return;

    // Don't display top navigation on page 1; it creates unnecessary visual clutter
    if ( ( $html_id === 'nav-above' && is_paged() ) || $html_id !== 'nav-above' ) {

      // Hack: switch navigation links from "newer" and "older" to "next" and "previous"; solves UI problems with custom post ordering
      ?><nav class="<?php echo $html_id; ?> page-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php _e( 'Post navigation', 'pendrell' ); ?></h2>
        <div class="nav-links">
        <?php if ( get_previous_posts_link() ) { ?>
          <div class="nav-previous"><?php previous_posts_link( __( '<span class="nav-arrow">&larr; </span>Previous', 'pendrell' ) ); ?></div>
        <?php }

        if ( get_next_posts_link() ) { ?>
          <div class="nav-next"><?php next_posts_link( __( 'Next<span class="nav-arrow"> &rarr;</span>', 'pendrell' ) ); ?></div>
        <?php } ?>
        </div><!-- .nav-links -->
      </nav><!-- .page-navigation --><?php
    }
  }
} endif;



// Wrapper for wp_link_pages(); @TODO: separate by comma or something; built-in separator functionality is coded by crazy people
if ( !function_exists( 'pendrell_link_pages' ) ) : function pendrell_link_pages() {
  wp_link_pages( array(
    'before' => '<nav class="page-links">' . __( 'Pages:', 'pendrell' ),
    'after' => '</nav>'
  ) );
} endif;
