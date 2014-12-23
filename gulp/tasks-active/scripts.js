// ==== SCRIPTS ==== //

var gulp        = require('gulp')
  , gutil       = require('gulp-util')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config')
  , source      = config.paths.source
  , build       = config.paths.build
  , dist        = config.paths.dist
  , bower       = config.paths.bower
;

// Scripts broken out into different tasks to create specific bundles which are then compressed in place
// Rationale: why bother letting WP queue individual scripts when we can easily roll bundles for all occasions?
gulp.task('scripts', [
    'scripts-lint',
    'scripts-html5',
    'scripts-contact',
    'scripts-core',
    'scripts-pg8',
    'scripts-pg8-pf',
    'scripts-pg8-prism',
    'scripts-pg8-pf-prism',
    'scripts-pf',
    'scripts-pf-prism',
    'scripts-prism',
    'scripts-xn8',
    'scripts-xn8-prism'
  ], function(){
  return gulp.src([build+'js/**/*.js', '!'+build+'js/**/*.min.js']) // Avoid recursive min.min.min.js
  .pipe(plugins.rename({suffix: '.min'}))
  .pipe(plugins.uglify())
  .pipe(gulp.dest(build+'js/'));
});

// Only lint custom scripts; ignore the error-riddled custom build of Prism
gulp.task('scripts-lint', function() {
  return gulp.src([source+'js/**/*.js', '!'+source+'js/prism.js'])
  .pipe(plugins.jshint('.jshintrc'))
  .pipe(plugins.jshint.reporter('default'));
});

// HTML5 shiv that originally came with Twenty Twelve; provides backwards compatibility with legacy IE browsers: https://github.com/aFarkas/html5shiv
gulp.task('scripts-html5', function() {
  return gulp.src(bower+'html5shiv/dist/html5shiv.js')
  .pipe(gulp.dest(build+'js/'));
});

// Contact form standalone script (not integrated into any general bundle)
gulp.task('scripts-contact', function() {
  return gulp.src([
    bower+'jquery-validation/dist/jquery.validate.js'
  , source+'js/contact-form.js'
  ])
  .pipe(plugins.concat('contact-form.js'))
  .pipe(gulp.dest(build+'js/'));
});

// These are the core custom scripts
gulp.task('scripts-core', function() {
  return gulp.src([
    source+'js/navigation.js'
  , source+'js/core.js'
  ])
  .pipe(plugins.concat('p-core.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Page Loader: loads the next page of content with jQuery/AJAX
gulp.task('scripts-pg8', ['scripts-core'], function() {
  return gulp.src([
    bower+'html5-history-api/history.iegte8.js'
  , bower+'spin.js/spin.js'
  , bower+'spin.js/jquery.spin.js'
  , source+'js/page-loader.js'
  , build+'js/p-core.js'
  ])
  .pipe(plugins.concat('p-pg8.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Page Loader + Picturefill
gulp.task('scripts-pg8-pf', ['scripts-core'], function() {
  return gulp.src([
    bower+'html5-history-api/history.iegte8.js'
  , bower+'spin.js/spin.js'
  , bower+'spin.js/jquery.spin.js'
  , source+'js/page-loader.js'
  , bower+'picturefill/dist/picturefill.js'
  , build+'js/p-core.js'
  ])
  .pipe(plugins.concat('p-pg8-pf.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Page Loader
gulp.task('scripts-pg8-prism', ['scripts-core'], function() {
  return gulp.src([
    bower+'html5-history-api/history.iegte8.js'
  , bower+'spin.js/spin.js'
  , bower+'spin.js/jquery.spin.js'
  , source+'js/page-loader.js'
  , source+'js/prism.js'
  , build+'js/p-core.js'
  ])
  .pipe(plugins.concat('p-pg8-prism.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Page Loader + Picturefill + Prism
gulp.task('scripts-pg8-pf-prism', ['scripts-core'], function() {
  return gulp.src([
    bower+'html5-history-api/history.iegte8.js'
  , bower+'spin.js/spin.js'
  , bower+'spin.js/jquery.spin.js'
  , source+'js/page-loader.js'
  , source+'js/prism.js'
  , bower+'picturefill/dist/picturefill.js'
  , build+'js/p-core.js'
  ])
  .pipe(plugins.concat('p-pg8-pf-prism.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Picturefill
gulp.task('scripts-pf', ['scripts-core'], function() {
  return gulp.src([
    bower+'picturefill/dist/picturefill.js'
  , build+'js/p-core.js'
  ])
  .pipe(plugins.concat('p-pf.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Picturefill + Prism
gulp.task('scripts-pf-prism', ['scripts-core'], function() {
  return gulp.src([
    bower+'picturefill/dist/picturefill.js'
  , source+'js/prism.js'
  , build+'js/p-core.js'
  ])
  .pipe(plugins.concat('p-pf-prism.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Prism code highlighting; roll your own at http://prismjs.com/
gulp.task('scripts-prism', ['scripts-core'], function() {
  return gulp.src([
    source+'js/prism.js'
  , build+'js/p-core.js'
  ])
  .pipe(plugins.concat('p-prism.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Ajaxinate: brute-force page loading with jQuery/AJAX; the order of dependencies is important here
gulp.task('scripts-xn8', ['scripts-core'], function() {
  return gulp.src([
    bower+'html5-history-api/history.iegte8.js'
  , bower+'spin.js/spin.js'
  , bower+'spin.js/jquery.spin.js'
  , bower+'ajaxinate/src/ajaxinate.js'
  , bower+'ajaxinate-wp/src/ajaxinate-wp.js'
  , source+'js/ajaxinate.js'
  , build+'js/p-core.js'
  ])
  .pipe(plugins.concat('p-xn8.js'))
  .pipe(gulp.dest(build+'js/'));
});

// Ajaxinate + Prism
gulp.task('scripts-xn8-prism', ['scripts-core'], function() {
  return gulp.src([
    bower+'html5-history-api/history.iegte8.js'
  , bower+'spin.js/spin.js'
  , bower+'spin.js/jquery.spin.js'
  , bower+'ajaxinate/src/ajaxinate.js'
  , bower+'ajaxinate-wp/src/ajaxinate-wp.js'
  , source+'js/ajaxinate.js'
  , source+'js/prism.js'
  , build+'js/p-core.js'
  ])
  .pipe(plugins.concat('p-xn8-prism.js'))
  .pipe(gulp.dest(build+'js/'));
});
