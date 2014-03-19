<?php // === ARCHIVES === //

// Generates all-purpose archive titles
function pendrell_archive_title() {
  if ( is_day() ) {
    printf( __( 'Daily archives: %s', 'pendrell' ), '<span>' . get_the_date() . '</span>' );
  } elseif ( is_month() ) {
    printf( __( 'Monthly archives: %s', 'pendrell' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'pendrell' ) ) . '</span>' );
  } elseif ( is_year() ) {
    printf( __( 'Yearly archives: %s', 'pendrell' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'pendrell' ) ) . '</span>' );
  } elseif ( is_author() ) {
    //printf( __( 'Posts by %s', 'pendrell' ), '<span>' . get_the_author_meta( 'display_name', get_query_var( 'author' ) ) . '</span>' );
    printf( __( 'Posts by %s', 'pendrell' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
  } elseif ( is_category() ) {
    printf( __( 'Archive for the &#8216;%s&#8217; category', 'pendrell' ), '<span>' . single_cat_title( '', false ) . '</span>' );
  } elseif ( is_post_type_archive() ) {
    printf( __( '%s archives', 'pendrell' ), '<span>' . post_type_archive_title( '', false ) . '</span>' );
  } elseif ( is_tag() ) {
    printf( __( 'Posts tagged &#8216;%s&#8217;', 'pendrell' ), '<span>' . single_tag_title( '', false ) . '</span>' );
  } elseif ( is_tax() ) {
    if ( is_tax( 'post_format') && get_post_format() === 'quote' ) {
      printf( __( '%s archives', 'pendrell' ), '<span>' . __( 'Quotation', 'pendrell' ) . '</span>' );
    } else {
      printf( __( '%s archives', 'pendrell' ), '<span>' . single_term_title( '', false ) . '</span>' );
    }
  } else {
    _e( 'Archives', 'pendrell' );
  }
}



// Conditional archive descriptions
function pendrell_archive_description() {
  if ( is_category() || is_tag() || is_tax() ) {

    // Check to see if we have a description for this category, tag, or taxonomy
    $description = term_description();

    // Got something?
    if ( !empty( $description ) ) {
      ?><div class="archive-meta">
        <?php echo $description; ?>
      </div><?php
    }
  } elseif ( is_author() ) {
    if ( get_the_author_meta( 'description' ) ) {
      ?><div class="archive-meta">
        <?php pendrell_author_info(); ?>
      </div><?php
    }
  }
}
