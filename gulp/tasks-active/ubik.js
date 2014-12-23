// ==== UBIK ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config')
  , build       = config.paths.build
  , bower       = config.paths.bower
  , ubik        = config.ubik.plugins
;

// Copy Ubik components into the `build/modules` directory
gulp.task('ubik', function() {

  // Iterate through the Ubik array and wrap each plugin in the glob pattern
  ubik.forEach(function(plugin, i, array) {
    array[i] = bower+'ubik-'+plugin+'/**/*';
  });

  // Ignore patterns and integrate Ubik core
  var ubikIgnore = ['!'+bower+'ubik*/**/*.json', '!'+bower+'ubik*/**/readme.*'];
  ubik = ubik.concat(bower+'ubik/**/*', ubikIgnore);

  // Copy components
  return gulp.src(ubik)
  .pipe(gulp.dest(build+'modules'));
});
