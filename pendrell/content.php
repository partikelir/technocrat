<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
if ( pendrell_is_portfolio() && is_archive() ) { // Portfolio archive items

	// Sourced from Hatch, probably by way of Hybrid: http://wordpress.org/extend/themes/hatch
	global $counter; $counter++; // Initialize counter and then bump it to 1 on the first pass

	$post_class = '';
	if ( ( $counter % 3 ) == 0 ) { $post_class .= 'divisible-by-3 '; }
	if ( ( $counter % 2 ) == 0 ) { $post_class .= 'divisible-by-2 '; }
	$post_class = trim( $post_class ); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
		<div class="entry-content">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php the_post_thumbnail( 'portfolio-navigation' ); ?></a>
		</div>
		<header class="entry-header">
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'pendrell' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
		</header>
		<footer class="entry-meta"><?php echo get_the_date(); ?></footer>
	</article>

<?php } else { ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title">
				<?php pendrell_title(); ?>
			</h1>
		</header><!-- .entry-header -->
		<?php // Only display excerpts for search; @TODO: handle post formats in the same way
		if ( is_search() ) : ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'pendrell' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>
		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->

<?php } ?>
