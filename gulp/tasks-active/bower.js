// ==== BOWER ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').bower
  , normalize   = config.normalize
;

// This task is executed on `bower update` which is in turn triggered by `npm update`
// Use this task to manually copy front-end dependencies into your working source folder when needed
gulp.task('bower', ['bower-normalize']);

// Used to get around Sass's inability to properly @import vanilla CSS; see: https://github.com/sass/sass/issues/556
gulp.task('bower-normalize', function() {
  return gulp.src(normalize.src)
  .pipe(plugins.rename(normalize.name))
  .pipe(gulp.dest(normalize.dest));
});
