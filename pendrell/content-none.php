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
			<h1 class="entry-title"><?php _e( 'We have encountered an anomaly...', 'pendrell' ); ?></h1>
		</header>

		<div class="entry-content">
			<p><?php _e( 'No results were found. Try searching:', 'pendrell' ); ?></p>
			<?php $search_term = esc_url( $_SERVER['REQUEST_URI'] ); pendrell_search_form( $search_term ); ?>
		</div><!-- .entry-content -->
	</article><!-- #post-0 -->
