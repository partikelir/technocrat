<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
?>

<article id="post-0" class="post no-results not-found">
	<?php if ( !is_404() ) { ?><header class="entry-header">
		<h1 class="entry-title"><?php _e( 'No results', 'pendrell' ); ?></h1>
	</header><?php } ?>
	<div class="entry-content">
		<?php if ( is_search() ) { ?>
			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again:', 'pendrell' ); ?></p>
		<?php } elseif ( is_archive() ) { ?>
			<p><?php _e( 'Sorry, there is no content here yet. Try searching for something else:', 'pendrell' ); ?></p>
		<?php } else { ?>
			<p><?php _e( 'What are you looking for? Try searching for it:', 'pendrell' ); ?></p>
		<?php } ?>
		<?php get_search_form(); ?>
	</div>
</article>
