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

// Return if the post is password-protected and a password has not been entered
if ( post_password_required() )
	return;

function pendrell_comments( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;

  // Display trackbacks differently than normal comments.
  switch ( $comment->comment_type ) :
    case 'pingback' :
    case 'trackback' :
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
        </div>
        <div class="comment-meta-buttons">
          <?php edit_comment_link( __( 'Edit', 'pendrell' ), ' <span class="edit-link button">', '</span>' ); ?>
          <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'pendrell' ), 'before' => ' <span class="leave-reply button">', 'after' => '</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div>
        <div class="comment-meta-main">
        	<h3><?php comment_author_link(); ?></h3>
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
        <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'pendrell' ); ?></p>
      <?php endif; ?>

      <section class="comment-content comment">
        <?php comment_text(); ?>
      </section>

    </article>
  <?php
    break;
  endswitch; // end comment_type check
} ?>

<section id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		<h2 class="comments-title">
			<?php
				printf( _n( 'One comment on &lsquo;%2$s&rsquo;', '%1$s comments on &lsquo;%2$s&rsquo;', get_comments_number(), 'pendrell' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<ol class="comment-list">
			<?php wp_list_comments( array(
        'callback'    => 'pendrell_comments',
        'style'       => 'ol',
        'short_ping'  => true
      ) );
      ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="nav-comments" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'pendrell' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '<span class="nav-arrow">&larr; </span>Older comments', 'pendrell' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer comments<span class="nav-arrow"> &rarr;</span>', 'pendrell' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<?php // If there are no comments and comments are closed, let's leave a note.
		if ( !comments_open() && get_comments_number() != '0' && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		  <p class="no-comments"><?php _e( 'Comments are closed.' , 'pendrell' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php pendrell_comments_form(); ?>

</section>
