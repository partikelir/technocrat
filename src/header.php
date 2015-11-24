<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php wp_title( '-', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php do_action( 'pendrell_body_before' ); ?>
	<div id="page" class="site hfeed h-feed">
		<div id="wrap-header" class="wrap-header">
			<header id="masthead" class="site-header">
				<div class="site-branding">
					<h1 class="site-title"><a href="<?php echo esc_url( site_url( '/' ) ); ?>" rel="home"><?php echo ubik_svg_icon_text( 'typ-spiral', get_bloginfo( 'name' ) ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				</div>
				<div id="site-interface" class="site-interface">
					<a href="#content" class="button skip-link" role="button" rel="nofollow"><?php echo __( 'Skip to content', 'pendrell' ); ?></a><?php get_search_form(); ?><button id="menu-toggle" class="menu-toggle"><?php echo pendrell_icon_text( 'menu-toggle', __( 'Menu', 'pendrell' ) ); ?></button>
				</div>
				<nav id="site-navigation" class="site-navigation">
					<?php do_action( 'pendrell_site_navigation' ); ?>
				</nav>
			</header>
		</div>
		<div id="wrap-main" class="wrap-main">
