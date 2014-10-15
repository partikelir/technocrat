<?php // ==== NAVIGATION ==== //

// Content navigation: used in archive, index, and search templates
if ( !function_exists( 'pendrell_nav_content' ) ) : function pendrell_nav_content( $html_id = 'nav-below' ) {

  global $wp_query;

  // No funny business
  $html_id = esc_attr( $html_id );

  // Don't print empty markup if there's only one page
  if ( $wp_query->max_num_pages < 2 )
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

} endif;



// Page navigation: should only be used in page.php template
if ( !function_exists( 'pendrell_nav_page' ) ) : function pendrell_nav_page( $html_id = 'nav-below' ) {

  global $post;

  // No funny business
  $html_id = esc_attr( $html_id );
  $child = '';
  $parent = '';

  // Search for ancestors; what we really want here is the immediate parent
  $ancestors = get_ancestors( $post->ID, 'page' );
  if ( !empty( $ancestors ) ) {
    $parent = $ancestors[0];
    if ( !empty( $parent ) ) {
      $parent = '<div class="nav-previous"><a href="' . get_permalink( $parent ) . '"><span class="nav-arrow">&larr;</span> ' . get_the_title( $parent ) . '</div>';
    }
  }

  // Search for the first-born child
  $children = get_pages( 'child_of=' . $post->ID . '&sort_column=menu_order' );
  if ( !empty( $children ) ) {
    $child = $children[0]->ID;
    if ( !empty( $child ) ) {
      $child = '<div class="nav-next"><a href="' . get_permalink( $child ) . '">' . get_the_title( $child ) . ' <span class="nav-arrow">&rarr;</span></div>';
    }
  }

  // Check if we have anything; if so, let's output what we've found
  if ( !empty( $parent ) || !empty( $child ) ) {
    ?><nav id="<?php echo $html_id; ?>" class="nav-page" role="navigation">
      <h2 class="screen-reader-text"><?php _e( 'Post navigation', 'pendrell' ); ?></h2>
      <div class="nav-links">
        <?php echo $parent . $child; ?>
      </div>
    </nav><?php
  }
} endif;



// Post navigation; only used in single.php temple
if ( !function_exists( 'pendrell_nav_post' ) ) : function pendrell_nav_post( $html_id = 'nav-below' ) {

  // No funny business
  $html_id = esc_attr( $html_id );

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
    ?><nav id="<?php echo $html_id; ?>" class="nav-post" role="navigation">
      <h2 class="screen-reader-text"><?php _e( 'Post navigation', 'pendrell' ); ?></h2>
      <div class="nav-links">
        <?php echo $prev . $next; ?>
      </div>
    </nav><?php
  }
} endif;



// Wrapper for wp_link_pages(); @TODO: separate by comma or something; built-in separator functionality is coded by crazy people
if ( !function_exists( 'pendrell_nav_link_pages' ) ) : function pendrell_nav_link_pages() {
  wp_link_pages( array(
    'before' => '<nav class="page-links">' . __( 'Pages:', 'pendrell' ),
    'after' => '</nav>'
  ) );
} endif;
