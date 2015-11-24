// ==== MAIN ==== //

var gulp = require('gulp');

// Default task chain: build -> (livereload or browsersync) -> watch
gulp.task('default', ['watch']);

// One-off setup tasks
gulp.task('setup', ['icons', 'utils-normalize']);

// Build a working copy of the theme
gulp.task('build', ['images', 'scripts', 'styles', 'svg', 'theme']);

// Dist task chain: wipe -> build -> clean -> copy -> styles -> images
// NOTE: this is a resource-intensive task!
gulp.task('dist', ['images-dist']);
