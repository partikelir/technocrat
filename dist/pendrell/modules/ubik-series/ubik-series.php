<?php
/**
 * Plugin Name: Ubik Series
 * Plugin URI: http://github.com/synapticism/ubik-series
 * Description: A lightweight post series taxonomy for WordPress.
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.0.1
 */
define( 'UBIK_SERIES_VERSION', '0.0.1' );

// Do not call this plugin directly
if ( !defined( 'WPINC' ) ) {
  die;
}

// Configuration file loading: first we try to grab user-defined settings
if ( is_readable( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-series-config.php' ) )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-series-config.php' );

// Configuration file loading: now load the defaults
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-series-config-defaults.php' );

// Load plugin core
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-series-core.php' );
