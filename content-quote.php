<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$source_name = get_post_meta( $post->ID, '_format_quote_source_name', true );
$source_url = get_post_meta( $post->ID, '_format_quote_source_url', true );
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<blockquote<?php if ( $source_url ) { ?> cite="<?php echo $source_url; ?>"<?php } ?>>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
			</blockquote>
			
			<?php if ( $source_name ) { 
?>
				<footer>&#8213;<?php if ( $source_url ) { ?><a href="<?php echo $source_url; ?>"><?php } echo $source_name; if ( $source_url ) { ?></a><?php } ?></footer>
			<?php } ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php twentytwelve_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
