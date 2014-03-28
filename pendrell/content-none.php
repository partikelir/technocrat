<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
?>

	<article id="post-0" class="post no-results not-found">
		<header class="entry-header">
			<h1 class="entry-title">
        <?php _e( 'Error 404: content not found', 'pendrell' ); ?>
      </h1>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<p><?php _e( 'What are you looking for? Try searching for it:', 'pendrell' ); ?></p>
			<?php $search_term = esc_url( $_SERVER['REQUEST_URI'] ); pendrell_search_form( $search_term ); ?>
		</div><!-- .entry-content -->
	</article><!-- #post-0 -->
