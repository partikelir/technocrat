// ==== UTILITIES ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , del         = require('del')
  , config      = require('../../gulpconfig').utils
;

// Totally wipe the contents of the `dist` folder to prepare for a clean build; additionally trigger Bower-related tasks to ensure we have the latest source files
gulp.task('utils-wipe', ['bower', 'icons'], function() {
  return del(config.wipe);
});

// Clean out junk files after build
gulp.task('utils-clean', ['utils-wipe', 'build'], function() {
  return del(config.clean);
});

// Copy everything in the `build` folder to the `dist/project` folder
gulp.task('utils-dist', ['utils-clean'], function() {
  return gulp.src(config.dist.src)
  .pipe(gulp.dest(config.dist.dest));
});
