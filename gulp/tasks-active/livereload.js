// ==== LIVERELOAD ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
;

// Start the livereload server; not asynchronous
gulp.task('lr-server', ['build'], function() {
  plugins.livereload.listen(35729, function (err) {
    if (err) {
      return console.log(err);
    };
  });
});
