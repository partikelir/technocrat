<?php
/**
 * The template for displaying image attachments.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->
					<div class="entry-content">
						<div class="entry-attachment">
							<div class="attachment">
								<?php echo wp_get_attachment_image( $post->ID, array( 960, 960 ) );	?>
								<?php if ( ! empty( $post->post_excerpt ) ) { ?>
								<div class="entry-caption">
									<?php the_excerpt(); ?>
								</div>
								<?php } ?>
							</div><!-- .attachment -->
						</div><!-- .entry-attachment -->
						<div class="entry-description">
							<?php the_content(); ?>
						</div><!-- .entry-description -->
					</div><!-- .entry-content -->
					<footer class="entry-meta">
						<?php pendrell_image_info( wp_get_attachment_metadata() ); ?>
						<?php pendrell_entry_meta(); ?>
					</footer><!-- .entry-meta -->
				</article><!-- #post -->
				<?php comments_template(); ?>
			<?php endwhile; // end of the loop. ?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>