<?php
/**
 * Streamlined archive template
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) { ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php pendrell_archive_title(); ?></h1>
				<?php pendrell_archive_description(); ?>
			</header><!-- .archive-header -->
			<?php while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_format() );
			endwhile;
			pendrell_content_nav( 'nav-below' );
		} else {
			get_template_part( 'content', 'none' );
		} ?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>