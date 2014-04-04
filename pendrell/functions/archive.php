<?php // === ARCHIVES === //

// Generates all-purpose archive titles
function pendrell_archive_title() {
  if ( is_day() ) {
    $title = sprintf( __( 'Daily archives: %s', 'pendrell' ), '<span>' . get_the_date() . '</span>' );
  } elseif ( is_month() ) {
    $title = sprintf( __( 'Monthly archives: %s', 'pendrell' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'pendrell' ) ) . '</span>' );
  } elseif ( is_year() ) {
    $title = sprintf( __( 'Yearly archives: %s', 'pendrell' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'pendrell' ) ) . '</span>' );
  } elseif ( is_author() ) {
    //$title = sprintf( __( 'Posts by %s', 'pendrell' ), '<span>' . get_the_author_meta( 'display_name', get_query_var( 'author' ) ) . '</span>' );
    $title = sprintf( __( 'Posts by %s', 'pendrell' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
  } elseif ( is_category() ) {
    $title = sprintf( __( '%s category archive', 'pendrell' ), '<span>' . single_cat_title( '', false ) . '</span>' );
  } elseif ( is_post_type_archive() ) {
    $title = sprintf( __( '%s archives', 'pendrell' ), '<span>' . post_type_archive_title( '', false ) . '</span>' );
  } elseif ( is_tag() ) {
    $title = sprintf( __( 'Posts tagged &#8216;%s&#8217;', 'pendrell' ), '<span>' . single_tag_title( '', false ) . '</span>' );
  } elseif ( is_tax() ) {
    if ( is_tax( 'post_format') && get_post_format() === 'quote' ) {
      $title = sprintf( __( '%s archives', 'pendrell' ), '<span>' . __( 'Quotation', 'pendrell' ) . '</span>' );
    } else {
      $title = sprintf( __( '%s archives', 'pendrell' ), '<span>' . single_term_title( '', false ) . '</span>' );
    }
  } else {
    $title = __( 'Archives', 'pendrell' );
  }
  echo apply_filters( 'pendrell_archive_title', $title );
}



// Conditional archive descriptions
function pendrell_archive_description() {

  // Only show descriptions on the first page of results
  if ( !is_paged() ) {

    // Only output archive descriptions for categories, tags, taxonomies, and authors
    if ( is_category() || is_tag() || is_tax() ) {

      // Check to see if we have a description for this category, tag, or taxonomy
      $description = term_description();

      // Got something?
      if ( !empty( $description ) ) {
        ?><div class="archive-content entry-content">
          <?php echo $description; ?>
        </div><?php
      }

      do_action( 'pendrell_archive_description_after' );

    } elseif ( is_author() ) {
      if ( get_the_author_meta( 'description' ) ) {
        ?><div class="archive-content entry-content">
          <?php pendrell_author_info(); ?>
        </div><?php
      }
    }
  }
}
