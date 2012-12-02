<?php // === PENDRELL CONFIGURATION === //
define( 'PENDRELL_VERSION', 0.1 );
define( 'PENDRELL_NAME', get_bloginfo( 'name' ) );
define( 'PENDRELL_DESC', get_bloginfo( 'description' ) );
define( 'PENDRELL_HOME', get_bloginfo( 'url' ) );

// Google Analytics code e.g. 'UA-XXXXXX-XX'; false when not in use
define( 'PENDRELL_GOOGLE_ANALYTICS_CODE', false );

 // Choose a pre-defined font stack: sans, serif, or false to default back to Twenty Twelve
define( 'PENDRELL_FONTSTACK', 'sans' );

// Admin
if ( is_admin() ) {
	define( 'PENDRELL_FONTSTACK_EDITOR', 'Georgia, "Palatino Linotype", Palatino, "URW Palladio L", "Book Antiqua", "Times New Roman", serif;' ); // Admin HTML editor font stack.
	define( 'PENDRELL_FONTSIZE_EDITOR', '16px' ); // Admin HTML editor font size.
}

?>