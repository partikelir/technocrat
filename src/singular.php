<?php // ==== SINGULAR ==== //

get_header(); ?>
  <div id="wrap-content" class="wrap-content">
    <div id="content" class="site-content<?php pendrell_content_class(); ?>">
    	<section id="primary" class="content-area">
    		<main id="main" class="site-main">
    			<?php while ( have_posts() ) : the_post();
    				pendrell_template_part();
          endwhile; ?>
    		</main>
        <?php do_action( 'pendrell_singular_below' ); ?>
    	</section>
    </div>
  </div>
<?php get_sidebar(); get_footer();