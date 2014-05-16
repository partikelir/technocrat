<?php // ==== SEARCH ==== //

// Custom search form
if ( !function_exists( 'pendrell_search_form' ) ) : function pendrell_search_form( $search_term = '' ) {

  // Smarter search form defaults
  $value = '';
  if ( is_search() ) {
    $value = get_search_query();
  } elseif ( !empty( $search_term ) ) {
    $value = esc_attr( $search_term );
  } ?>
    <form role="search" method="get" class="search-form" action="<?php echo trailingslashit( home_url() ); ?>">
      <label>
        <span class="screen-reader-text"><?php _e( 'Search for&hellip;', 'pendrell' ); ?></span>
        <input type="search" class="search-field" placeholder="<?php _e( 'Search for&hellip;', 'pendrell' ); ?>" value="<?php echo $value; ?>" name="s" title="<?php _e( 'Search for&hellip;', 'pendrell' ); ?>" />
      </label>
      <input type="submit" class="search-submit button" value="<?php _e( 'Go!', 'pendrell' ); ?>" />
    </form><?php
} endif;
