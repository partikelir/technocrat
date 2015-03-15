<?php // ==== COMMENTS ==== //

// Comments template wrapper; load template if comments are open or we have at least one comment; via _s
// @filter: pendrell_comment_template
// @action: pendrell_comment_template_before
// @action: pendrell_comment_template_after
if ( !function_exists( 'pendrell_comments_template' ) ) : function pendrell_comments_template() {

  do_action( 'pendrell_comment_template_before' );

  // Allow the display of the comments template to be filtered by other functions
  $display = apply_filters( 'pendrell_comment_template', true );

  // Display the template if all conditions are met
  if ( $display === true && is_singular() && ( comments_open() || get_comments_number() != '0' ) )
    comments_template( '', true );

  do_action( 'pendrell_comment_template_after' );

} endif;



// Load comments
if ( !function_exists( 'pendrell_comments' ) ) : function pendrell_comments( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;

  // Handle different comment types differently
  switch ( $comment->comment_type ) :

    // Pingbacks and trackbacks
    case 'pingback':
    case 'trackback':

      ?><li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <article class="pingback comment-pingback"><?php
          _e( 'Pingback: ', 'pendrell' );
          comment_author_link();
          $pingback_edit_link = pendrell_comments_edit_link();
          if ( !empty( $pingback_edit_link ) )
            echo '<div class="buttons">' . $pingback_edit_link . '</div>';
        ?></article>
      </li><?php
      break;

    // Normal comments
    default:

      global $post;

      ?><li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment-article">
          <header class="comment-author vcard">
            <div class="comment-avatar">
              <?php echo get_avatar( $comment, 60 ); ?>
            </div>
            <div class="comment-buttons">
              <?php echo pendrell_comments_edit_link(); comment_reply_link( array_merge( $args, array( 'reply_text' => pendrell_icon( 'comment-reply', __( 'Reply', 'pendrell' ) ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div>
            <div class="comment-meta">
              <h1><?php comment_author_link(); ?></h1>
            <?php
              printf( __( '<a href="%1$s" rel="bookmark"><time datetime="%2$s">%3$s, %4$s</time></a>', 'pendrell' ),
                esc_url( get_comment_link( $comment->comment_ID ) ),
                get_comment_time('c'),
                get_comment_date(),
                get_comment_time()
              );
            ?>
            </div>
          </header>

          <?php if ( $comment->comment_approved == '0' ) : ?>
            <p class="comment-warning"><?php _e( 'Your comment is awaiting moderation.', 'pendrell' ); ?></p>
          <?php endif; ?>

          <section class="comment-content">
            <?php comment_text(); ?>
          </section>
        </article>
      </li><?php
      break;
  endswitch; // end comment_type check
} endif;



// Comments form wrapper; tidier display of commenting markup options among other things
if ( !function_exists( 'pendrell_comments_form' ) ) : function pendrell_comments_form() {

  $notes = '';

  if ( get_option( 'wpcom_publish_comments_with_markdown' ) == true ) {
    $notes = sprintf( __( '<a href="https://daringfireball.net/projects/markdown/syntax" target="_blank"><span data-tooltip="%1$s">Markdown</span></a> and <span data-tooltip="%2$s">HTML</span> enabled in comments', 'pendrell' ),
      __( 'Markdown is an awesome plain text formatting syntax. Click below for more info!', 'pendrell' ),
      sprintf( __( 'Valid tags/attributes: %s', 'pendrell' ), esc_attr( allowed_tags() ) )
    );
  } else {
    $notes = sprintf( __( '<span data-tooltip="%s">HTML</span> enabled in comments', 'pendrell' ),
      sprintf( __( 'Valid tags/attributes: %s', 'pendrell' ), esc_attr( allowed_tags() ) )
    );
  }

  if ( !empty( $notes ) )
    $notes = '<div class="comment-notes-after">' . $notes . '.</div>';

  // Reference: http://codex.wordpress.org/Function_Reference/comment_form
  // @TODO: completely redo the comment form
  comment_form(
    array(
      'id_form'               => 'response-form',
      'id_submit'             => 'submit',
      'cancel_reply_link'     => __( 'Cancel', 'pendrell' ),
      'title_reply'           => __( 'Respond', 'pendrell' ),
      'title_reply_to'        => __( 'Respond to %s', 'pendrell' ),
      'label_submit'          => __( 'Post comment', 'pendrell' ),
      //'comment_field'         => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' . '</textarea></p>',
      //'must_log_in'           => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
      //'logged_in_as'          => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
      //'comment_notes_before'  => '<p class="comment-notes">' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</p>',
      'comment_notes_after'   => $notes
    )
  );
} endif;



// Comments link wrapper; requires Ubik Comments to really shine
if ( !function_exists( 'pendrell_comments_link' ) ) : function pendrell_comments_link( $buttons ) {

  // Do we have good reason to display the link?
  if ( !is_singular() && ( comments_open() || get_comments_number() !== 0 ) ) {

    // Define text that appears in the link
    $zero = pendrell_icon( 'comment-link', __( 'Respond', 'pendrell' ) );
    $one  = pendrell_icon( 'comment-link', __( '1 Response', 'pendrell' ) );
    $more = pendrell_icon( 'comment-link', __( '% Responses', 'pendrell' ) );
    $none = pendrell_icon( 'comment-link', __( 'Comments off', 'pendrell' ) );

    // Attempt to get the best link we can
    $link = ubik_comments_link( $zero, $one, $more, 'button comments-link', $none );
    if ( !empty( $link ) )
      $buttons .= $link;
  }

  return $buttons;
} endif;
add_filter( 'pendrell_entry_buttons', 'pendrell_comments_link', 20 );



// == HACKS == //

// Add the button class to the comment edit link
function pendrell_comments_edit_link() {
  global $comment;
  if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) )
    return;
  return '<a class="button comment-edit-link" href="' . get_edit_comment_link( $comment->comment_ID ) . '" rel="nofollow" role="button">' . pendrell_icon( 'comment-edit', __( 'Edit', 'pendrell' ) ) . '</a>';
}
add_filter( 'cancel_comment_reply_link', 'pendrell_comments_link_cancel_reply', 10, 3 );

// Filter the comment reply link; P.S. this sort of thing is why people use Disqus
function pendrell_comments_reply_link( $html, $args, $comment, $post ) {
  return str_replace( 'comment-reply', 'button leave-reply comment-reply', $html );
}
add_filter( 'comment_reply_link', 'pendrell_comments_reply_link', 10, 4 );

// A slightly more semantic comment cancel reply link
function pendrell_comments_link_cancel_reply( $formatted_link, $link, $text ) {
  $style = isset( $_GET['replytocom'] ) ? '' : ' style="display: none;"';
  return '<a class="button button-cancel" id="cancel-comment-reply-link"' . $style . ' href="' . $link . '" rel="nofollow" role="button">' . pendrell_icon( 'comment-reply-cancel', $text ) . '</a>';
}
add_filter( 'cancel_comment_reply_link', 'pendrell_comments_link_cancel_reply', 10, 3 );
