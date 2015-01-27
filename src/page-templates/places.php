<?php
/**
 * Template Name: Places
 *
 * Description: Use this in place of a base template for the places taxonomy.
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.18
 */
get_header(); ?>
  <div id="wrap-content" class="wrap-content">
    <div id="content" class="site-content<?php pendrell_content_class(); ?>">
      <section id="primary" class="content-area">
        <header id="archive-header">
          <?php pendrell_entry_title(); ?>
          <div class="archive-content">
            <div class="archive-description">
              <?php the_content(); ?>
            </div>
          </div>
        </header>
        <main id="main" class="site-main" role="main">
          <?php $places = ubik_places_top();
          if ( !empty( $places ) ) {
            foreach ( $places as $place ) {
              echo ubik_imagery_markup(
                $html     = '',
                $id       = pendrell_thumbnail_id( $place->thumb ),
                $caption  = $place->name,
                $title    = '',
                $align    = '',
                $url      = $place->link,
                $size     = 'third-square',
                $alt      = '',
                $rel      = '',
                $class    = '',
                $group    = 1
              );
            }
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
