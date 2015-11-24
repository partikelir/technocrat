// ==== SVG ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../../gulpconfig').svg
;

// Generate an optimized SVG sprite sheet file from individual SVG source icons
// Note: IE support requires svg4everybody; even then it's only IE9+ without fallback PNGs not generated here
// See also: `icons.js` for the task that copies icons into the project source folder
gulp.task('svg', function() {
  return gulp.src(config.src)
  .pipe(plugins.cheerio(config.transform.before))
  .pipe(plugins.svgstore())
  .pipe(gulp.dest(config.dest));
});
