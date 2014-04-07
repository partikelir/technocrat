<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.7
 */

	// Sourced from Hatch, probably by way of Hybrid: http://wordpress.org/extend/themes/hatch
	global $counter; $counter++; // Initialize counter and then bump it to 1 on the first pass

	$post_class = '';
	if ( ( $counter % 3 ) == 0 ) { $post_class .= 'divisible-by-3 '; }
	if ( ( $counter % 2 ) == 0 ) { $post_class .= 'divisible-by-2 '; }
	$post_class = trim( $post_class ); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
		<div class="entry-content">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php the_post_thumbnail( 'medium-300-cropped' ); ?></a>
		</div>
		<header class="entry-header">
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'pendrell' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
		</header><!-- .entry-header -->
		<footer class="entry-meta"><?php echo get_the_date(); ?></footer><!-- .entry-meta -->
	</article><!-- #post -->
