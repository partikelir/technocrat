<?php // === AUTHOR === //

// Author info on posts
function pendrell_author_meta() {
  // Show a bio if a user has filled out their description... but not on quote or link posts; we probably haven't authored that content
  if (
    is_singular()
    && !is_attachment()
    && !has_post_format( array( 'link', 'quote' ) )
    && get_the_author_meta( 'description' )
  ) {
    pendrell_author_info();
  }
}
add_filter( 'pendrell_entry_meta_after', 'pendrell_author_meta' );



// Author info box
function pendrell_author_info() {
  if ( get_the_author_meta( 'description' ) ) {
    $author = '<span class="fn n">' . get_the_author() . '</span>';
    $author_url = get_the_author_meta( 'user_url' ); ?>
    <div class="author-info author vcard">
      <div class="author-avatar">
        <?php if ( $author_url ) {
          ?><a href="<?php echo $author_url; ?>" title="<?php the_author_meta( 'display_name' ); ?>" rel="author"><?php echo get_avatar( get_the_author_meta( 'user_email' ), PENDRELL_BASELINE * 3 ); ?></a><?php
        } else {
          echo get_avatar( get_the_author_meta( 'user_email' ), PENDRELL_BASELINE * 3 );
        } ?>
      </div><!-- .author-avatar -->
      <div class="author-description">
        <h2><?php printf( __( 'About %s', 'pendrell' ), $author ); ?></h2>
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
}
