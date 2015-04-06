<?php // ==== ICONS ==== //

// Returns a list of icon associations as an array
function pendrell_icons() {
  return apply_filters( 'pendrell_icons', array(
    'author-edit'             => 'typ-spanner'
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
}



// Get an icon association from the table above
function pendrell_icon_get( $name = '' ) {
  $icons = pendrell_icons();
  if ( !empty( $name ) && array_key_exists( $name, $icons ) )
    return $icons[$name];
  return false;
}



// Returns an SVG icon
function pendrell_icon( $name = '', $text = '' ) {
  $icon = pendrell_icon_get( $name );
  if ( empty( $icon ) )
    return;
  return ubik_svg_icon( $icon, $text );
}



// Returns an SVG icon and some text
function pendrell_icon_text( $name = '', $text = '' ) {
  $icon = pendrell_icon_get( $name );
  if ( empty( $icon ) )
    return;
  return ubik_svg_icon_text( $icon, $text );
}



// Add an icon to an Ubik search button
function pendrell_icon_search_button( $contents ) {
  return pendrell_icon_text( 'search', $contents );
}
