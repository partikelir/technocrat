// ==== STYLES ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').styles
;

// Build stylesheets from source Sass files, autoprefix, and make a minified copy (for debugging)
// @TODO: https://github.com/sogko/gulp-recipes/blob/master/unnecessary-wrapper-gulp-plugins/clean-css.js
gulp.task('styles', function() {
  return gulp.src(config.build.src)
  .pipe(plugins.rubySass(config.rubySass))
  .pipe(plugins.sourcemaps.init()) // Source maps for Autoprefixer work; they're inline but removed when the CSS file is minified for production
  .pipe(plugins.autoprefixer(config.autoprefixer))
  .pipe(plugins.sourcemaps.write())
  .pipe(gulp.dest(config.build.dest)) // Drops the unminified CSS file into the `build` folder
  .pipe(plugins.rename(config.rename))
  .pipe(plugins.minifyCss(config.minify))
  .pipe(gulp.dest(config.build.dest)); // Drops a minified CSS file into the `build` folder for debugging
});

// Take whatever stylesheets made it to the build folder, minify them, and sent them to `dist`
gulp.task('styles-dist', ['utils-dist'], function() {
  return gulp.src(config.dist.src)
  .pipe(plugins.minifyCss(config.minify))
  .pipe(gulp.dest(config.dist.dest));
});
