// ==== SVG ==== //

// Based on the approach outlined in this article by Mike Street at Liquid Light: https://www.liquidlight.co.uk/blog/article/creating-svg-sprites-using-gulp-and-sass/

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').svg
;

// Generate an optimized icons file from individual SVG source icons
gulp.task('svg-store', ['bower-typicons'], function() {
  return gulp.src(config.svgstore.src)
  .pipe(plugins.svgo())
  .pipe(plugins.svgstore(config.svgstore.options))
  .pipe(gulp.dest(config.svgstore.dest));
});

// PNG fallbacks are required for external SVG maps; see https://github.com/jonathantneal/svg4everybody
gulp.task('svg-store-png', function() {
  return gulp.src(config.svgstore.src)
  .pipe(plugins.svg2png())
  .pipe(gulp.dest(config.svgstore.dest));
});

// Master SVG sprite task
gulp.task('svg', ['svg-store']);
