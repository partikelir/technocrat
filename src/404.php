<?php // ==== 404 ==== //

get_header(); ?>
  <div id="wrap-content" class="wrap-content">
    <div id="content" class="site-content<?php pendrell_content_class(); ?>">
    	<header class="archive-header">
        <?php pendrell_archive_title(); ?>
      </header>
      <main>
    		<?php get_template_part( 'content', 'none' ); ?>
    	</main>
    </div>
  </div>
<?php get_footer();