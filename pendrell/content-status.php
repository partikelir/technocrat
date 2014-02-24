<?php
/**
 * The template for displaying posts in the Status post format
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-header">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), apply_filters( 'twentytwelve_status_avatar', '36' ) ); ?>
		</div><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php twentytwelve_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
