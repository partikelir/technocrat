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
		wp_enqueue_script( 'pendrell', get_stylesheet_directory_uri() . '/pendrell.min.js', array( 'jquery' ), '0.1', true );
    wp_enqueue_script( 'pendrell-plugins', get_stylesheet_directory_uri() . '/pendrell-plugins.min.js', array( 'jquery' ), '0.1', true );
	}

  // Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use).
  // Commented out due to unnecessary bloat!
  //if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
    //wp_enqueue_script( 'comment-reply' );

  // Override Twenty Twelve font styles
  $font_url = pendrell_get_font_url();
  if ( ! empty( $font_url ) ) {
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



// Widgets from Twenty Twelve
function pendrell_widgets_init() {
  register_sidebar( array(
    'name' => __( 'Main Sidebar', 'pendrell' ),
    'id' => 'sidebar-1',
    'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'pendrell' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );
}
add_action( 'widgets_init', 'pendrell_widgets_init' );



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
