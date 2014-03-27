<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.6
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'pendrell' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php echo wp_get_attachment_image( $post->ID, array( 960, 960 ) );
			if ( !empty( $post->post_excerpt ) ) { ?>
				<div class="entry-caption"><?php the_excerpt(); ?></div>
			<?php } ?>
			<?php the_content(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
			<?php pendrell_image_info( wp_get_attachment_metadata() ); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
