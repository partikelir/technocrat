<?php // ==== AUTHOR ==== //

// Author info on posts (optional); not shown on certain post formats or pages
if ( !function_exists( 'pendrell_author_meta' ) ) : function pendrell_author_meta() {
  if (
    is_singular()
    && get_the_author_meta( 'description' ) // Only if there is a description
    && !has_post_format( array( 'aside', 'image', 'link', 'quote', 'status' ) ) // Not for small content
    && !is_page() // Not on pages
  ) {
    pendrell_author_info();
  }
} endif;
if ( PENDRELL_AUTHOR_META )
  add_filter( 'pendrell_entry_meta_after', 'pendrell_author_meta', 12 );



// Author info box
if ( !function_exists( 'pendrell_author_info' ) ) : function pendrell_author_info() {
  if ( get_the_author_meta( 'description' ) ) {
    $author = '<span class="fn n">' . get_the_author() . '</span>'; ?>
    <div class="author-info author vcard">
      <div class="author-avatar">
        <?php pendrell_author_avatar( get_the_author_meta( 'user_url' ) ); ?>
      </div>
      <div class="author-description">
        <?php if ( !is_archive() ) { ?><h3><?php printf( __( 'About %s', 'pendrell' ), $author ); ?></h3><?php } ?>
        <p><?php the_author_meta( 'description' ); ?></p>
        <?php if ( is_multi_author() ) { ?>
        <div class="author-link">
          <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
            <?php printf( __( 'View all posts by %s<span class="nav-arrow"> &rarr;</span>', 'pendrell' ), $author ); ?>
          </a>
        </div>
        <?php } ?>
      </div>
    </div>
  <?php }
} endif;



// Return the avatar with a link to the specified URL
if ( !function_exists( 'pendrell_author_avatar' ) ) : function pendrell_author_avatar( $url ) {

  // Size should be some multiple of the baseline
  $size = 120;
  $default = '';
  $alt = get_the_author();

  // Optionally wrap avatar in a link
  if ( !empty( $url ) ) {
    echo '<a href="' . $url . '" rel="author">' . get_avatar( get_the_author_meta( 'user_email' ), $size, $default, $alt ) . '</a>';
  } else {
    echo get_avatar( get_the_author_meta( 'user_email' ), $size, $default, $alt );
  }
} endif;



// Add an "edit this user" link to author archives
if ( !function_exists( 'pendrell_author_edit_link' ) ) : function pendrell_author_edit_link( $buttons ) {
  if ( is_author() ) {
    $edit_author_link = get_edit_user_link(); // Author edit link for users with the appropriate capabilities
    if ( !empty( $edit_author_link ) )
      $buttons .= '<a href="' . $edit_author_link . '" class="button edit-link">' . pendrell_icon( 'author-edit', __( 'Edit', 'pendrell' ) ) . '</a></div>';
  }
  return $buttons;
} endif;
add_filter( 'pendrell_archive_buttons', 'pendrell_author_edit_link' );



// Output social icons associated with the specified account holder
if ( !function_exists( 'pendrell_author_social' ) ) : function pendrell_author_social( $id = 1 ) {

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
    $social .= '<a href="' . $url . '">' . ubik_svg_icon( pendrell_icon( 'social-home' ), 'Homepage' ) . '</a>';
  if ( !empty( $facebook ) )
    $social .= '<a href="' . $facebook . '">' . ubik_svg_icon( pendrell_icon( 'social-facebook' ), 'Facebook' ) . '</a>';
  if ( !empty( $flickr ) )
    $social .= '<a href="' . $flickr . '">' . ubik_svg_icon( pendrell_icon( 'social-flickr' ), 'Flickr' ) . '</a>';
  if ( !empty( $github ) )
    $social .= '<a href="' . $github . '">' . ubik_svg_icon( pendrell_icon( 'social-github' ), 'GitHub' ) . '</a>';
  if ( !empty( $instagram ) )
    $social .= '<a href="' . $instagram . '">' . ubik_svg_icon( pendrell_icon( 'social-instagram' ), 'Instagram' ) . '</a>';
  if ( !empty( $twitter ) )
    $social .= '<a href="' . $twitter . '">' . ubik_svg_icon( pendrell_icon( 'social-twitter' ), 'Twitter' ) . '</a>';

  // Wrap it
  if ( !empty( $social ) )
    $social = '<div class="social-icons">' . $social . '</div>';

  return apply_filters( 'pendrell_author_social', $social );
} endif;
