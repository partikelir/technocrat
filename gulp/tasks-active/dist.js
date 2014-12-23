// ==== DISTRIBUTION ==== //

// Prepare a distribution, the properly minified, uglified, and sanitized version of the theme ready for installation

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , del         = require('del')
  , config      = require('../config')
  , source      = config.paths.source
  , build       = config.paths.build
  , dist        = config.paths.dist
;

// Clean out junk files after build
gulp.task('clean', ['build'], function(cb) {
  del([build+'**/.DS_Store'], cb)
});

// Totally wipe the contents of the distribution folder after doing a clean build
gulp.task('dist-wipe', ['clean'], function(cb) {
  del([dist], cb)
});

// Copy everything in the build folder (except previously minified stylesheets) to the `dist/project` folder
gulp.task('dist-copy', ['dist-wipe'], function() {
  return gulp.src([build+'**/*', '!'+build+'**/*.min.css'])
  .pipe(gulp.dest(dist));
});

// Minify stylesheets in place
gulp.task('dist-styles', ['dist-copy'], function() {
  return gulp.src([dist+'**/*.css', '!'+dist+'**/*.min.css'])
  .pipe(plugins.minifyCss({ keepSpecialComments: 1 }))
  .pipe(gulp.dest(dist));
});

// Optimize images in place
gulp.task('dist-images', ['dist-styles'], function() {
  return gulp.src([dist+'**/*.png', dist+'**/*.jpg', dist+'**/*.jpeg', dist+'**/*.gif', '!'+dist+'screenshot.png'])
  .pipe(plugins.imagemin({
    optimizationLevel: 7
  , progressive: true
  , interlaced: true
  }))
  .pipe(gulp.dest(dist));
});

// Release creates a clean distribution package under `dist` after running build, clean, and wipe in sequence
// NOTE: this is a resource-intensive task since it optimizes images every time
gulp.task('dist', ['dist-images']);
