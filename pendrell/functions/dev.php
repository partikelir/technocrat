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



// Excerpt functions from Twentyeleven, slightly modified
function pendrell_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading&nbsp;&rarr;', 'pendrell' ) . '</a>';
}
add_filter( 'the_content_more_link', 'pendrell_continue_reading_link');
function pendrell_auto_excerpt_more( $more ) {
	return '&hellip;' . pendrell_continue_reading_link();
}
add_filter( 'excerpt_more', 'pendrell_auto_excerpt_more' );
function pendrell_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= pendrell_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'pendrell_custom_excerpt_more' );



// Legacy code, should be fazed out
function pendrell_pre_get_posts( $query ) {
  if ( is_front_page() && PENDRELL_SHADOW_CATS ) {
  	$query->set( 'cat', PENDRELL_SHADOW_CATS );
	}
}
add_action( 'pre_get_posts', 'pendrell_pre_get_posts' );



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
