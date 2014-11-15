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
      		<?php get_template_part( 'content', 'none' ); ?>
      	</main>
      </section>
    </div>
  </div>
<?php get_footer(); ?>