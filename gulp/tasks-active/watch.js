// ==== WATCH ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').watch
;

// Watch task: build stuff when files are modified, livereload when anything in the `build` or `dist` folders change
gulp.task('watch', ['lr-server'], function() {
  gulp.watch(config.styles, ['styles']);
  gulp.watch(config.scripts, ['scripts']);
  gulp.watch(config.images, ['images']);
  gulp.watch(config.theme, ['theme']);
  gulp.watch(config.livereload).on('change', function(file) {
    plugins.livereload.changed(file.path);
  });
});
