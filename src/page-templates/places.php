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
          <?php $ancestors = ubik_places_ancestors();
          if ( !empty( $ancestors ) ) {

            global $pendrell_places_thumbs;
            if ( !is_array( $pendrell_places_thumbs ) )
              $pendrell_places_thumbs = array();

            // Initialize thumbs
            $places = array();

            // Loop through all the top-most places
            foreach ( $ancestors as $place ) {

              // Clear metadata
              $metadata = '';

              // This is a hack to manually assign thumbnails to specific places; @TODO: code this properly
              if ( array_key_exists( $place->term_id, $pendrell_places_thumbs ) )
                $place->thumb = $pendrell_places_thumbs[$place->term_id];

              // Additional metadata to pass to the image creation function; requires additional CSS styling for correct display
              if ( !empty( $place->count ) )
                $metadata = pendrell_image_overlay_wrapper( sprintf( _n( '1 post', '%s posts', $place->count, 'pendrell' ), $place->count ) . ' ' .  ubik_svg_icon( pendrell_icon( 'places' ), __( 'Places', 'pendrell' ) ) );

              // Output a gallery of places
              $places[] = ubik_imagery(
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
                $context  = array( 'group', 'responsive' )
              );
            }

            // Output places thumbnails and metadata
            if ( !empty( $places ) )
              echo '<div class="gallery gallery-flex">' . join( $places ) . '</div>';

          } else {
            get_template_part( 'content', 'none' );
          } ?>
        </main>
      </section>
    </div>
  </div>
<?php get_footer(); ?>
