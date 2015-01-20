<?php // ==== CONFIGURATION (CUSTOM) ==== //

// Refer to `functions-config-defaults.php` for default values; set custom values for your site here...

// This stuff is all here for testing purposes only
if ( WP_LOCAL_DEV ) {
  defined( 'PENDRELL_MODULE_IMAGE_META' )   || define( 'PENDRELL_MODULE_IMAGE_META', true );
  defined( 'PENDRELL_MODULE_POST_FORMATS' ) || define( 'PENDRELL_MODULE_POST_FORMATS', true );
  defined( 'PENDRELL_MODULE_RESPONSIVE' )   || define( 'PENDRELL_MODULE_RESPONSIVE', true );
  defined( 'PENDRELL_MODULE_VIEWS' )        || define( 'PENDRELL_MODULE_VIEWS', true );

  defined( 'PENDRELL_UBIK_ADMIN' )          || define( 'PENDRELL_UBIK_ADMIN', true );
  defined( 'PENDRELL_UBIK_ANALYTICS' )      || define( 'PENDRELL_UBIK_ANALYTICS', true );
  defined( 'PENDRELL_UBIK_CLEANER' )        || define( 'PENDRELL_UBIK_CLEANER', true );
  defined( 'PENDRELL_UBIK_COMMENTS' )       || define( 'PENDRELL_UBIK_COMMENTS', true );
  defined( 'PENDRELL_UBIK_EXCLUDER' )       || define( 'PENDRELL_UBIK_EXCLUDER', true );
  defined( 'PENDRELL_UBIK_FEED' )           || define( 'PENDRELL_UBIK_FEED', true );
  defined( 'PENDRELL_UBIK_LINGUAL' )        || define( 'PENDRELL_UBIK_LINGUAL', true );
  defined( 'PENDRELL_UBIK_LINKS' )          || define( 'PENDRELL_UBIK_LINKS', true );
  defined( 'PENDRELL_UBIK_MARKDOWN' )       || define( 'PENDRELL_UBIK_MARKDOWN', true );
  defined( 'PENDRELL_UBIK_PLACES' )         || define( 'PENDRELL_UBIK_PLACES', true );
  defined( 'PENDRELL_UBIK_POST_FORMATS' )   || define( 'PENDRELL_UBIK_POST_FORMATS', true );
  defined( 'PENDRELL_UBIK_QUICK_TERMS' )    || define( 'PENDRELL_UBIK_QUICK_TERMS', true );
  defined( 'PENDRELL_UBIK_RECORDPRESS' )    || define( 'PENDRELL_UBIK_RECORDPRESS', true );
  defined( 'PENDRELL_UBIK_RELATED' )        || define( 'PENDRELL_UBIK_RELATED', true );
  defined( 'PENDRELL_UBIK_SERIES' )         || define( 'PENDRELL_UBIK_SERIES', true );
  defined( 'PENDRELL_UBIK_SEO' )            || define( 'PENDRELL_UBIK_SEO', true );
}
