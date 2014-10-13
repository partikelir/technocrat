<?php // ==== FOOTER INFO ==== //

// Footer info; relies on information in functions-config.php to generate appropriate content; it is't elegant but it gets the job done for now
if ( !function_exists( 'pendrell_footer_info' ) ) : function pendrell_footer_info() {

  global $pendrell_footer_info; // Defined in `src/functions-config.php`
  $output = '';

  // Set the copyright year or range of years
  if ( !empty( $pendrell_footer_info['year'] ) ) {
    $year = '&copy;' . $pendrell_footer_info['year'] . '&#8211;' . date( "Y" ) . ' ';
  } else {
    $year = '&copy;' . date( "Y" ) . ' ';
  }

  if ( is_array( $pendrell_footer_info ) && !empty( $pendrell_footer_info ) ) {

    $author = $pendrell_footer_info['author'];
    $author_url = $pendrell_footer_info['author_url'];
    $string = $pendrell_footer_info['string'];

    // If a custom copyright string has been set let's display that
    if ( !empty( $string ) ) {
      $output = $year . $string;
    } elseif ( !empty( $author ) && !empty( $author_url ) ) {
      $output = sprintf( __( '%1$s <a href="%2$s" rel="author">%3$s</a>. ', 'pendrell' ),
        $year,
        $author_url,
        $author
      );
    } elseif ( !empty ( $author ) ) {
      $output = $year . $author;
    }

  } else {
    $output = sprintf( __( '%1$s <a href="%2$s" rel="author">%3$s</a>. ', 'pendrell' ),
      $year,
      get_home_url(),
      PENDRELL_NAME // Name of the blog
    );
  }

  // Powered by statement
  $output .= sprintf( __( '%1$s is powered by <a href="http://wordpress.org" rel="generator">WordPress</a> and <a href="%2$s">%3$s</a>.', 'pendrell' ),
    PENDRELL_NAME,
    PENDRELL_THEME_URL,
    PENDRELL_THEME_NAME . ' ' . PENDRELL_THEME_VERSION
  );

  if ( !empty( $output ) ) {
    ?><div class="site-footer-info">
      <?php echo $output; ?>
    </div><!-- .site-footer-info --><?php
  }
} endif;
add_action( 'pendrell_footer', 'pendrell_footer_info' );
