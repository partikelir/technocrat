<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
		<header class="entry-header">
			<?php pendrell_entry_title(); ?>
		</header>
		<div class="entry-content">
			<?php the_content(); pendrell_nav_link_pages(); ?>
		</div>
		<footer class="entry-meta">
			<?php pendrell_entry_meta(); pendrell_image_meta(); ?>
		</footer>
		<?php if ( is_singular() ) {
			pendrell_comments_template();
		} ?>
	</article>
