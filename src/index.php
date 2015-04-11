<?php // ==== INDEX ==== //

get_header(); ?>
  <div id="wrap-content" class="wrap-content">
    <div id="content" class="site-content<?php pendrell_content_class(); ?>">
			<section id="primary" class="content-area">
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
<?php get_sidebar(); get_footer();