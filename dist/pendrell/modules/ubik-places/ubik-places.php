<?php
/**
 * Plugin Name: Ubik Places
 * Plugin URI: http://github.com/synapticism/ubik-places
 * Description: A places taxonomy for WordPress
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.0.1
 */
define( 'UBIK_PLACES_VERSION', '0.0.1' );

// Do not call this plugin directly
if ( !defined( 'WPINC' ) ) {
  die;
}

// Configuration file loading: first we try to grab user-defined settings
if ( is_readable( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-places-config.php' ) )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-places-config.php' );

// Configuration file loading: now load the defaults
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-places-config-defaults.php' );

// Load plugin core
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-places-navigation.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-places-setup.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-places-shortcode.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-places-widget.php' );
