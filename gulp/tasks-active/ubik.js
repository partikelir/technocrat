// ==== UBIK ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').ubik
;

// Copy Ubik components into the build directory
gulp.task('ubik', function() {

  // Iterate through the Ubik array and wrap each component in a glob pattern to handle the nested directory structure
  config.components.forEach(function(component, i, components) {
    components[i] = config.path+'ubik-'+component+'/**/*';
  });

  // Combine the list of components with the ignore glob; this allows us to copy only the files we need without anything extra from the original GitHub repos
  config.components = config.components.concat(config.path+'ubik/**/*', config.ignore);

  // Let's go!
  return gulp.src(config.components)
  .pipe(gulp.dest(config.dest));
});
