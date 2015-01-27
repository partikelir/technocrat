<?php
/**
 * Template Name: Links
 *
 * Description: For use with Ubik Links.
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
          <?php pendrell_archive_title( ubik_links_archive_title() ); ?>
          <?php pendrell_archive_description( ubik_links_archive_description() ); ?>
        </header>
				<main id="main" class="site-main" role="main">
					<?php $links = ubik_links();
					if ( !empty( $links ) ) {
						foreach ( $links as $link ) {
							?><article id="post-<?php echo esc_attr( $link['id'] ); ?>" class="post" role="article">
								<header class="entry-header">
									<h1 class="entry-title"><?php echo $link['link']; ?></h1>
								</header><?php
								if ( !empty( $link['description'] ) ) {
								?><div class="entry-content">
									<?php echo $link['description']; ?>
								</div><?php }
								if ( !empty( $link['cats_html'] ) ) {
								?><footer class="entry-meta">
									<div class="entry-meta-main">
										<?php printf( __( 'Tags: %s. ', 'pendrell' ), $link['cats_html'] );
										printf( __( 'Domain: %s. ', 'pendrell'), '<a href="' . esc_url( $link['url'] ) . '" rel="bookmark">' . $link['domain'] . '</a>' ); ?>
									</div>
								</footer><?php }
							?></article><?php
						}
					} else {
						?><article id="post-0" class="post no-results not-found">
							<div class="entry-content">
								<p><?php _e( 'Sorry, there appears to be nothing here. Try a search:', 'pendrell' ); ?></p>
								<?php echo ubik_links_search_form(); ?>
							</div>
						</article><?php
					} ?>
				</main>
			</section>
		</div>
	</div>
<?php pendrell_sidebar(); ?>
<?php get_footer(); ?>
