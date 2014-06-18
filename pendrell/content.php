<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title">
				<?php pendrell_content_title(); ?>
			</h1>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<?php if ( is_search() ) {
				the_excerpt();
			} else {
				if ( has_post_format( 'image' ) && has_post_thumbnail()
					|| is_attachment() && wp_attachment_is_image()
				) {
					pendrell_image_wrapper();
				}
				the_content();
			} ?>
			<?php pendrell_link_pages(); ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
