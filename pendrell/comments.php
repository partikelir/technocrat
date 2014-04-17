<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to twentytwelve_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;

function pendrell_comments( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
  switch ( $comment->comment_type ) :
    case 'pingback' :
    case 'trackback' :
    // Display trackbacks differently than normal comments.
  ?>
  <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
    <article><?php _e( 'Pingback:', 'pendrell' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'pendrell' ), '<div class="comment-meta-buttons"><span class="edit-link button">', '</span></div>' ); ?></article>
  <?php
      break;
    default :
    // Proceed with normal comments.
    global $post;
  ?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    <article id="comment-<?php comment_ID(); ?>" class="comment">
      <header class="comment-meta comment-author vcard">
        <div class="comment-meta-avatar">
        	<?php echo get_avatar( $comment, 60 ); ?>
        </div><!-- .comment-meta-avatar -->
        <div class="comment-meta-buttons">
          <?php edit_comment_link( __( 'Edit', 'pendrell' ), ' <span class="edit-link button">', '</span>' ); ?>
          <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'pendrell' ), 'before' => ' <span class="leave-reply button">', 'after' => '</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div><!-- .comment-meta-buttons -->
        <div class="comment-meta-main">
        	<h3><?php comment_author_link(); ?></h3>
        <?php
          printf( 'This comment was posted <a href="%1$s" rel="bookmark"><time datetime="%2$s">%3$s, %4$s</time></a>.',
            esc_url( get_comment_link( $comment->comment_ID ) ),
            get_comment_time('c'),
            get_comment_date(),
            get_comment_time()
          );
        ?>
      	</div><!-- .comment-meta-main -->
      </header><!-- .comment-meta -->

      <?php if ( '0' == $comment->comment_approved ) : ?>
        <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'pendrell' ); ?></p>
      <?php endif; ?>

      <section class="comment-content comment">
        <?php comment_text(); ?>
      </section><!-- .comment-content -->

    </article><!-- #comment-## -->
  <?php
    break;
  endswitch; // end comment_type check
} ?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		<h2 class="comments-title">
			<?php
				printf( _n( 'One comment on &lsquo;%2$s&rsquo;', '%1$s comments on &lsquo;%2$s&rsquo;', get_comments_number(), 'pendrell' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'pendrell_comments', 'style' => 'ol' ) ); ?>
		</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'pendrell' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '<span class="nav-arrow">&larr; </span>Older Comments', 'pendrell' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments<span class="nav-arrow"> &rarr;</span>', 'pendrell' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<?php // If there are no comments and comments are closed, let's leave a note.
		if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.' , 'pendrell' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form( array(
      'cancel_reply_link' => __( 'Cancel', 'pendrell' ),
      'title_reply'       => __( 'Respond', 'pendrell' ),
      'title_reply_to'    => __( 'Respond to %s', 'pendrell' ),
    )
  ); ?>

</div><!-- #comments .comments-area -->
