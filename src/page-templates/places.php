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
          <div class="archive-desc">
            <?php the_content(); ?>
          </div>
        </header>
        <main id="main" class="site-main" role="main">
          <?php $places = ubik_places_top();
          if ( !empty( $places ) ) {

            ?><div class="gallery"><?php

            global $pendrell_places_thumbs;
            if ( !is_array( $pendrell_places_thumbs ) )
              $pendrell_places_thumbs = array();

            // Loop through all the top-most places
            foreach ( $places as $place ) {

              $metadata = '';

              // This is a hack to manually assign thumbnails to specific places; @TODO: code this properly
              if ( array_key_exists( $place->term_id, $pendrell_places_thumbs ) )
                $place->thumb = $pendrell_places_thumbs[$place->term_id];

              // Additional metadata to pass to the image creation function; requires additional CSS styling for correct display
              if ( !empty( $place->count ) )
                $metadata = pendrell_image_overlay_metadata( sprintf( _n( '1 post', '%s posts', $place->count, 'pendrell' ), $place->count ) . ' ' .  pendrell_icon( 'typ-location', __( 'Places', 'pendrell' ) ) );

              // Output a gallery of places
              echo ubik_imagery(
                $html     = '',
                $id       = pendrell_thumbnail_id( $place->thumb ),
                $caption  = $place->name,
                $title    = '',
                $align    = '',
                $url      = $place->link,
                $size     = 'third-square',
                $alt      = '',
                $rel      = '',
                $class    = 'overlay no-fade',
                $contents = $metadata,
                $group    = 3
              );
            }

            ?></div><?php
          } else {
            get_template_part( 'content', 'none' );
          } ?>
        </main>
      </section>
    </div>
  </div>
<?php get_footer(); ?>
