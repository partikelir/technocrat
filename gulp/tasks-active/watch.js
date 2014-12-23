// ==== WATCH ==== //

var gulp        = require('gulp')
  , gutil       = require('gulp-util')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config')
  , source      = config.paths.source
  , build       = config.paths.build
  , dist        = config.paths.dist
  , bower       = config.paths.bower
;

// Watch task: build stuff when files are modified, livereload when anything in the `build` or `dist` folders change
gulp.task('watch', ['lr-server'], function() {
  gulp.watch(source+'scss/**/*.scss', ['styles']);
  gulp.watch([source+'js/**/*.js', bower+'**/*.js'], ['scripts']);
  gulp.watch(source+'**/*(*.png|*.jpg|*.jpeg|*.gif)', ['images']);
  gulp.watch(source+'**/*.php', ['php']);
  gulp.watch([build+'**/*', dist+'**/*']).on('change', function(file) {
    plugins.livereload.changed(file.path);
  });
});
