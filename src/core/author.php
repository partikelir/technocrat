<?php // ==== AUTHOR ==== //

// Author info box on posts (optional, disabled by default); not shown on certain post formats or pages
// @filter: pendrell_author_box
function pendrell_author_box() {
  if (
    1 == 1 ||
    apply_filters( 'pendrell_author_box', true ) // A switch to allow for theme-specific rules
    && is_singular()
    && get_the_author_meta( 'description' ) // Only if there is a description
    && !has_post_format( array( 'aside', 'image', 'link', 'quote', 'status' ) ) // Not for small content
    && !is_page() // Not on pages
  ) {
    ?><section class="author-box"><?php echo pendrell_author_info(); ?></section><?php
  }
}
add_action( 'pendrell_comment_template_before', 'pendrell_author_box', 5 );



// Author box rules: when and where to display the extra author box
function pendrell_author_box_rules( $switch ) {

  // Off by default
  $switch = false;

  return $switch;
}
add_filter( 'pendrell_author_box', 'pendrell_author_box_rules' );



// Author info; abstracted for use in author archive descriptions as well as inside author info boxes
function pendrell_author_info( $avatar = true ) {

  // Exit early if the author has no description
  if ( !get_the_author_meta( 'description' ) )
    return;

  // Initialize
  $output = $avatar_html = '';
  $author = '<span class="p-name p-author">' . get_the_author() . '</span>';

  // Author description
  $output .= get_the_author_meta( 'description' );

  // Only add this stuff to author info boxes
  if ( is_singular() ) {
    $output = '<h3>' . $author . '</h3>' . $output;
    if ( is_multi_author() )
      $output .= '<footer class="author-link"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . sprintf( __( 'View all posts by %s<span class="nav-arrow">&nbsp;&rarr;</span>', 'pendrell' ), $author ) . '</a></footer>';
  }

  // Wrap the author description
  $output = '<div class="author-description">' . $output . '</div>';

  // Avatar handling
  if ( $avatar === true ) {
    $avatar_html = pendrell_author_avatar( get_the_author_meta( 'user_url' ), PENDRELL_BASELINE * 4 ); // Must match the value in `/src/scss/lib/_author.scss'
    if ( !empty ( $avatar_html ) )
      $output = '<div class="author-avatar">' . $avatar_html . '</div>' . $output;
  }

  // Wrap it all up and return
  return '<div class="author author-info h-card">' . $output . '</div>';
}



// Return the avatar with a link to the specified URL; size should be some multiple of the baseline
// @filter: pendrell_author_avatar_default
function pendrell_author_avatar( $url = '', $size = PENDRELL_BASELINE ) {

  // Initialize
  $output = '';
  $default = apply_filters( 'pendrell_author_avatar_default', '' );
  $alt = get_the_author();

  // Optionally wrap avatar in a link
  if ( !empty( $url ) ) {
    $output = '<a href="' . $url . '" rel="author">' . get_avatar( get_the_author_meta( 'user_email' ), $size, $default, $alt ) . '</a>';
  } else {
    $output = get_avatar( get_the_author_meta( 'user_email' ), $size, $default, $alt );
  }
  return $output;
}



// Neaten author descriptions; Ubik Markdown can also be used to transform descriptions
add_filter( 'get_the_author_description', 'wptexturize' );
add_filter( 'get_the_author_description', 'do_shortcode' );
add_filter( 'get_the_author_description', 'wpautop' );
add_filter( 'get_the_author_description', 'shortcode_unautop' );



// Add an "edit this user" link to author archives
function pendrell_author_edit_link( $buttons ) {
  if ( is_author() ) {
    $edit_author_link = get_edit_user_link(); // Author edit link for users with the appropriate capabilities
    if ( !empty( $edit_author_link ) )
      $buttons .= '<a href="' . $edit_author_link . '" class="button edit-link">' . pendrell_icon_text( 'author-edit', __( 'Edit', 'pendrell' ) ) . '</a>';
  }
  return $buttons;
}
add_filter( 'pendrell_archive_buttons', 'pendrell_author_edit_link' );



// Output social icons associated with the specified account holder
function pendrell_author_social( $id = 1 ) {

  // Get user info
  $meta = get_user_meta( $id );
  if ( empty( $meta ) )
    return;

  // Line everything up
  $url        = get_the_author_meta( 'user_url', $id );
  $facebook   = $meta['facebook'][0];
  $flickr     = $meta['flickr'][0];
  $github     = $meta['github'][0];
  $instagram  = $meta['instagram'][0];
  $twitter    = $meta['twitter'][0];

  // Prepend URLs as needed; this allows for usernames OR full URLs to be entered into the admin panel
  if ( !empty( $facebook ) && strpos( $facebook, 'facebook.com' ) === false )
    $facebook = 'https://www.facebook.com' . $facebook;
  if ( !empty( $flickr ) && strpos( $flickr, 'flickr.com' ) === false )
    $flickr = 'https://www.flickr.com/photos/' . $flickr;
  if ( !empty( $github ) && strpos( $github, 'github.com' ) === false )
    $github = 'https://github.com/' . $github;
  if ( !empty( $instagram ) && strpos( $instagram, 'instagram.com' ) === false )
    $instagram = 'https://instagram.com/' . $instagram;
  if ( !empty( $twitter ) && strpos( $twitter, 'twitter.com' ) === false )
    $twitter = 'https://twitter.com/' . $twitter;

  // Initialize output
  $social = '';

  // Loop through the social icons we might want to display
  if ( !empty( $url ) )
    $social .= '<a href="' . $url . '">' . pendrell_icon( 'social-home', 'Homepage' ) . '</a>';
  if ( !empty( $facebook ) )
    $social .= '<a href="' . $facebook . '">' . pendrell_icon( 'social-facebook', 'Facebook' ) . '</a>';
  if ( !empty( $flickr ) )
    $social .= '<a href="' . $flickr . '">' . pendrell_icon( 'social-flickr', 'Flickr' ) . '</a>';
  if ( !empty( $github ) )
    $social .= '<a href="' . $github . '">' . pendrell_icon( 'social-github', 'GitHub' ) . '</a>';
  if ( !empty( $instagram ) )
    $social .= '<a href="' . $instagram . '">' . pendrell_icon( 'social-instagram', 'Instagram' ) . '</a>';
  if ( !empty( $twitter ) )
    $social .= '<a href="' . $twitter . '">' . pendrell_icon( 'social-twitter', 'Twitter' ) . '</a>';

  // Wrap it
  if ( !empty( $social ) )
    $social = '<div class="social-icons">' . $social . '</div>';

  return apply_filters( 'pendrell_author_social', $social );
}
