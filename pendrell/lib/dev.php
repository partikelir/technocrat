<?php // === DEVELOPMENT AREA === //

// Everything below here might or might not be working... when it has been developed and tested move it above this line

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses twentytwelve_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since Twenty Twelve 1.2
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */
function twentytwelve_mce_css( $mce_css ) {
  $font_url = pendrell_get_font_url();

  if ( empty( $font_url ) )
    return $mce_css;

  if ( ! empty( $mce_css ) )
    $mce_css .= ',';

  $mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

  return $mce_css;
}
add_filter( 'mce_css', 'twentytwelve_mce_css' );



// Related Posts Function; source: https://github.com/eddiemachado/bones
function bones_related_posts() {
  echo '<ul id="bones-related-posts">';
  global $post;
  $tags = wp_get_post_tags( $post->ID );
  if($tags) {
    foreach( $tags as $tag ) {
      $tag_arr .= $tag->slug . ',';
    }
    $args = array(
      'tag' => $tag_arr,
      'numberposts' => 5, /* you can change this to show more */
      'post__not_in' => array($post->ID)
    );
    $related_posts = get_posts( $args );
    if($related_posts) {
      foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
        <li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>"><?php the_title(); ?></a></li>
      <?php endforeach; }
    else { ?>
      <?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'bonestheme' ) . '</li>'; ?>
    <?php }
  }
  wp_reset_postdata();
  echo '</ul>';
} /* end bones related posts function */
