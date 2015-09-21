// ==== BROWSERIFY ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , browserify  = require('browserify')
  , watchify    = require('watchify')
  , source      = require('vinyl-source-stream')
  , config      = require('../../gulpconfig').scripts.browserify
;

// Bundle scripts with Browserify; task template courtesy of Stefan Imhoff: http://stefanimhoff.de/2014/gulp-tutorial-5-javascripts-browserify/
// @TODO: logging and error handling, among other things
gulp.task('browserify', function() {

  var browserifyThis = function(bundleConfig) {
    var bundler = browserify({
      cache: {}, packageCache: {}, fullPaths: false, // Required watchify args
      entries: bundleConfig.entries,
      extensions: config.extensions,
      debug: config.debug // Enable sourcemaps
    });

    var bundle = function() {
      return bundler
        .bundle()
        .pipe(source(bundleConfig.outputName)) // Use vinyl-source-stream to make the stream Gulp-compatible
        .pipe(gulp.dest(bundleConfig.dest));
    };

    // Wrap with watchify and rebundle on changes
    if(global.isWatching) {
      bundler = watchify(bundler);
      bundler.on('update', bundle);
    }

    return bundle();
  };

  // Start bundling with Browserify for each bundle specified in the config file
  config.bundleConfigs.forEach(browserifyThis);
});
