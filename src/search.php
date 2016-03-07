<?php // ==== SEARCH ==== //

get_header(); ?>
  <div id="wrap-content" class="wrap-content">
    <div id="content" class="site-content">
			<header class="archive-header">
				<?php pendrell_archive_title(); ?>
			</header>
			<?php pendrell_nav_content( 'nav-above' ); ?>
			<main>
				<?php if ( have_posts() ) {
					while ( have_posts() ) : the_post();
						pendrell_template_part();
					endwhile;
				} else {
					get_template_part( 'content', 'none' );
				} ?>
			</main>
			<?php pendrell_nav_content( 'nav-below' ); ?>
		</div>
	</div>
<?php get_footer();