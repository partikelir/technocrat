// ==== BUILD ==== //

var gulp = require('gulp');

// Build styles and scripts; copy PHP files
gulp.task('build', ['styles', 'scripts', 'images', 'theme']);
