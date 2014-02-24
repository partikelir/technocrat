<?php

// Head cleaner: removes useless fluff, Windows Live Writer support, version info, pointless relational links
function pendrell_init() {
	if ( !is_admin() ) {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link' );
	}
}
add_action( 'init', 'pendrell_init' );



// Enqueue scripts
function pendrell_enqueue_scripts() {
  // Hack: no need to load Open Sans more than once!
  wp_deregister_style( 'open-sans' );
  wp_register_style( 'open-sans', false );

  // Load theme-specific JavaScript
	if ( !is_admin() ) { // http://www.ericmmartin.com/5-tips-for-using-jquery-with-wordpress/
		wp_enqueue_script( 'pendrell-functions', get_stylesheet_directory_uri() . '/pendrell.min.js', array( 'jquery' ), '0.1', true );
	}

  // Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use).
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
    wp_enqueue_script( 'comment-reply' );

  // Adds JavaScript for handling the navigation menu hide-and-show behavior.
  //wp_enqueue_script( 'twentytwelve-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true );

  // Loads the Internet Explorer specific stylesheet.
  //wp_enqueue_style( 'twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentytwelve-style' ), '20121010' );
  //$wp_styles->add_data( 'twentytwelve-ie', 'conditional', 'lt IE 9' );
  // Note: cruft removed since I don't really care about supporting old versions of IE. The CSS is archived in the assets folder.

  // Override Twenty Twelve font styles
  $font_url = pendrell_get_font_url();
  if ( ! empty( $font_url ) ) {
    wp_deregister_style( 'twentytwelve-fonts' ); // Should be unnecessary
    wp_enqueue_style( 'pendrell-fonts', esc_url_raw( $font_url ), array(), null );
  }

  // Loads our main stylesheet.
  wp_enqueue_style( 'pendrell-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'pendrell_enqueue_scripts' );



// Hack: simplify and customize Google font loading; reference Twenty Twelve for more advanced options
function pendrell_get_font_url() {
  $font_url = '';
  $protocol = is_ssl() ? 'https' : 'http';
  $fonts = PENDRELL_GOOGLE_FONTS ? PENDRELL_GOOGLE_FONTS : "Open+Sans:400italic,700italic,400,700"; // Default back to Open Sans
  $font_url = "$protocol://fonts.googleapis.com/css?family=" . $fonts;
  return $font_url;
}



// Dynamic page titles; hooks into wp_title to improve search engine ranking without making a mess
function pendrell_wp_title( $title, $sep = '-', $seplocation = 'right' ) {

	// Flush out whatever came in; let's do it from scratch
	$title = '';

	// Default seperator and spacing
	if ( trim( $sep ) == '' )
		$sep = '-';
	$sep = ' ' . $sep . ' ';

	// Call up page number; show in page title if greater than 1
	$page_num = '';
	if ( is_paged() ) {
		global $page, $paged;
		if ( $paged >= 2 || $page >= 2 )
			$page_num = $sep . sprintf( __( 'Page %d', 'pendrell' ), max( $paged, $page ) );
	}

	if ( is_search() ) {
		if ( trim( get_search_query() ) == '' )
			$title = __( 'No search query entered', 'pendrell' ) . $sep . PENDRELL_NAME;
		else
			$title = sprintf( __( 'Search results for &#8216;%s&#8217;', 'pendrell' ), trim( get_search_query() ) ) . $sep . PENDRELL_NAME . $page_num;
	}

	if ( is_404() )
		$title = __( 'Page not found', 'pendrell' ) . $sep . PENDRELL_NAME;

	if ( is_feed() )
		$title = single_post_title( '', false ) . $sep . PENDRELL_NAME;

	if ( is_front_page() || is_home() ) {
		$title = PENDRELL_NAME;
		if ( PENDRELL_DESC )
			$title .= $sep . PENDRELL_DESC;
	}

	// Archives; some guidance from Hybrid on times and dates
	if ( is_archive() ) {
		if ( is_author() )
			$title = sprintf( __( 'Posts by %s', 'pendrell' ), get_the_author_meta( 'display_name', get_query_var( 'author' ) ) );
		elseif ( is_category() )
			$title = sprintf( __( '%s category archive', 'pendrell' ), single_term_title( '', false ) );
		elseif ( is_tag() )
			$title = sprintf( __( '%s tag archive', 'pendrell' ), single_term_title( '', false ) );
		elseif ( is_post_type_archive() )
			$title = sprintf( __( '%s archive', 'pendrell' ), post_type_archive_title( '', false ) );
		elseif ( is_tax() )
			$title = sprintf( __( '%s archive', 'pendrell' ), single_term_title( '', false ) );
		elseif ( is_date() ) {
			if ( get_query_var( 'second' ) || get_query_var( 'minute' ) || get_query_var( 'hour' ) )
				$title = sprintf( __( 'Archive for %s', 'pendrell' ), get_the_time( __( 'g:i a', 'pendrell' ) ) );
			elseif ( is_day() )
				$title = sprintf( __( '%s daily archive', 'pendrell' ), get_the_date() );
			elseif ( get_query_var( 'w' ) )
				$title = sprintf( __( 'Archive for week %1$s of %2$s', 'pendrell' ), get_the_time( __( 'W', 'pendrell' ) ), get_the_time( __( 'Y', 'pendrell' ) ) );
			elseif ( is_month() )
				$title = sprintf( __( '%s monthly archive', 'pendrell' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'pendrell' ) ) );
			elseif ( is_year() )
				$title = sprintf( __( '%s yearly archive', 'pendrell' ), get_the_date( _x( 'Y', 'yearly archives date format', 'pendrell' ) ) );
			else
				$title = get_the_date();
			}
		$title .= $sep . PENDRELL_NAME;
	}

	// Single posts, pages, and attachments
	if ( is_singular() ) {
		if ( is_attachment() )
			$title = single_post_title( '', false );
		elseif ( is_page() || is_single() )
			$title = single_post_title( '', false );
		$title .= $sep . PENDRELL_NAME;
	}

	return esc_html( strip_tags( stripslashes( $title . $page_num ) ) );
}
// Lower priority so titles aren't doubled up
add_filter( 'wp_title', 'pendrell_wp_title', 11, 3 );



// Output a human readable date wrapped in an HTML5 time tag
function pendrell_date( $date ) {
	if ( is_archive() && !pendrell_is_portfolio() ) {
		return $date;
	} else {
		if ( ( current_time( 'timestamp' ) - get_the_time('U') ) < 86400 )
			$pendrell_time = human_time_diff( get_the_time('U'), current_time( 'timestamp' ) ) . ' ago';
		else
			$pendrell_time = get_the_time( 'M j, Y, g:i a', '', '' );
		return '<time datetime="' . get_the_time('c') . '" pubdate>' . $pendrell_time . '</time>';
	}
}
add_filter( 'get_the_date', 'pendrell_date' );



function twentytwelve_entry_meta() {
	global $post;

	do_action( 'pre_entry_meta' );

	?><div class="entry-meta-buttons"><?php

	edit_post_link( __( 'Edit', 'twentytwelve' ), ' <span class="edit-link button">', '</span>' );

	if ( comments_open() && !is_singular() ) { ?>
		<span class="leave-reply button"><?php comments_popup_link( __( 'Respond', 'pendrell' ), __( '1 Response', 'pendrell' ), __( '% Responses', 'pendrell' ) );
		?></span><?php
	}

	?></div><?php

	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		get_the_date()
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	$post_format = get_post_format();
	if ( $post_format === false ) {
		if ( is_attachment() && wp_attachment_is_image() ) {
			$format = __( 'image', 'pendrell' );
		} else {
			$format = __( 'entry', 'pendrell' );
		}
	} elseif ( $post_format === 'quote' ) {
		// Formality, please!
		$format = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
			esc_url( get_post_format_link( $post_format ) ),
			__( 'Quotation archive', 'pendrell' ),
			__( 'quotation', 'pendrell' )
		);
	} else {
		$format = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
			esc_url( get_post_format_link( $post_format ) ),
			esc_attr( sprintf( __( '%s archive', 'pendrell' ), get_post_format_string( $post_format ) ) ),
			esc_attr( strtolower( get_post_format_string( $post_format ) ) )
		);
	}

	$parent = '';
	if ( ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) || ( is_page() && $post->post_parent ) ) {
		if ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) {
			$parent_rel = 'gallery';
		} elseif ( is_page() && $post->post_parent ) {
			$parent_rel = 'parent';
		}
		$parent = sprintf( __( '<a href="%1$s" title="Return to %2$s" rel="%3$s">%4$s</a>', 'pendrell' ),
			esc_url( get_permalink( $post->post_parent ) ),
			esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
			$parent_rel,
			get_the_title( $post->post_parent )
		);
	}

	// Translators: 1 is category, 2 is tag, 3 is the date, 4 is the author's name, 5 is post format, and 6 is post parent.
	if ( $tag_list && ( $post_format === false ) ) {
		// Posts with tags and categories
		$utility_text = __( 'This %5$s was posted %3$s in %1$s and tagged %2$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} elseif ( $categories_list && ( $post_format === false ) ) {
		// Posts with no tags
		$utility_text = __( 'This %5$s was posted %3$s in %1$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} elseif ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) {
		// Images with a parent post
		$utility_text = __( 'This %5$s was posted %3$s in %6$s.', 'pendrell' );
	} elseif ( is_page() && $post->post_parent ) {
		// Pages with a parent (sub-pages)
		$utility_text = __( 'This page was posted under %6$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} elseif ( is_page() ) {
		// Pages
		$utility_text = __( 'This page was posted<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} else {
		// Post formats
		$utility_text = __( 'This %5$s was posted %3$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	}

	?><div class="entry-meta-main"><?php

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author,
		$format,
		$parent
	);

	?></div><?php

	do_action( 'post_entry_meta' );

}



// Hack: switch navigation links depending on the order of posts, mainly for use with series
function twentytwelve_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<?php if ( $wp_query->query_vars['order'] == 'ASC' ) { ?><div class="nav-previous"><?php previous_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next"><?php next_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
			<?php } else { ?><div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div><?php } ?>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}



function pendrell_pre_get_posts( $query ) {
	// Modify how many posts per page are displayed in different contexts (e.g. more portfolio items on category archives)
	// Source: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
    if ( pendrell_is_portfolio() && $query->is_main_query() ) {
    	$query->set( 'posts_per_page', 24 );
    }
    if ( is_search() && $query->is_main_query() ) {
        $query->set( 'posts_per_page', 20 );
    }
    if ( is_front_page() && PENDRELL_SHADOW_CATS ) {
    	$query->set( 'cat', PENDRELL_SHADOW_CATS );
	}
}
add_action( 'pre_get_posts', 'pendrell_pre_get_posts' );
