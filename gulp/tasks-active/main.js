// ==== MAIN ==== //

// Main task chaings are defined here

var gulp = require('gulp');

// Build styles and scripts; copy PHP files
gulp.task('build', ['styles', 'scripts', 'images', 'theme']);

// The default task runs watch which boots up the Livereload server after an initial build is finished
gulp.task('default', ['watch']);
