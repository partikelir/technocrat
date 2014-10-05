<?php // ==== COMMENTS ==== //

// Comments template wrapper; load template if comments are open or we have at least one comment; via _s
if ( !function_exists( 'pendrell_comments_template' ) ) : function pendrell_comments_template() {
  if ( comments_open() || get_comments_number() != '0' ) {
    comments_template( '', true );
  }
} endif;



// Comments form wrapper; tidier display of commenting markup options among other things
if ( !function_exists( 'pendrell_comments_form' ) ) : function pendrell_comments_form() {

  if ( get_option( 'wpcom_publish_comments_with_markdown' ) == true ) {
    $notes = sprintf( __( '<a href="https://daringfireball.net/projects/markdown/syntax" target="_blank"><span data-tooltip="%1$s">Markdown</span></a> and <span data-tooltip="%2$s">HTML</span> enabled in comments', 'pendrell' ),
      __( 'Markdown is a shorthand system of formatting text on the web. Click to find out more!', 'pendrell' ),
      sprintf( __( 'Valid tags and attributes: %s', 'pendrell' ), esc_attr( allowed_tags() ) )
    );
  } else {
    $notes = sprintf( __( '<span data-tooltip="%s">HTML</span> enabled in comments', 'pendrell' ),
      sprintf( __( 'Valid tags and attributes: %s', 'pendrell' ), esc_attr( allowed_tags() ) )
    );
  }

  $notes = '<div class="comment-notes-after">' . $notes . '.</div>';

  // Reference: http://codex.wordpress.org/Function_Reference/comment_form
  comment_form(
    array(
      'id_form'             => 'commentform',
      'id_submit'           => 'submit',
      'cancel_reply_link'   => __( 'Cancel', 'pendrell' ),
      'title_reply'         => __( 'Respond', 'pendrell' ),
      'title_reply_to'      => __( 'Respond to %s', 'pendrell' ),
      'label_submit'        => __( 'Post comment', 'pendrell' ),
      'comment_notes_after' => $notes
    )
  );
} endif;
