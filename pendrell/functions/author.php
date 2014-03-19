<?php // === AUTHOR === //

// Author info on posts
function pendrell_author_meta() {
  // Show a bio if a user has filled out their description... but not on quote or link posts; we probably haven't authored that content
  if (
    is_singular()
    && get_the_author_meta( 'description' )
    && !has_post_format( array( 'link', 'quote' ) )
  ) {
    pendrell_author_info();
  }
}
add_filter( 'pendrell_entry_meta_after', 'pendrell_author_meta' );



// Author info box
function pendrell_author_info() {
  if ( get_the_author_meta( 'description' ) ) {
    $author_url = get_the_author_meta( 'user_url' ); ?>
    <div class="author-info">
      <div class="author-avatar">
        <?php if ( $author_url ) {
          ?><a href="<?php echo $author_url; ?>" title="<?php the_author_meta( 'display_name' ); ?>"><?php echo get_avatar( get_the_author_meta( 'user_email' ), 90 ); ?></a><?php
        } else {
          echo get_avatar( get_the_author_meta( 'user_email' ), 90 );
        } ?>
      </div><!-- .author-avatar -->
      <div class="author-description">
        <h2><?php printf( __( 'About %s', 'pendrell' ), get_the_author() ); ?></h2>
        <p><?php the_author_meta( 'description' ); ?></p>
        <?php if ( is_multi_author() ) { ?>
        <div class="author-link">
          <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
            <?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'pendrell' ), get_the_author() ); ?>
          </a>
        </div><!-- .author-link -->
        <?php } ?>
      </div><!-- .author-description -->
    </div><!-- .author-info -->
  <?php }
}
