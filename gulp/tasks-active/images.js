// ==== IMAGES ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , images      = require('../config').images
;

// This merely copies; minification occurs during packaging
gulp.task('images', function() {
  return gulp.src(images.build.src)
  .pipe(gulp.dest(images.build.dest));
});
