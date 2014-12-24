// ==== STYLES ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').styles
;

// Stylesheet handling
gulp.task('styles', function() {
  return gulp.src(config.build.src) // Ignore partials
  .pipe(plugins.rubySass(config.build.sass))
  .pipe(plugins.autoprefixer(config.build.autoprefixer))
  .pipe(gulp.dest(config.build.dest))
  .pipe(plugins.rename(config.build.rename))
  .pipe(plugins.minifyCss(config.build.minify))
  .pipe(gulp.dest(config.build.dest));
});
