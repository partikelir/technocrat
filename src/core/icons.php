<?php // ==== ICONS ==== //

// Returns an icon and optionally some text; this is just a simple wrapper function for `ubik_svg_icon_text` that allows for icons to be defined in a single location
function pendrell_icon( $name = '', $text = '' ) {

  // Icon assignments
  $pendrell_icons = apply_filters( 'pendrell_icons', array(
    'author-edit'             => 'typ-spanner'
  , 'comment-link'            => 'awe-comment'
  , 'comment-edit'            => 'typ-spanner'
  , 'comment-post'            => 'awe-comment'
  , 'comment-reply'           => 'typ-arrow-right-thick'
  , 'comment-reply-cancel'    => 'typ-cancel'
  , 'contact-form-send'       => 'typ-arrow-right-thick'
  , 'content-edit'            => 'typ-spanner'
  , 'content-protected'       => 'typ-key'
  , 'gallery-comments'        => 'awe-comment'
  , 'menu-toggle'             => 'awe-caret-down'
  , 'places'                  => 'typ-location'
  , 'related-comments'        => 'awe-comment'
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

  if ( !empty( $name ) && array_key_exists( $name, $pendrell_icons ) ) {
    if ( !empty( $text ) )
      return ubik_svg_icon_text( $pendrell_icons[$name], $text );
    return $pendrell_icons[$name];
  }
  return false;
}
