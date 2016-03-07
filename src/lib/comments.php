<?php // ==== COMMENTS ==== //

// Comments template wrapper; load template if comments are open or we have at least one comment; via _s
// @filter: pendrell_comment_template
// @action: pendrell_comment_template_before
// @action: pendrell_comment_template_after
function pendrell_comments_template() {
  do_action( 'pendrell_comment_template_before' );

  // Allow the display of the comments template to be filtered by other functions
  $display = apply_filters( 'pendrell_comment_template', true );

  // Display the template if all conditions are met
  if ( $display === true && is_singular() && ( comments_open() || get_comments_number() != '0' ) )
    comments_template( '', true );

  do_action( 'pendrell_comment_template_after' );
}



// Load comments
function pendrell_comments( $comment, $args, $depth ) {
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
            echo '<div class="comments-buttons buttons buttons-merge" role="button">' . $pingback_edit_link . '</div>';
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
            <div class="comment-buttons buttons buttons-merge">
              <?php echo pendrell_comments_edit_link(); comment_reply_link( array_merge( $args, array( 'reply_text' => pendrell_icon_text( 'comment-reply', __( 'Reply', 'pendrell' ) ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
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
          <?php if ( $comment->comment_approved == '0' ) {
            echo '<p class="warning comment-warning">' . __( 'Your comment is awaiting moderation.', 'pendrell' ) . '</p>';
          } ?>
          <div class="comment-content">
            <?php comment_text(); ?>
          </div>
        </article>
      </li><?php
      break;
  endswitch; // end comment_type check
}



// == COMMENTS FORM == //

// A light refresh of the core `comment_form` function to provide more sensible markup and styling hooks (done the WordPress way with ugly filters and merged arrays)
function pendrell_comments_form_defaults( $defaults ) {

  // Setup required data
  $post_id = get_the_ID();
  $commenter = wp_get_current_commenter();
  $user = wp_get_current_user();
  $user_identity = $user->exists() ? $user->display_name : '';
  $required = pendrell_comments_form_required();

  // Custom settings to make the comments form match the theme
  return array_merge( $defaults, array(
    'fields'                => array(
      'author'  => '<fieldset class="comment-form-author"><label for="author">' . __( 'Name', 'pendrell' ) . $required['text'] . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" placeholder="' . esc_attr( __( 'Your name&#x0085;', 'pendrell' ) ) . '"' . $required['attr'] . ' /></fieldset>',
      'email'   => '<fieldset class="comment-form-email"><label for="email">' . __( 'Email', 'pendrell' ) . $required['text'] . '</label><input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" placeholder="' . esc_attr( __( 'your@email.com', 'pendrell' ) ) . '" aria-describedby="email-notes"' . $required['attr']  . ' /></fieldset>',
      'url'     => '<fieldset class="comment-form-url"><label for="url">' . __( 'URL', 'pendrell' ) . '</label><input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" placeholder="' . esc_attr( __( 'Your web site (optional)&#x0085;', 'pendrell' ) ) . '" /></fieldset>'
    ),
    'comment_field'         => '<fieldset class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="5"' . $required['attr'] . '></textarea></fieldset>',
    'must_log_in'           => '<div class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</div>',
    'logged_in_as'          => '<div class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s" aria-label="Logged in as %2$s. Edit your profile.">%2$s</a>. <a href="%3$s">Log out</a>?' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</div>',
    'comment_notes_before'  => '',
    'comment_notes_after'   => pendrell_comments_form_notes_after(),
    'title_reply'           => __( 'Respond', 'pendrell' ),
    'title_reply_to'        => __( 'Respond to %s', 'pendrell' ),
    'title_reply_before'    => '<h3>',
    'title_reply_after'     => '</h3>',
    'cancel_reply_before'   => '<div class="buttons">',
    'cancel_reply_after'    => '</div>',
    'cancel_reply_link'     => __( 'Cancel', 'pendrell' ),
    'submit_button'         => '<button name="%1$s" type="submit" id="%2$s">' . pendrell_icon_text( 'comment-post', __( 'Post comment', 'pendrell' ) ) . '</button>',
    'submit_field'          => '<div class="comment-form-submit">%1$s %2$s</div>',
  ) );
}
add_filter( 'comment_form_defaults', 'pendrell_comments_form_defaults' );



// Explanatory notes that appear immediately below the comment form `textarea`
function pendrell_comments_form_notes_after() {
  $notes = '';
  if ( get_option( 'wpcom_publish_comments_with_markdown' ) == true ) {
    $notes = sprintf( __( '<a href="https://daringfireball.net/projects/markdown/syntax" target="_blank"><span data-tooltip="%1$s">Markdown</span></a> and <span data-tooltip="%2$s">HTML</span> enabled in comments', 'pendrell' ),
      __( 'Markdown is an awesome plain text formatting syntax. Click for more info!', 'pendrell' ),
      sprintf( __( 'Valid HTML: %s', 'pendrell' ), esc_attr( allowed_tags() ) )
    );
  } else {
    $notes = sprintf( __( '<span data-tooltip="%s">HTML</span> enabled in comments', 'pendrell' ),
      sprintf( __( 'Valid HTML: %s', 'pendrell' ), esc_attr( allowed_tags() ) )
    );
  }
  if ( !empty( $notes ) )
    $notes = '<div class="comment-notes-after">' . $notes . '.</div>';
  return $notes;
}



// Only print this line for users who are logged out; we could integrate this into the regular form but it looks nicer down near the submit button instead of cluttering up the textarea
function pendrell_comments_form_after_fields() {
  echo '<div class="comment-notes"><span id="email-notes">' . __( 'Your email address will not be published.', 'pendrell' ) . '</span>'. pendrell_comments_form_required( 'help' ) . '</div>';
}
add_action( 'comment_form_after_fields', 'pendrell_comments_form_after_fields' );



// A helper function that returns useful strings for use with required data on the comments form
function pendrell_comments_form_required( $key = '' ) {
  $required = array();
  $required['flag'] = (bool) get_option( 'require_name_email' );
  $required['text'] = ( $required['flag'] ? ' <span class="required">*</span>' : '' );
  $required['attr'] = ( $required['flag'] ? ' aria-required="true" required=""' : '' );
  $required['help'] = ( $required['flag'] ? sprintf( ' ' . __( 'Required fields are marked %s', 'pendrell' ), trim( $required['text'] ) ) : '' );
  if ( !empty( $key ) && isset( $required[$key] ) )
    return $required[$key];
  return $required;
}



// == BUTTONS == //

// Comments link wrapper; requires Ubik Comments to really shine
function pendrell_comments_link( $buttons ) {

  // Do we have good reason to display the link?
  if ( !is_singular() && ( comments_open() || get_comments_number() !== 0 ) ) {

    // Define text that appears in the link
    $zero = pendrell_icon_text( 'comment-link', __( 'Respond', 'pendrell' ) );
    $one  = pendrell_icon_text( 'comment-link', __( '1 Response', 'pendrell' ) );
    $more = pendrell_icon_text( 'comment-link', __( '% Responses', 'pendrell' ) );
    $none = pendrell_icon_text( 'comment-link', __( 'Comments off', 'pendrell' ) );

    // Attempt to get the best link we can
    $link = ubik_comments_link( $zero, $one, $more, 'button', $none );
    if ( !empty( $link ) )
      $buttons .= $link;
  }

  return $buttons;
}
add_filter( 'pendrell_entry_buttons', 'pendrell_comments_link', 20 );



// Add the button class and an icon to the comment edit link
function pendrell_comments_edit_link() {
  global $comment;
  if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) )
    return;
  return '<a class="button" href="' . get_edit_comment_link( $comment->comment_ID ) . '" rel="nofollow" role="button">' . pendrell_icon_text( 'comment-edit', __( 'Edit', 'pendrell' ) ) . '</a>';
}
add_filter( 'cancel_comment_reply_link', 'pendrell_comments_link_cancel_reply', 10, 3 );



// Filter the comment reply link; this sort of madness is why people use Disqus
function pendrell_comments_reply_link( $html, $args, $comment, $post ) {
  return str_replace( 'comment-reply', 'button leave-reply comment-reply', $html );
}
add_filter( 'comment_reply_link', 'pendrell_comments_reply_link', 10, 4 );



// A slightly more semantic comment cancel reply link
function pendrell_comments_link_cancel_reply( $formatted_link, $link, $text ) {
  $style = isset( $_GET['replytocom'] ) ? '' : ' style="display: none;"';
  return '<a class="button button-cancel" id="cancel-comment-reply-link"' . $style . ' href="' . $link . '" rel="nofollow" role="button">' . pendrell_icon_text( 'comment-reply-cancel', $text ) . '</a>';
}
add_filter( 'cancel_comment_reply_link', 'pendrell_comments_link_cancel_reply', 10, 3 );
