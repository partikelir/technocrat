// ==== MAIN ==== //

var gulp = require('gulp');

// The default task runs watch which boots up the Livereload server after an initial build is finished
gulp.task('default', ['watch']);

// Build styles and scripts; copy PHP and language files
gulp.task('build', ['styles', 'scripts', 'images', 'theme']);

// Prepare a distribution: the properly minified, uglified, and sanitized version of the theme ready for installation
// NOTE: this is a resource-intensive task since it wipes the `dist` folder every time
// Master dist task: wipe -> build -> clean -> copy -> images/styles
gulp.task('dist', ['images-dist', 'styles-dist']);
