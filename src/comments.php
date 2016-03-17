<?php // ==== COMMENTS ==== //

// Return if the post is password-protected and a password has not been entered
if ( post_password_required() )
	return; ?>

  <section id="comments" class="entry-comments">
  	<?php if ( have_comments() ) { ?>
  		<h2 class="comments-title">
  			<?php printf( _n( '<span itemprop="commentCount">1</span> response', '%1$s responses', get_comments_number(), 'pendrell' ), '<span itemprop="commentCount">' . number_format_i18n( get_comments_number() ) . '</span>' ); ?>
  		</h2>
  		<ol class="comment-list">
  			<?php wp_list_comments( array(
          'callback'    => 'pendrell_comments', // Calls a function in `src/core/comments.php`
          'style'       => 'ol',
          'short_ping'  => true
        ) ); ?>
  		</ol>

  		<?php pendrell_nav_comments();

      // If there are no comments and comments are closed, let's make a note
  		if ( !comments_open() && get_comments_number() != '0' && post_type_supports( get_post_type(), 'comments' ) ) {
        ?><p class="notice"><?php _e( 'Comments are closed.' , 'pendrell' ); ?></p><?php
  		}
    } // have_comments()

    // Now spit out the default comment form
  	comment_form(); ?>
  </section>
