// ==== UTILITIES ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , del         = require('del')
  , config      = require('../config').utils
;

// Totally wipe the contents of the `dist` folder to prepare for a clean build
gulp.task('wipe', function(cb) {
  del(config.wipe, cb)
});

// Clean out junk files after build
gulp.task('clean', ['build', 'wipe'], function(cb) {
  del(config.clean, cb)
});

// Copy everything in the `build` folder (except previously minified stylesheets) to the `dist/project` folder
gulp.task('copy-dist', ['clean'], function() {
  return gulp.src(config.dist.src)
  .pipe(gulp.dest(config.dist.dest));
});
