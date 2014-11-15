<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.10
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-thumbnail">
		<?php echo ubik_imagery_markup(
	    $html = '',
	    $id = pendrell_thumbnail_id(),
	    $caption = '',
	    $title = '',
	    $align = '',
	    $url = get_permalink(),
	    $size = 'thumbnail',
	    $alt = '',
	    $rel = '',
	    $class = ''
	  ); ?>
	</div>
	<div class="entry-content-wrapper">
		<header class="entry-header">
			<?php pendrell_entry_title(); ?>
		</header>
		<footer class="entry-meta">
			<?php pendrell_entry_meta( 'mini' ); ?>
		</footer>
		<div class="entry-content">
			<?php // Trim excerpts for lists using Ubik; degrades gracefully is Ubik isn't active
			if ( function_exists( 'ubik_excerpt_length_transient' ) )
				ubik_excerpt_length_transient(25);
			the_excerpt(); ?>
		</div>
	</div>
</article>
