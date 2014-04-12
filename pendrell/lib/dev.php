<?php // === PENDRELL DEVELOPMENT AREA === //

// Everything below here might or might not be working... when it has been developed and tested move it above this line

// 404 (TO DO); some suggestions: http://www.alistapart.com/articles/perfect404/ http://justintadlock.com/archives/2009/05/13/customize-your-404-page-from-the-wordpress-admin
function pendrell_404() {
	?><h2><?php _e( 'Nothing found', 'pendrell' ); ?></h2>

<?php // Prefill the search form with a half-decent guess.
	$search_term = esc_url( $_SERVER['REQUEST_URI'] );
	pendrell_search_form( $search_term );
}



// Smarter search form
function pendrell_search_form( $search_term = '' ) {
	global $search_num;
	++$search_num;
	?>
				<form id="search-form<?php if ( $search_num ) echo "-{$search_num}"; ?>" method="get" action="<?php echo trailingslashit( home_url() ); ?>">
					<div>
						<input type="search" id="search-text<?php if ( $search_num ) echo "-{$search_num}"; ?>" class="search-field" name="s" value="<?php
							if ( is_search() ) {
								the_search_query();
							} elseif ( !empty( $search_term) ) {
								echo $search_term;
							} else {
								_e( 'Search for&hellip;', 'pendrell' ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;<?php
							} ?>" />
						<input type="submit" id="search-submit<?php if ( $search_num ) echo "-{$search_num}"; ?>" class="search-submit button" value="<?php _e( 'Go!', 'pendrell' ); ?>" />
					</div>
				</form>
<?php
}



// Capture search query for jQuery highlighter; not working at present
function pendrell_search_highlighter() {
  $query = get_search_query();
  if ( strlen($query) > 0 ) {
    ?><script type="text/javascript">var pendrellSearchQuery  = "<?php echo $query; ?>";</script><?php
  }
}
//add_action( 'wp_print_scripts', 'pendrell_search_highlighter' );



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
        <li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
      <?php endforeach; }
    else { ?>
      <?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'bonestheme' ) . '</li>'; ?>
    <?php }
  }
  wp_reset_postdata();
  echo '</ul>';
} /* end bones related posts function */
