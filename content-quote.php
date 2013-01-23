<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$source_name = get_post_meta( $post->ID, '_format_quote_source_name', true );
$source_title = get_post_meta( $post->ID, '_format_quote_source_title', true );
$source_date = get_post_meta( $post->ID, '_format_quote_source_date', true );
$source_url = get_post_meta( $post->ID, '_format_quote_source_url', true );
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<blockquote<?php if ( $source_url ) { ?> cite="<?php echo $source_url; ?>"<?php } ?>>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
			</blockquote>

			<?php if ( $source_name || $source_title || $source_url ) {

				$source_data = array();

				if ( $source_name ) { $source_data[] = $source_name; }

				if ( $source_title && $source_url ) {
					$source_data[] = '<cite><a href="' . $source_url . '">' . $source_title . '</a>'; 
				} elseif ( $source_title ) {
					$source_data[] = '<cite>' . $source_title . '</cite>';
				} elseif ( $source_url ) {
					$source_data[] = $source_url;
				}

				if ( $source_date ) { $source_data[] = $source_date; }

				$source = implode( ', ', $source_data );

			?>
			<footer>&#8213;<?php echo $source; ?></footer>
			<?php } ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php twentytwelve_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
