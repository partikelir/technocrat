// ==== SVG ==== //

// Based on the approach outlined in this article by Mike Street at Liquid Light: https://www.liquidlight.co.uk/blog/article/creating-svg-sprites-using-gulp-and-sass/

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').svg
;

// Optimize SVG files and create the SVG sprite; this approach presumes you only have so many SVG files to turn into a sprite
gulp.task('svg-sprites', function() {
  return gulp.src(config.sprites.src)
  .pipe(plugins.svgo())
  .pipe(plugins.svgSprites(config.sprites.options))
  .pipe(gulp.dest(config.sprites.dest));
});

// Generate a PNG fallback image for IE9
gulp.task('svg-sprites-png', ['svg-sprites'], function() {
  return gulp.src(config.images.src)
  .pipe(plugins.svg2png())
  .pipe(gulp.dest(config.images.dest));
});

// Master SVG sprite task: svg-sprite -> svg-sprite-png
gulp.task('svg', ['svg-sprites-png']);
