<?php
/**
 * Plugin Name: Ubik Excluder
 * Plugin URI: http://github.com/synapticism/ubik-excluder
 * Description: Arbitrarily exclude WordPress posts by category, tag, or post format.
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.0.1
 */
define( 'UBIK_EXCLUDER_VERSION', '0.0.1' );

// Do not call this plugin directly
if ( !defined( 'WPINC' ) ) {
  die;
}

// Configuration file loading: first we try to grab user-defined settings
if ( is_readable( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-excluder-config.php' ) )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-excluder-config.php' );

// Configuration file loading: now load the defaults
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-excluder-config-defaults.php' );

// Load plugin core
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-excluder-exclusion.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-excluder-inclusion.php' );
