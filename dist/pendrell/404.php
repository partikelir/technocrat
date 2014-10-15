<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php pendrell_content_template( 'none' ); ?>
		</main>
	</section>

<?php get_footer(); ?>