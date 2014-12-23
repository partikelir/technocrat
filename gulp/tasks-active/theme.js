// ==== THEME ==== //

// These tasks are specific to WordPress themes

var gulp        = require('gulp')
  , gutil       = require('gulp-util')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config')
  , source      = config.paths.source
  , build       = config.paths.build
  , dist        = config.paths.dist
  , lang        = config.paths.lang
  , bower       = config.paths.bower
  , ubik        = config.ubik
;



// == PHP == //

gulp.task('php', ['php-core', 'ubik']);

// Copy PHP source files to the build directory
gulp.task('php-core', function() {
  return gulp.src(source+'**/*.php')
  .pipe(gulp.dest(build));
});



// == LANGUAGES == //

// Copy everything under `src/languages` indiscriminately
gulp.task('languages', function() {
  return gulp.src(source+lang+'**/*')
  .pipe(gulp.dest(build+lang));
});



// All the theme tasks in one
gulp.task('theme', ['languages', 'php']);
