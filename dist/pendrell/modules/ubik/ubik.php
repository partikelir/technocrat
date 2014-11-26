<?php
/**
 * Plugin Name: Ubik
 * Plugin URI: http://github.com/synapticism/ubik
 * GitHub Plugin URI: https://github.com/synapticism/ubik
 * Description: A library of useful theme-agnostic WordPress snippets, hacks, and functions
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.8.0
 */
define( 'UBIK_VERSION', '0.8.0' );

// Do not call this plugin directly
if ( !defined( 'WPINC' ) ) {
  die;
}

// Configuration file loading: first we try to grab user-defined settings
if ( is_readable( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-config.php' ) )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-config.php' );

// Configuration file loading: now load the defaults
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-config-defaults.php' );

// Load plugin files
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-is-categorized.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-terms-popular.php' );
