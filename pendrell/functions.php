<?php
// Pendrell is abstracted into the /functions directory
if ( is_readable( get_stylesheet_directory() . '/functions-config.php' ) ) {
	require_once( get_stylesheet_directory() . '/functions-config.php' );
} else {
	require_once( get_stylesheet_directory() . '/functions-config-sample.php' );
}
