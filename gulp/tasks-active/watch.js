// ==== WATCH ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').watch
;

// Watch task: build stuff when files are modified, inform livereload when anything in the `build` or `dist` folders change
gulp.task('watch-old', ['lr-server'], function() {
  gulp.watch(config.src.styles, ['styles']);
  gulp.watch(config.src.scripts, ['scripts']);
  gulp.watch(config.src.images, ['images']);
  gulp.watch(config.src.theme, ['theme']);
  gulp.watch(config.src.livereload).on('change', function(file) {
    plugins.livereload.changed(file.path);
  });
});

// Watch (BS)
gulp.task('watch-browsersync', ['browsersync'], function() {
  gulp.watch(config.src.styles, ['styles']);
  gulp.watch(config.src.scripts, ['scripts']);
  gulp.watch(config.src.images, ['images']);
  gulp.watch(config.src.theme, ['theme']);
});

// Watch (LR): build stuff when files are modified, inform livereload when anything in the `build` or `dist` folders change
gulp.task('watch-livereload', ['lr-server'], function() {
  gulp.watch(config.src.styles, ['styles']);
  gulp.watch(config.src.scripts, ['scripts']);
  gulp.watch(config.src.images, ['images']);
  gulp.watch(config.src.theme, ['theme']);
  gulp.watch(config.src.livereload).on('change', function(file) {
    plugins.livereload.changed(file.path);
  });
});

// Master control switch for the watch task
gulp.task('watch', ['watch-browsersync']);
