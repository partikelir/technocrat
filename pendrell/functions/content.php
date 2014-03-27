<?php // === CONTENT === //

// Minimally functional wp_title filter; use Ubik (or your plugin of choice) for more SEO-friendly page titles
function pendrell_wp_title( $title, $sep = '-' ) {
  if ( is_front_page() || is_home() ) {
    $title = get_bloginfo( 'name' );
    if ( get_bloginfo( 'description' ) )
      $title .= ' ' . $sep . ' ' . get_bloginfo( 'description' );
  } else {
    $title = $title . get_bloginfo( 'name' );
  }
  return $title;
}
add_filter( 'wp_title', 'pendrell_wp_title', 11, 3 );



// Entry metadata
// Actions: pendrell_entry_meta_before, pendrell_entry_meta_after
function pendrell_entry_meta() {
  global $post;

  do_action( 'pendrell_entry_meta_before' );

  // Date
  $date = get_the_date();

  // If Ubik is active retrieve the human-readable date
  if ( function_exists( 'ubik_date') )
    $date = ubik_date( $date );

  $date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
    esc_url( get_permalink() ),
    esc_attr( get_the_time() ),
    $date
  );

  // Author
  $author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
    esc_attr( sprintf( __( 'View all posts by %s', 'pendrell' ), get_the_author() ) ),
    get_the_author()
  );

  // Post format or type
  $post_format = get_post_format();
  if ( $post_format === false ) {
    if ( is_attachment() && wp_attachment_is_image() ) {
      $format = __( 'image', 'pendrell' );
    } elseif ( is_page() ) {
      $format = __( 'page', 'pendrell' );
    } elseif ( get_post_type() === 'place' ) {
      $format = __( 'place', 'pendrell' );
    } else {
      $format = __( 'entry', 'pendrell' );
    }
  } else {
    if ( $post_format === 'quote' || $post_format === 'quotation' ) {
      $post_format_string = 'Quotation';
    } else {
      $post_format_string = get_post_format_string( $post_format );
    }
    $format = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
      esc_url( get_post_format_link( $post_format ) ),
      esc_attr( sprintf( __( '%s archive', 'pendrell' ), $post_format_string ) ),
      esc_attr( strtolower( $post_format_string ) )
    );
  }

  // Categories
  $categories_list = get_the_category_list( __( ', ', 'pendrell' ) );

  // Tags
  if ( get_post_type() === 'place' ) {
    $tag_list = get_the_term_list( $post->ID, 'place_tag', '', ', ', '' );
  } else {
    $tag_list = get_the_tag_list( '', __( ', ', 'pendrell' ) );
  }

  // Parent link for pages, images, attachments, and places
  $parent = '';
  if ( ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) || ( ( is_page() || get_post_type() === 'place' ) && $post->post_parent ) ) {
    if ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) {
      $parent_rel = 'gallery';
    } elseif ( is_page() && $post->post_parent ) {
      $parent_rel = 'parent';
    }
    $parent = sprintf( __( '<a href="%1$s" title="Return to %2$s" rel="%3$s">%4$s</a>', 'pendrell' ),
      esc_url( get_permalink( $post->post_parent ) ),
      esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
      $parent_rel,
      get_the_title( $post->post_parent )
    );
  }

  // Translators: 1 is category, 2 is tag, 3 is the date, 4 is the author's name, 5 is post format or type, and 6 is post parent.
  if ( $tag_list && ( $post_format === false ) && get_post_type() != 'place' ) {
    // Posts with tags and categories
    $utility_text = __( 'This %5$s was posted %3$s in %1$s and tagged %2$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
  } elseif ( $categories_list && ( $post_format === false ) ) {
    // Posts with no tags
    $utility_text = __( 'This %5$s was posted %3$s in %1$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
  } elseif ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) {
    // Images with a parent post
    $utility_text = __( 'This %5$s was posted %3$s in %6$s.', 'pendrell' );
  } elseif ( is_page() || get_post_type() === 'place' ) {
    // Places with tags
    if ( $tag_list ) {
      if ( $post->post_parent ) {
        $utility_text = __( 'This %5$s was posted %3$s under %6$s and tagged %2$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
      } else {
        $utility_text = __( 'This %5$s was posted %3$s and tagged %2$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
      }
    // Pages with a parent
    } elseif ( $post->post_parent ) {
      $utility_text = __( 'This %5$s was posted %3$s under %6$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
    // Pages without a parent
    } else {
      if ( is_multi_author() ) {
        $utility_text = __( 'This %5$s was posted %3$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
      // Nothing to display
      } else {
        $utility_text = '';
      }
    }
  } else {
    // Post formats
    $utility_text = __( 'This %5$s was posted %3$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
  }

  // Pull it all together
  ?><div class="entry-meta-buttons"><?php
    edit_post_link( __( 'Edit', 'pendrell' ), ' <span class="edit-link button">', '</span>' );
    if ( comments_open() && !is_singular() ) { ?>
      <span class="leave-reply button"><?php comments_popup_link( __( 'Respond', 'pendrell' ), __( '1 Response', 'pendrell' ), __( '% Responses', 'pendrell' ) );
      ?></span><?php
    } ?>
  </div>
  <div class="entry-meta-main"><?php
    printf(
      $utility_text,
      $categories_list,
      $tag_list,
      $date,
      $author,
      $format,
      $parent
    );
  ?></div><?php

  do_action( 'pendrell_entry_meta_after' );
}
