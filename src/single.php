<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>
  <div id="wrap-content">
    <div id="content" class="site-content<?php pendrell_content_class(); ?>">
    	<section id="primary" class="content-area">
    		<main id="main" class="site-main" role="main">
    			<?php while ( have_posts() ) : the_post();
    				pendrell_template_part();
          endwhile; ?>
    		</main>
        <?php pendrell_nav_post( 'nav-below' ); ?>
    	</section>
    </div>
  </div>
<?php pendrell_sidebar(); ?>
<?php get_footer(); ?>