<?php
/**
 * The template for displaying posts in the Link post format
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
$link_url = get_post_meta( get_the_ID(), '_format_link_url', true );
if ( empty( $link_url ) )
	$link_url = get_permalink();
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title">
				<a href="<?php echo $link_url; ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?>&nbsp;&rarr;</a>
			</h1>
			<footer><a href="<?php echo $link_url; ?>" title="<?php the_title(); ?>" rel="bookmark"><?php echo $link_url; ?></a></footer>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'pendrell' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php pendrell_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
