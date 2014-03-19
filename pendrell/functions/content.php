<?php // === CONTENT === //

// Filters:
// pendrell_wp_title_raw ("Title")
// pendrell_wp_title_final ("Title - Blog Name - Page 2")

// Actions:
// pendrell_entry_meta_before
// pendrell_entry_meta_after

// Dynamic page titles; hooks into wp_title to improve search engine ranking without making a mess
function pendrell_wp_title( $title, $sep = '-', $seplocation = 'right' ) {

  // Flush out whatever came in; let's do it from scratch
  $title = '';

  // Default seperator and spacing
  if ( trim( $sep ) == '' )
    $sep = '-';
  $sep = ' ' . $sep . ' ';

  // Call up page number; show in page title if greater than 1
  $page_num = '';
  if ( is_paged() ) {
    global $page, $paged;
    if ( $paged >= 2 || $page >= 2 )
      $page_num = $sep . sprintf( __( 'Page %d', 'pendrell' ), max( $paged, $page ) );
  }

  if ( is_search() ) {
    if ( trim( get_search_query() ) == '' )
      $title = __( 'No search query entered', 'pendrell' );
    else
      $title = sprintf( __( 'Search results for &#8216;%s&#8217;', 'pendrell' ), trim( get_search_query() ) );
  }

  if ( is_404() )
    $title = __( 'Page not found', 'pendrell' );

  if ( is_feed() )
    $title = single_post_title( '', false );

  // Archives; some guidance from Hybrid on times and dates
  if ( is_archive() ) {
    if ( is_author() )
      $title = sprintf( __( 'Posts by %s', 'pendrell' ), get_the_author_meta( 'display_name', get_query_var( 'author' ) ) );
    elseif ( is_category() )
      $title = sprintf( __( '%s category archive', 'pendrell' ), single_term_title( '', false ) );
    elseif ( is_tag() )
      $title = sprintf( __( '%s tag archive', 'pendrell' ), single_term_title( '', false ) );
    elseif ( is_post_type_archive() )
      $title = sprintf( __( '%s archive', 'pendrell' ), post_type_archive_title( '', false ) );
    elseif ( is_tax() )
      $title = sprintf( __( '%s archive', 'pendrell' ), single_term_title( '', false ) );
    elseif ( is_date() ) {
      if ( get_query_var( 'second' ) || get_query_var( 'minute' ) || get_query_var( 'hour' ) )
        $title = sprintf( __( 'Archive for %s', 'pendrell' ), get_the_time( __( 'g:i a', 'pendrell' ) ) );
      elseif ( is_day() )
        $title = sprintf( __( '%s daily archive', 'pendrell' ), get_the_date() );
      elseif ( get_query_var( 'w' ) )
        $title = sprintf( __( 'Archive for week %1$s of %2$s', 'pendrell' ), get_the_time( __( 'W', 'pendrell' ) ), get_the_time( __( 'Y', 'pendrell' ) ) );
      elseif ( is_month() )
        $title = sprintf( __( '%s monthly archive', 'pendrell' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'pendrell' ) ) );
      elseif ( is_year() )
        $title = sprintf( __( '%s yearly archive', 'pendrell' ), get_the_date( _x( 'Y', 'yearly archives date format', 'pendrell' ) ) );
      else
        $title = get_the_date();
    }
  }

  // Single posts, pages, and attachments
  if ( is_singular() ) {
    if ( is_attachment() )
      $title = single_post_title( '', false );
    elseif ( is_page() || is_single() )
      $title = single_post_title( '', false );
  }

  if ( is_front_page() || is_home() ) {
    $title = PENDRELL_NAME;
    if ( PENDRELL_DESC )
      $title .= $sep . PENDRELL_DESC;
  } else {
    $title = apply_filters( 'pendrell_wp_title_raw', $title );
    $title .= $sep . PENDRELL_NAME;
  }

  $title = esc_html( strip_tags( stripslashes( $title . $page_num ) ) );

  return apply_filters( 'pendrell_wp_title_final', $title );
}
// Lower priority so titles aren't doubled up
add_filter( 'wp_title', 'pendrell_wp_title', 11, 3 );



// Entry metadata
function pendrell_entry_meta() {
  global $post;

  do_action( 'pendrell_entry_meta_before' );

  // Date
  $date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
    esc_url( get_permalink() ),
    esc_attr( get_the_time() ),
    get_the_date()
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



// Hack: switch navigation links from "newer" and "older" to "next" and "previous"; solves UI problems with custom post ordering
function pendrell_content_nav( $html_id ) {
  global $wp_query;

  $html_id = esc_attr( $html_id );

  if ( $wp_query->max_num_pages > 1 ) : ?>
    <nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
      <h3 class="assistive-text"><?php _e( 'Post navigation', 'pendrell' ); ?></h3>
      <div class="nav-previous"><?php previous_posts_link( __( '<span class="meta-nav">&larr;</span> Previous', 'pendrell' ) ); ?></div>
          <div class="nav-next"><?php next_posts_link( __( 'Next <span class="meta-nav">&rarr;</span>', 'pendrell' ) ); ?>
    </nav><!-- #<?php echo $html_id; ?> .navigation -->
  <?php endif;
}
