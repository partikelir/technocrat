<?php // ==== ICONS ==== //

// Returns an icon association from a pre-defined list
function pendrell_icons( $name = '' ) {
  $icons = apply_filters( 'pendrell_icons', array(
    'anchor'                  => 'typ-anchor'
  , 'author-edit'             => 'typ-spanner'
  , 'comment-link'            => 'awe-comment'
  , 'comment-edit'            => 'typ-spanner'
  , 'comment-post'            => 'awe-comment'
  , 'comment-reply'           => 'typ-arrow-right-thick'
  , 'comment-reply-cancel'    => 'typ-cancel'
  , 'contact-form-send'       => 'typ-arrow-right-thick'
  , 'content-edit'            => 'typ-spanner'
  , 'content-protected'       => 'typ-key'
  , 'menu-toggle'             => 'awe-caret-down'
  , 'nav-next'                => 'typ-arrow-right-thick'
  , 'nav-previous'            => 'typ-arrow-left-thick'
  , 'overlay-comments'        => 'awe-comment'
  , 'places'                  => 'typ-location'
  , 'search'                  => 'ion-search'
  , 'social-home'             => 'typ-social-at-circular'
  , 'social-facebook'         => 'typ-social-facebook-circular'
  , 'social-flickr'           => 'typ-social-flickr-circular'
  , 'social-github'           => 'typ-social-github-circular'
  , 'social-instagram'        => 'typ-social-instagram-circular'
  , 'social-twitter'          => 'typ-social-twitter-circular'
  , 'term-edit'               => 'typ-spanner'
  , 'top-link'                => 'typ-arrow-up-thick'
  , 'view-gallery'            => 'ion-image'
  , 'view-list'               => 'typ-th-list'
  , 'view-posts'              => 'typ-document-text'
  ) );
  if ( !empty( $name ) && array_key_exists( $name, $icons ) )
    return $icons[$name];
  return false;
}



// Returns an SVG icon
function pendrell_icon( $name = '', $text = '', $desc = '', $class = array() ) {
  $icon = pendrell_icons( $name );
  if ( empty( $icon ) )
    return;
  return ubik_svg_icon( $icon, $text, $desc, $class );
}



// Returns an SVG icon and some text
function pendrell_icon_text( $name = '', $text = '', $desc = '', $class = array() ) {
  $icon = pendrell_icons( $name );
  if ( empty( $icon ) )
    return;
  return ubik_svg_icon_text( $icon, $text, $desc, $class );
}



// Add an icon to the Ubik search button
function pendrell_icon_search_button( $contents ) {
  return pendrell_icon_text( 'search', $contents, __( 'Search button', 'pendrell' ) );
}
