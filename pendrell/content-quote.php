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
		<div class="entry-content">
			<blockquote<?php if ( !empty( $source['url'] ) ) { ?> cite="<?php echo $source['url']; ?>"<?php } ?>>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'pendrell' ) ); ?>
				<?php if ( !empty( $source['citation'] ) ) { ?><footer>
					<?php echo $source['citation']; ?>
				</footer><?php } ?>
			</blockquote>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
