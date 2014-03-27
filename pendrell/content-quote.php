<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

$source = pendrell_quotation_metadata(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title">
				<?php pendrell_title(); ?>
			</h1>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<?php // Check for a password; necessary since we are wrapping quotations in additional markup
			if ( post_password_required() ) {
				the_content();
			} else { ?>
			<blockquote<?php if ( !empty( $source['url'] ) ) { ?> cite="<?php echo $source['url']; ?>"<?php } ?>>
				<?php the_content(); ?>
				<?php if ( !empty( $source['citation'] ) ) { ?><footer>
					<?php echo $source['citation']; ?>
				</footer><?php } ?>
			</blockquote>
			<?php } ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
