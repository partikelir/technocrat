<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.10
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<?php pendrell_entry_title(); ?>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
