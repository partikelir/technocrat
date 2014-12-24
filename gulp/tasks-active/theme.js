// ==== THEME ==== //

// These tasks are specific to WordPress themes

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').theme
;

// Copy PHP source files to the `build` folder
gulp.task('php', ['ubik'], function() {
  return gulp.src(config.php.src)
  .pipe(gulp.dest(config.php.dest));
});

// Copy everything under `src/languages` indiscriminately; @TODO: better language handling
gulp.task('lang', function() {
  return gulp.src(config.lang.src)
  .pipe(gulp.dest(config.lang.dest));
});

// All the theme tasks in one
gulp.task('theme', ['lang', 'php']);
