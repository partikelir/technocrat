<?php // ==== CONTENT ==== //

// Content title; displayed at the top of posts, pages, etc.
if ( !function_exists( 'pendrell_entry_title' ) ) : function pendrell_entry_title( $content = '' ) {
  if ( empty( $content ) )
    $content = get_the_title();
  $title = '<h1 class="entry-title"><a href="' . get_permalink() . '" rel="bookmark">' . $content . '</a></h1>';
  echo apply_filters( 'pendrell_entry_title', $title );
  do_action( 'pendrell_entry_title_after' );
} endif;



// Content class; applies a filter to the content wrapper to allow other functions to alter the look and feel of posts, pages, etc.
if ( !function_exists( 'pendrell_content_class' ) ) : function pendrell_content_class() {
  $classes = apply_filters( 'pendrell_content_class', array() );
  if ( !empty( $classes ) )
    echo ' ' . join( ' ', $classes );
} endif;



// Entry meta wrapper
if ( !function_exists( 'pendrell_entry_meta' ) ) : function pendrell_entry_meta( $mode = 'full' ) {
  global $post;

  do_action( 'pendrell_entry_meta_before' );

  if ( $mode == 'full' ) {

    ?><div class="entry-meta-buttons">
      <?php edit_post_link( __( 'Edit', 'pendrell' ), ' <span class="edit-link button">', '</span>' );
      if ( !is_singular() && !post_password_required() && ( comments_open() || get_comments_number() != '0' ) ) {
        ?> <span class="leave-reply button"><?php comments_popup_link( __( 'Respond', 'pendrell' ), __( '1 Response', 'pendrell' ), __( '% Responses', 'pendrell' ) ); ?></span><?php
      } ?>
    </div><?php

  }

  ?><div class="entry-meta-main">
    <?php pendrell_entry_meta_contents( $mode ); ?>
  </div><?php

  do_action( 'pendrell_entry_meta_after' );

} endif;



// Entry meta; bare bones version, mostly untested... refer to Ubik for the real deal
if ( !function_exists( 'pendrell_entry_meta_contents' ) ) : function pendrell_entry_meta_contents( $mode = 'full' ) {

  // Is Ubik active?
  if ( function_exists( 'ubik_entry_meta' ) && $mode == 'full' ) {

    // Ubik entry meta magic
    echo ubik_entry_meta();

  // If Ubik isn't active let's just fallback to Twenty Twelve's entry meta implementation with small updates to conform to hAtom microformat standard
  } else {

    global $post;

    $categories_list = get_the_category_list( ', ' );
    $tag_list = get_the_tag_list( '', ', ' );

    if ( $mode == 'mini' ) {
      $date = sprintf( '<time class="entry-date post-date published updated" datetime="%1$s">%2$s</time>',
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() )
      );
    } else {
      $date = sprintf( '<a href="%1$s" rel="bookmark"><time class="entry-date post-date published updated" datetime="%2$s">%3$s</time></a>',
        esc_url( get_permalink() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() )
      );
    }

    $author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" rel="author">%2$s</a></span>',
      esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
      get_the_author()
    );

    // Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
    if ( $mode == 'mini' ) {
      if ( !empty( $categories_list ) && !empty( $tag_list ) ) {
        $utility_text = __( '%3$s <span class="by-author">&middot; %4$s</span> &middot; %1$s &middot; %2$s', 'pendrell' );
      } elseif ( !empty( $categories_list ) ) {
        $utility_text = __( '%3$s <span class="by-author">&middot; %4$s</span> &middot; %1$s', 'pendrell' );
      } elseif ( !empty( $tag_list ) ) {
        $utility_text = __( '%3$s <span class="by-author">&middot; %4$s</span> &middot; %2$s', 'pendrell' );
      } else {
        $utility_text = __( '%3$s <span class="by-author">&middot; %4$s</span>', 'pendrell' );
      }
    } else {
      if ( !empty( $categories_list ) && !empty( $tag_list ) ) {
        $utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span> and tagged %2$s.', 'pendrell' );
      } elseif ( !empty( $categories_list ) ) {
        $utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
      } elseif ( !empty( $tag_list ) ) {
        $utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span> and tagged %2$s.', 'pendrell' );
      } else {
        $utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
      }
    }

    printf(
      $utility_text,
      $categories_list,
      $tag_list,
      $date,
      $author
    );
  }
} endif;
