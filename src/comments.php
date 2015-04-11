<?php // ==== COMMENTS ==== //

// Return if the post is password-protected and a password has not been entered
if ( post_password_required() )
	return; ?>

  <section class="entry-comments" id="comments">
  	<?php if ( have_comments() ) : ?>
  		<h2 class="comments-title">
  			<?php printf( _n( 'One comment on &lsquo;%2$s&rsquo;', '%1$s comments on &lsquo;%2$s&rsquo;', get_comments_number(), 'pendrell' ),
  					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' ); ?>
  		</h2>
  		<ol class="comment-list">
  			<?php wp_list_comments( array(
          'callback'    => 'pendrell_comments', // Calls a function in `src/core/comments.php`
          'style'       => 'ol',
          'short_ping'  => true
        ) ); ?>
  		</ol>

  		<?php pendrell_nav_comments(); ?>

  		<?php // If there are no comments and comments are closed, let's make a note
  		if ( !comments_open() && get_comments_number() != '0' && post_type_supports( get_post_type(), 'comments' ) ) : ?>
  		  <p class="notice"><?php _e( 'Comments are closed.' , 'pendrell' ); ?></p>
  		<?php endif; ?>

  	<?php endif; // have_comments() ?>

  	<?php pendrell_comments_form(); ?>
  </section>
