<?php // ==== AUTHOR ==== //

// Author info on posts
if ( !function_exists( 'pendrell_author_meta' ) ) : function pendrell_author_meta() {
  // Show a bio if a user has filled out their description... but not on certain post formats or the contact form page
  if (
    is_singular()
    && get_the_author_meta( 'description' ) // Only if there is a description
    && !has_post_format( array( 'aside', 'image', 'link', 'quote', 'status' ) ) // Not for small content
    && !is_page( array( 'about', 'about-me', 'bio', 'biography' ) ) // No sense in duplicating info
    && !is_page_template( 'page-templates/contact-form.php' ) // Not on the contact form either
  ) {
    pendrell_author_info();
  }
} endif;
if ( PENDRELL_AUTHOR_META )
  add_filter( 'pendrell_entry_meta_after', 'pendrell_author_meta', 12 );



// Author info box
if ( !function_exists( 'pendrell_author_info' ) ) : function pendrell_author_info() {
  if ( get_the_author_meta( 'description' ) ) {
    pendrell_author_edit_link();
    $author = '<span class="fn n">' . get_the_author() . '</span>'; ?>
    <div class="author-info author vcard">
      <div class="author-avatar">
        <?php pendrell_author_avatar( get_the_author_meta( 'user_url' ) ); ?>
      </div><!-- .author-avatar -->
      <div class="author-description">
        <?php if ( !is_archive() ) { ?><h3><?php printf( __( 'About %s', 'pendrell' ), $author ); ?></h3><?php } ?>
        <p><?php the_author_meta( 'description' ); ?></p>
        <?php if ( is_multi_author() ) { ?>
        <div class="author-link">
          <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
            <?php printf( __( 'View all posts by %s<span class="nav-arrow"> &rarr;</span>', 'pendrell' ), $author ); ?>
          </a>
        </div><!-- .author-link -->
        <?php } ?>
      </div><!-- .author-description -->
    </div><!-- .author-info -->
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
    ?><a href="<?php echo $url; ?>" rel="author"><?php
    echo get_avatar( get_the_author_meta( 'user_email' ), $size, $default, $alt );
    ?></a><?php
  } else {
    echo get_avatar( get_the_author_meta( 'user_email' ), $size, $default, $alt );
  }
} endif;



// Add an "edit this user" link to author archives
if ( !function_exists( 'pendrell_author_edit_link' ) ) : function pendrell_author_edit_link() {
  if ( is_author() ) {
    $edit_author_link = get_edit_user_link(); // Author edit link for users with the appropriate capabilities
    if ( !empty( $edit_author_link ) )
      echo '<div class="entry-meta-buttons"><a href="' . $edit_author_link . '" class="edit-link button">' . pendrell_icon( 'typ-edit', __( 'Edit', 'pendrell' ) ) . '</a></div>';
  }
} endif;
