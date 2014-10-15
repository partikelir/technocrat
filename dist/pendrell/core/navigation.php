<?php // ==== NAVIGATION ==== //

// Content navigation for singular content as well as paged content archives
if ( !function_exists( 'pendrell_content_nav' ) ) : function pendrell_content_nav( $html_id = '' ) {

  // @TODO: different paging for archives, posts, and pages

  // No funny business
  $html_id = esc_attr( $html_id );

  // Post navigation; adapted from https://github.com/mattbanks/WordPress-Starter-Theme/
  if ( is_singular() ) {

    if ( is_page() ) {

      global $post;

      $parent = '';
      $child = '';

      $ancestors = get_ancestors( $post->ID, 'page' );
      if ( $ancestors ) {
        $parent = $ancestors[0];
      }

      $children = get_pages( 'child_of=' . $post->ID . '&sort_column=menu_order' );
      if ( $children ) {
        $child = $children[0]->ID;
      }

      if ( !empty( $parent ) ) {
        $parent = '<div class="nav-previous"><a href="' . get_permalink( $parent ) . '"><span class="nav-arrow">&larr;</span> ' . get_the_title( $parent ) . '</div>';
      }

      if ( !empty( $child ) ) {
        $child = '<div class="nav-next"><a href="' . get_permalink( $child ) . '">' . get_the_title( $child ) . ' <span class="nav-arrow">&rarr;</span></div>';
      }

      // Do stuff
      if ( !empty( $parent ) || !empty( $child ) ) {
        ?><nav id="<?php echo $html_id; ?>" class="nav-pages" role="navigation">
          <h2 class="screen-reader-text"><?php _e( 'Post navigation', 'pendrell' ); ?></h2>
          <div class="nav-links">
            <?php echo $parent . $child; ?>
          </div>
        </nav><?php
      }

    } else {

      // Default taxonomy
      $post_tax = 'category';

      // If this blog isn't big on categories let's use tags instead
      if ( function_exists( 'ubik_categorized_blog' ) ) {
        if ( !ubik_categorized_blog() )
          $post_tax = 'post_tags';
      }

      // @TODO: use $post_tax; this seems to be very buggy in the current version of WP as per https://core.trac.wordpress.org/ticket/26937
      $prev = get_previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="nav-arrow">&larr;</span> %title', 'Previous post link', 'pendrell' ) );
      $next = get_next_post_link( '<div class="nav-next">%link</div>', _x( '%title <span class="nav-arrow">&rarr;</span>', 'Next post link', 'pendrell' ) );

      // Output markup if we've got a match
      if ( !empty( $next ) || !empty( $prev ) ) {
        ?><nav id="<?php echo $html_id; ?>" class="nav-posts" role="navigation">
          <h2 class="screen-reader-text"><?php _e( 'Post navigation', 'pendrell' ); ?></h2>
          <div class="nav-links">
            <?php echo $prev . $next; ?>
          </div>
        </nav><?php
      }
    }

  // Archive and index navigation
  } else {

    // Don't print empty markup if there's only one page
    if ( $GLOBALS['wp_query']->max_num_pages < 2 )
      return;

    // Don't display top navigation on page 1; it creates unnecessary visual clutter
    if ( ( $html_id === 'nav-above' && is_paged() ) || $html_id !== 'nav-above' ) {

      // Hack: switch navigation links from "newer" and "older" to "next" and "previous"; solves UI problems with custom post ordering
      ?><nav id="<?php echo $html_id; ?>" class="nav-content" role="navigation">
        <h2 class="screen-reader-text"><?php _e( 'Content navigation', 'pendrell' ); ?></h2>
        <div class="nav-links">
        <?php if ( get_previous_posts_link() ) { ?>
          <div class="nav-previous"><?php previous_posts_link( __( '<span class="nav-arrow">&larr; </span>Previous', 'pendrell' ) ); ?></div>
        <?php }

        if ( get_next_posts_link() ) { ?>
          <div class="nav-next"><?php next_posts_link( __( 'Next<span class="nav-arrow"> &rarr;</span>', 'pendrell' ) ); ?></div>
        <?php } ?>
        </div>
      </nav><?php
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
