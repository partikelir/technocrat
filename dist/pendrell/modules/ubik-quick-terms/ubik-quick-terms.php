<?php
/**
 * Plugin Name: Ubik Quick Terms
 * Plugin URI: http://github.com/synapticism/ubik-quick-terms
 * Description: Add term descriptions to the WordPress quick edit box
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.0.1
 */
define( 'UBIK_QUICK_TERMS_VERSION', '0.0.1' );

// Do not call this plugin directly
if ( !defined( 'WPINC' ) ) {
  die;
}

// Configuration file loading: first we try to grab user-defined settings
if ( is_readable( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-quick-terms-config.php' ) )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-quick-terms-config.php' );

// Configuration file loading: now load the defaults
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-quick-terms-config-defaults.php' );

// Load plugin core
if ( is_admin() )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-quick-terms-setup.php' );
