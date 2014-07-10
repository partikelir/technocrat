<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.10
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-thumbnail">
			<?php echo pendrell_image_markup(
		    $html = '',
		    $id = pendrell_thumbnail_id(),
		    $caption = '',
		    $title = '',
		    $align = '',
		    $url = get_permalink(),
		    $size = 'thumbnail'
		  ); ?>
		</div>
		<div class="entry-content-wrapper">
			<header class="entry-header">
				<?php pendrell_entry_title(); ?>
			</header><!-- .entry-header -->
			<footer class="entry-meta">
				<?php pendrell_entry_meta( 'concise' ); ?>
			</footer><!-- .entry-meta -->
			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div><!-- .entry-content -->
		</div><!-- .entry-content-wrapper -->
	</article><!-- #post -->
