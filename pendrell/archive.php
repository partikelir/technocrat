<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php do_action( 'pendrell_archive_header_before' ); ?>
			<header class="archive-header">
				<?php pendrell_archive_title(); ?>
				<?php pendrell_archive_description(); ?>
			</header><!-- .archive-header -->
			<?php if ( have_posts() ) {
				pendrell_content_nav( 'nav-above' );
				while ( have_posts() ) : the_post();
					pendrell_content_template();
				endwhile;
				pendrell_content_nav( 'nav-below' );
			} else {
				get_template_part( 'content', 'none' );
			} ?>
		</main><!-- #content -->
	</section><!-- #primary -->

<?php pendrell_sidebar(); ?>
<?php get_footer(); ?>