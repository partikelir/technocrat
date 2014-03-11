// Initialization sequence
var gulp      = require('gulp')
  , gutil     = require('gulp-util')
  , plugins   = require("gulp-load-plugins")({ camelize: true })
  , lr        = require('tiny-lr')
  , server    = lr()
;

var build     = "./pendrell/";

gulp.task('styles', function() {
  return gulp.src(['assets/src/scss/*.scss', '!assets/src/scss/_*.scss'])
  .pipe(plugins.rubySass({ compass: true })) // don't forget to `gem install sass`
  .pipe(plugins.autoprefixer('last 2 versions', 'ie 9', 'ios 6', 'android 4'))
  .pipe(gulp.dest('assets/staging'))
  .pipe(plugins.minifyCss({ keepSpecialComments: 1 }))
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(build));
});

gulp.task('plugins', function() {
  return gulp.src(['assets/src/js/plugins/*.js', 'assets/src/js/plugins.js'])
  .pipe(plugins.concat('pendrell-plugins.js'))
  .pipe(gulp.dest('assets/staging'))
  .pipe(plugins.rename({ suffix: '.min' }))
  .pipe(plugins.uglify())
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(build));
});

gulp.task('scripts', function() {
  return gulp.src(['assets/src/js/*.js', '!assets/src/js/plugins.js'])
  .pipe(plugins.jshint('.jshintrc'))
  .pipe(plugins.jshint.reporter('default'))
  .pipe(plugins.concat('pendrell.js'))
  .pipe(gulp.dest('assets/staging'))
  .pipe(plugins.rename({ suffix: '.min' }))
  .pipe(plugins.uglify())
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(build));
});

gulp.task('images', function() {
  return gulp.src('assets/src/img/**/*')
  .pipe(plugins.cache(plugins.imagemin({ optimizationLevel: 7, progressive: true, interlaced: true })))
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(build + 'img/'));
});

gulp.task('watch', function() {

  // Listen on port 35729
  server.listen(35729, function (err) {
  if (err) {
    return console.log(err)
  };

  // Watch .scss files
  gulp.watch('assets/src/scss/*.scss', ['styles']);

  // Watch .js files
  gulp.watch('assets/src/js/**/*.js', ['plugins', 'scripts']);

  // Watch image files
  gulp.watch('assets/src/img/**/*', ['images']);

  });

});

gulp.task('default', ['styles', 'plugins', 'scripts', 'images', 'watch']);
