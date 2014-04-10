<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); pendrell_microdata_scope( 'image-template' ); ?>>
		<header class="entry-header">
			<h1 class="entry-title">
				<?php pendrell_title(); ?>
			</h1>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<?php pendrell_image_wrapper(); ?>
			<?php the_content(); ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
