<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.6
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title">
				<?php pendrell_title(); ?>
			</h1>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<figure id="<?php echo $id; ?>" <?php if ( !empty( $post->post_excerpt ) ) { ?>aria-describedby="figcaption-<?php echo $id; ?>" <?php } ?>class="wp-caption" itemscope itemtype="http://schema.org/ImageObject"><?php echo wp_get_attachment_image( $post->ID, 'large' );
			if ( !empty( $post->post_excerpt ) ) { ?>
				<figcaption id="figcaption-<?php echo $post->ID; ?>" class="wp-caption-text"><?php the_excerpt(); ?></figcaption>
			<?php } ?></figure>
			<?php the_content(); ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
