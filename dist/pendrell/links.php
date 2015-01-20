<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.18
 */

get_header(); ?>
  <div id="wrap-content" class="wrap-content">
    <div id="content" class="site-content<?php pendrell_content_class(); ?>">
			<section id="primary" class="content-area">
				<header id="archive-header">
					<?php pendrell_archive_title(); ?>
					<?php pendrell_archive_description(); ?>
				</header>
				<?php pendrell_nav_content( 'nav-above' ); ?>
				<main id="main" class="site-main" role="main">
					<?php $links = ubik_links();
					if ( !empty( $links ) ) {
						foreach ( $links as $link ) {
							?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
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
										<?php printf( __( 'Tags: %s.', 'pendrell' ), $link['cats_html'] ); ?>
									</div>
								</footer><?php }
							?></article><?php
						}
					} else {
						get_template_part( 'content', 'none' );
					} ?>
				</main>
				<?php echo ubik_links_categories_list();
				pendrell_nav_content( 'nav-below' ); ?>
			</section>
		</div>
	</div>
<?php pendrell_sidebar(); ?>
<?php get_footer(); ?>