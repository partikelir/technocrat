<?php
// Pendrell is abstracted into the /functions directory
if ( is_readable( get_stylesheet_directory() . '/functions-config.php' ) ) {
	require_once( get_stylesheet_directory() . '/functions-config.php' );
} else {
	require_once( get_stylesheet_directory() . '/functions-config-sample.php' );
}
// Eventually all of this will be integrated into Pendrell; for now it's easier to just call the original as if Pendrell were still a child
//require_once( get_stylesheet_directory() . '/functions/twenty-twelve.php' );