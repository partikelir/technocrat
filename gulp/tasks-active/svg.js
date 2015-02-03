// ==== SVG ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../../gulpconfig').svg
;

// Generate an optimized SVG sprite sheet file from individual SVG source icons
// See also: `bower.js` for the task that copies icons into the project source folder
gulp.task('svg-store', function() {
  return gulp.src(config.svgstore.src)
  .pipe(plugins.svgstore(config.svgstore.options))
  .pipe(plugins.cheerio(config.transform))
  .pipe(gulp.dest(config.svgstore.dest));
});

// PNG fallbacks for use with SVG For Everybody when providing support for IE 8 and below: https://github.com/jonathantneal/svg4everybody
// This task is resource-intensive and must be triggered manually!
gulp.task('svg-png-fallback', function() {
  return gulp.src(config.svg2png.src)
  .pipe(plugins.svg2png())
  .pipe(plugins.rename({ prefix: config.svgstore.options.fileName+'.'+config.svgstore.options.prefix })) // File naming convention required by SVG For Everybody
  .pipe(gulp.dest(config.svg2png.dest));
});

// Master SVG sprite task
gulp.task('svg', ['svg-store']);
