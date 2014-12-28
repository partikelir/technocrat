// ==== BOWER ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').bower
;

// This task is executed on `bower update` which is in turn triggered by `npm update`
// Use this task to manually copy front-end dependencies into the `src` folder as needed
gulp.task('bower', ['bower-normalize', 'bower-typicons']);

// Used to get around Sass's inability to properly @import vanilla CSS; see: https://github.com/sass/sass/issues/556
gulp.task('bower-normalize', function() {
  return gulp.src(config.normalize.src)
  .pipe(plugins.changed(config.normalize.dest))
  .pipe(plugins.rename(config.normalize.rename))
  .pipe(gulp.dest(config.normalize.dest));
});

// Copy specified Typicons from `bower_components` to the SVG sprite staging area; modelled after the Ubik task
gulp.task('bower-typicons', function() {

  // Iterate through the Ubik array and wrap each component in a glob pattern to handle the nested directory structure
  config.typicons.icons.forEach(function(icon, i, icons) {
    icons[i] = config.typicons.src+icon+'.svg';
  });
  return gulp.src(config.typicons.icons)
  .pipe(plugins.changed(config.typicons.dest))
  .pipe(gulp.dest(config.typicons.dest));
});
