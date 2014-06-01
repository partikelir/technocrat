<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.7
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title">
				<?php pendrell_content_title(); ?>
			</h1>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<?php echo do_shortcode( get_post_meta( get_the_ID(), '_format_audio_embed', true ) );
			the_content(); ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
