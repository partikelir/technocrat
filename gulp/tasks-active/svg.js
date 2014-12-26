// ==== SVG ==== //

// Based on the approach outlined in this article by Mike Street at Liquid Light: https://www.liquidlight.co.uk/blog/article/creating-svg-sprites-using-gulp-and-sass/

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').svg
;


gulp.task('svg-sprite', function() {
  return gulp.src(config.sprites.src)
  .pipe(plugins.svgo())
  .pipe(plugins.svgSprites(config.sprites.options))
  .pipe(gulp.dest(config.sprites.dest));
});

gulp.task('svg-sprite-fallback', ['svg-sprite'], function() {
  return gulp.src(basePaths.dest + paths.sprite.svg)
  .pipe(plugins.svg2png())
  .pipe(gulp.dest(paths.images.dest));
});

gulp.task('sprite', ['svg-sprite-fallback']);
