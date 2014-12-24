// ==== STYLES ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').styles
;

// Build stylesheets from source Sass files, autoprefix, and make a minified copy (for debugging); @TODO: source maps
gulp.task('styles', function() {
  return gulp.src(config.build.src)
  .pipe(plugins.rubySass(config.build.sass))
  .pipe(plugins.autoprefixer(config.build.autoprefixer))
  .pipe(gulp.dest(config.build.dest))
  .pipe(plugins.rename(config.build.rename))
  .pipe(plugins.minifyCss(config.build.minify))
  .pipe(gulp.dest(config.build.dest));
});

// Take whatever stylesheets made it to the build folder, minify them, and sent them to `dist`
gulp.task('styles-dist', ['copy-dist'], function() {
  return gulp.src(config.dist.src)
  .pipe(plugins.minifyCss(config.dist.minify))
  .pipe(gulp.dest(config.dist.dest));
});
