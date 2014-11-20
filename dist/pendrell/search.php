<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>
  <div id="wrap-content" class="wrap-content">
    <div id="content" class="site-content<?php pendrell_content_class(); ?>">
			<section id="primary" class="content-area">
				<header class="archive-header">
					<h1 class="archive-title"><?php printf( __( 'Search results for &ldquo;%s&rdquo;', 'pendrell' ), '<mark>' . get_search_query() . '</mark>' ); ?></h1>
				</header>
				<?php pendrell_nav_content( 'nav-above' ); ?>
				<main id="main" class="site-main" role="main">
				<?php if ( have_posts() ) {
					while ( have_posts() ) : the_post();
						pendrell_template_part();
					endwhile;
				} else {
					get_template_part( 'content', 'none' );
				} ?>
				</main>
				<?php pendrell_nav_content( 'nav-below' ); ?>
			</section>
		</div>
	</div>
<?php pendrell_sidebar(); ?>
<?php get_footer(); ?>