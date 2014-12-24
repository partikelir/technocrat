// ==== IMAGES ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').images
;

// Copy images from the source folder to `build`
gulp.task('images', function() {
  return gulp.src(config.build.src)
  .pipe(gulp.dest(config.build.dest));
});

// Optimize images in the `dist` folder
gulp.task('images-dist', ['copy-dist'], function() {
  return gulp.src(config.dist.src)
  .pipe(plugins.imagemin(config.imagemin))
  .pipe(gulp.dest(config.dist.dest));
});
