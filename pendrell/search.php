<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">
		<?php if ( have_posts() ) { ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php printf( __( 'Search results for &ldquo;%s&rdquo;', 'pendrell' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header>
			<?php while ( have_posts() ) : the_post();
				get_template_part( 'content', pendrell_content_template() );
			endwhile;
			pendrell_content_nav( 'nav-below' );
		} else {
			get_template_part( 'content', 'none' );
		} ?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php pendrell_sidebar(); ?>
<?php get_footer(); ?>