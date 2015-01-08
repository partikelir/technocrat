<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php wp_title( '-', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php do_action( 'pendrell_body_before' ); ?>
	<a name="top"></a>
	<div id="page" class="hfeed site">
		<div id="wrap-header" class="wrap-header">
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				</div>
				<div id="site-interface" class="site-interface">
					<button id="responsive-menu-toggle" class="responsive-menu-toggle" role="button"><?php _e( 'Menu', 'pendrell' ); ?></button>
					<span class="button skip-link screen-reader-text"><a href="#content" role="button"><?php _e( 'Skip to content', 'pendrell' ); ?></a></span>
				</div>
				<nav id="site-navigation" class="site-navigation" role="navigation">
					<?php do_action( 'pendrell_site_navigation' ); ?>
				</nav>
			</header>
		</div>
		<div id="wrap-main" class="wrap-main">