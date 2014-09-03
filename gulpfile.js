// ==== SETUP ==== //

// Project configuration
var project     = 'pendrell'
  , build       = './build/'
  , dist        = './dist/'
  , src         = './src/'
  , assets      = src+'assets/'
  , bower       = assets+'bower_components/'
;

// Initialization sequence
var gulp        = require('gulp')
  , gutil       = require('gulp-util')
  , plugins     = require('gulp-load-plugins')({ camelize: true })  // This loads all modules prefixed with "gulp-" to plugin.moduleName
;



// ==== STYLES ==== //

// Stylesheet handling; don't forget `gem install sass`; Compass is not included by default here
gulp.task('styles', function() {
  return gulp.src([assets+'scss/*.scss', '!'+assets+'scss/_*.scss']) // Ignore partials
  .pipe(plugins.rubySass({
    loadPath: bower // Adds the `bower_components` directory to the load path so you can @import directly
  , precision: 8
  , 'sourcemap=none': true // Not yet ready for prime time! Sass 3.4 has sourcemaps on by default but this causes some problems from the Gulp toolchain
  }))
  .pipe(plugins.autoprefixer('last 2 versions', 'ie 9', 'ios 6', 'android 4'))
  .pipe(gulp.dest(build))
  .pipe(plugins.rename({suffix: '.min'}))
  .pipe(plugins.minifyCss({ keepSpecialComments: 1 }))
  .pipe(gulp.dest(build));
});



// ==== SCRIPTS ==== //

// Scripts; broken out into different tasks to create specific bundles which are then compressed in place
gulp.task('scripts', ['scripts-lint', 'scripts-core', 'scripts-ajaxify', 'scripts-html5', 'scripts-prism'], function(){
  return gulp.src([build+'js/**/*.js', '!'+build+'js/**/*.min.js']) // Avoid recursive min.min.min.js
  .pipe(plugins.rename({suffix: '.min'}))
  .pipe(plugins.uglify())
  .pipe(gulp.dest(build+'js/'));
});

// Only lint custom scripts; ignore the error-riddled custom build of Prism
gulp.task('scripts-lint', function() {
  return gulp.src([assets+'js/**/*.js', '!'+assets+'js/prism.js'])
  .pipe(plugins.jshint('.jshintrc'))
  .pipe(plugins.jshint.reporter('default'));
});

// These are the core custom scripts
gulp.task('scripts-core', function() {
  return gulp.src([
    assets+'js/core.js'
  , assets+'js/navigation.js'
  ])
  .pipe(plugins.concat('core.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Ajaxify module; the order of dependencies is important here; relies on jQuery, already loaded in the head
gulp.task('scripts-ajaxify', function() {
  return gulp.src([
    bower+'history.js/scripts/bundled-uncompressed/html4+html5/jquery.history.js'
  , bower+'spin.js/spin.js'
  , bower+'spin.js/jquery.spin.js'
  , assets+'js/ajaxify.js'
  ])
  .pipe(plugins.concat('ajaxify.js'))
  .pipe(gulp.dest(build+'js/'));
});

// HTML5 shiv that originally came with Twenty Twelve; provides backwards compatibility with legacy IE browsers: https://github.com/aFarkas/html5shiv
gulp.task('scripts-html5', function() {
  return gulp.src(bower+'html5shiv/dist/html5shiv.js')
  .pipe(plugins.concat('html5shiv.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Prism code highlighting; roll your own at http://prismjs.com/
gulp.task('scripts-prism', function() {
  return gulp.src(assets+'js/prism.js')
  .pipe(plugins.concat('prism.js'))
  .pipe(gulp.dest(build+'js/'));
});



// ==== PACKAGING ==== //

// Clean out junk files after build
gulp.task('clean', ['build'], function() {
  return gulp.src(build+'**/.DS_Store', { read: false })
  .pipe(plugins.rimraf());
});

// Totally wipe the contents of the distribution folder after doing a clean build
gulp.task('wipe', ['clean'], function() {
  return gulp.src(dist, {read: false })
  .pipe(plugins.rimraf());
});

// Prepare a distribution, the properly minified, uglified, and sanitized version of the theme ready for installation
gulp.task('package', ['wipe'], function() {

  // Define filters
  var styles = plugins.filter(['**/*.css', '!**/*.min.css'])
    , images = plugins.filter(['**/*.(jpg|jpeg|gif|png)']) // @TODO: double-check this works as expected
  ;

  // Take everything in the build folder
  return gulp.src([build+'**/*', '!'+build+'**/*.min.css'])

  // Compress existing stylesheets rather than duplicating previously compressed copies
  .pipe(styles)
  .pipe(plugins.minifyCss({ keepSpecialComments: 1 }))
  .pipe(styles.restore())

  // Compress images
  .pipe(images)
  .pipe(plugins.cache(plugins.imagemin({
    optimizationLevel: 7
  , progressive: true
  , interlaced: true
  })))
  .pipe(images.restore())

  // Send everything to the `dist/project` folder
  .pipe(gulp.dest(dist+project+'/'));
});



// ==== HACKS ==== //

// Executed on `bower update` which is in turn triggered by `npm update`; used to get around Sass's inability to properly @import vanilla CSS
gulp.task('bower_components', function() {
  return gulp.src(bower+'normalize.css/normalize.css')
  .pipe(plugins.rename('_base_normalize.scss'))
  .pipe(gulp.dest(assets+'scss'));
});



// ==== WATCH & RELOAD ==== //

// Start the livereload server; not asynchronous
gulp.task('server', ['build'], function() {
  plugins.livereload.listen(35729, function (err) {
    if (err) {
      return console.log(err)
    };
  });
});

// Watch task: build styles and scripts when files are modified, livereload when anything in the `build` or `dist` folders change
gulp.task('watch', ['server'], function() {
  gulp.watch(assets+'scss/**/*.scss', ['styles']);
  gulp.watch(assets+'js/**/*.js', ['scripts']);
  gulp.watch([build+'**/*', dist+'**/*']).on('change', function(file) {
    plugins.livereload.changed(file.path);
  });
});



// ==== TASKS ==== //

// Build styles and scripts
gulp.task('build', ['styles', 'scripts']);

// Release creates a clean distribution package under `dist` after running build, clean, and wipe in sequence
// This is a resource-intensive task; @TODO: integrate deployment and git updating?
gulp.task('release', ['package']);

// The default task runs watch which boots up the Livereload server after an initial build is finished
gulp.task('default', ['watch']);
