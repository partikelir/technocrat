<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Twelve already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php
					if ( is_day() ) :
						printf( __( 'Daily archives: %s', 'pendrell' ), '<mark>' . get_the_date() . '</mark>' );
					elseif ( is_month() ) :
						printf( __( 'Monthly archives: %s', 'pendrell' ), '<mark>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'twentytwelve' ) ) . '</mark>' );
					elseif ( is_year() ) :
						printf( __( 'Yearly archives: %s', 'pendrell' ), '<mark>' . get_the_date( _x( 'Y', 'yearly archives date format', 'twentytwelve' ) ) . '</mark>' );
					elseif ( is_category() ) : printf( __( 'Archive for the &#8216;%s&#8217; category', 'pendrell' ), single_cat_title( '<mark>', false ) . '</mark>' );
					elseif ( is_tag() ) : printf( __( 'Entries tagged &#8216;%s&#8217;', 'pendrell' ), single_tag_title( '<mark>', false ) . '</mark>' );
					elseif ( is_tax() ) : printf( __( '%s archives', 'pendrell' ), single_term_title( '<mark>', false ) . '</mark>' );
					elseif ( is_author() ) : printf( __( 'Posts by %s', 'pendrell' ), '<mark>' . get_the_author_meta( 'display_name', get_query_var( 'author' ) ) . '</mark>' );
					else :
						_e( 'Archives', 'twentytwelve' );
					endif;
				?></h1>
			</header><!-- .archive-header -->

			<?php 
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			endwhile;

			twentytwelve_content_nav( 'nav-below' );
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>