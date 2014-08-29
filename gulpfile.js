// Project configuration
var project     = 'pendrell'
  , src         = './assets/src/'
  , srcx         = 'assets/src/'
  , bower       = './assets/bower_components/'
  , staging     = './assets/staging/'
  , build       = './'+project+'/'
;

// Initialization sequence
var gulp        = require('gulp')
  , gutil       = require('gulp-util')
  , plugins     = require('gulp-load-plugins')({ camelize: true })  // This loads all modules prefixed with "gulp-" to plugin.moduleName
  , lr          = require('tiny-lr')
  , server      = lr()                                              // Invoke LiveReload server
;

// Don't forget `gem install sass`; Compass is not included by default
gulp.task('styles', function() {
  return gulp.src([src+'scss/*.scss', '!'+src+'scss/_*.scss']) // Ignore partials
  .pipe(plugins.rubySass({ precision: 8 }))
  .pipe(plugins.autoprefixer('last 2 versions', 'ie 9', 'ios 6', 'android 4'))
  .pipe(gulp.dest(staging))
  .pipe(plugins.minifyCss({ keepSpecialComments: 1 }))
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(build));
});

gulp.task('scripts-main', function() {
  return gulp.src([
      src+'js/main.js'
    , src+'js/navigation.js'
  ])
  .pipe(plugins.jshint('.jshintrc'))
  .pipe(plugins.jshint.reporter('default'))
  .pipe(plugins.concat(project+'.js'))
  .pipe(gulp.dest(staging))
  .pipe(plugins.rename({ suffix: '.min' }))
  .pipe(plugins.uglify())
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(build));
});

gulp.task('scripts-ajaxify', function() {
  // Manually load uncompressed Bower components in order; if you need something more complex look into RequireJS or Browserify
  return gulp.src([
      bower+'history.js/scripts/bundled-uncompressed/html4+html5/jquery.history.js'
    , bower+'spin.js/spin.js'
    , bower+'spin.js/jquery.spin.js'
    , src+'js/ajaxify.js'
  ])
  .pipe(plugins.concat(project+'-ajaxify.js'))
  .pipe(gulp.dest(staging))
  .pipe(plugins.rename({ suffix: '.min' }))
  .pipe(plugins.uglify())
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(build));
});

gulp.task('scripts-prism', function() {
  return gulp.src([src+'js/prism.js'])
  .pipe(plugins.concat(project+'-prism.js'))
  .pipe(gulp.dest(staging))
  .pipe(plugins.rename({ suffix: '.min' }))
  .pipe(plugins.uglify())
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(build));
});

gulp.task('images', function() {
  return gulp.src(src+'img/**/*')
  .pipe(plugins.cache(plugins.imagemin({
    optimizationLevel: 7
  , progressive: true
  , interlaced: true
  })))
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(build+'img/'));
});

gulp.task('clean', function() {
  return gulp.src(build+'**/.DS_Store', { read: false })
  .pipe(plugins.clean());
});

gulp.task('bower_components', function() { // Executed on `bower update`
  return gulp.src([bower+'normalize.css/normalize.css'])
  .pipe(plugins.rename('_base_normalize.scss'))
  .pipe(gulp.dest(src+'scss'));
});

gulp.task('watch', function() {
  server.listen(35729, function (err) { // Listen on port 35729
    if (err) {
      return console.log(err)
    };
    gulp.watch(src+'scss/**/*.scss', ['styles']);
    gulp.watch(src+'js/**/*.js', ['scripts']);
    gulp.watch(src+'img/**/*', ['images']);
    gulp.watch(build+'**/*.php').on('change', function(file) { plugins.livereload(server).changed(file.path); });
  });
});

gulp.task('scripts', ['scripts-main', 'scripts-ajaxify', 'scripts-prism']);
gulp.task('build', ['styles', 'scripts', 'images', 'clean']);
gulp.task('default', ['build', 'watch']);
