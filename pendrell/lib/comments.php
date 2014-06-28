<?php // ==== COMMENTS ==== //

// Comments template wrapper
if ( !function_exists( 'pendrell_comments_template' ) ) : function pendrell_comments_template() {

  // If comments are open or we have at least one comment, load up the comment template; via _s
  if ( comments_open() || get_comments_number() != '0' ) {
    comments_template( '', true );
  }
} endif;
