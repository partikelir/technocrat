<?php
/**
 * Plugin Name: Ubik Imagery
 * Plugin URI: http://github.com/synapticism/ubik-imagery
 * Description: Minimalist image management for WordPress.
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.0.1
 */
define( 'UBIK_IMAGERY_VERSION', '0.0.1' );

// Do not call this plugin directly
if ( !defined( 'WPINC' ) ) {
  die;
}

// Configuration file loading: first we try to grab user-defined settings
if ( is_readable( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-imagery-config.php' ) )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-imagery-config.php' );

// Configuration file loading: now load the defaults
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-imagery-config-defaults.php' );

// Load plugin core
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-imagery-markup.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-imagery-shortcodes.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-imagery-thumbnails.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-imagery-utils.php' );
