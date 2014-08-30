<!DOCTYPE html>
<!--[if IE 7 | IE 8]>
<html class="ie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php wp_title( '-', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<a name="top"></a>
<div id="page" class="hfeed site">
	<div class="site-header-wrapper">
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div><!-- .site-branding -->
			<div id="site-interface">
				<button id="responsive-menu-toggle" role="button"><?php _e( 'Menu', 'pendrell' ); ?></button>
				<span class="button"><a href="#content" class="skip-link screen-reader-text" role="button"><?php _e( 'Skip to content', 'pendrell' ); ?></a></span>
			</div><!-- #site-interface -->
			<nav id="site-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'header', 'menu_class' => 'menu-header inline-menu' ) ); ?>
			</nav><!-- #site-navigation -->
		</header><!-- #masthead -->
	</div><!-- .site-header-wrapper -->

	<div id="content-wrapper" class="site-content-wrapper">
		<div id="content" class="site-content<?php pendrell_content_class(); ?>">