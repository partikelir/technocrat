// ==== GULPFILE ==== //

// This configuration follows the modular design of the `gulp-starter` project by Dan Tello: https://github.com/greypants/gulp-starter
// Explore the task files under `/gulpfile.js/tasks` for more...

var requireDir = require('require-dir');
requireDir('./tasks');

// @TODO: px to rem CSS post-processing instead of using a SCSS mixin?
// @TODO: support for multiple icon sheets; currently we'd need to copy all tasks/config settings to make a second set
// @TODO: better error handling; we don't want gulp to drop anytime there's an error in a stylesheet or whatever (wait for Gulp 4)
// @TODO: reduce unnecessary wrapper plugins; see: https://github.com/sogko/gulp-recipes/tree/master/unnecessary-wrapper-gulp-plugins
// @TODO: expand on i18n functionality; explore the possibility of generating POT files from source
// @TODO: rtl support with https://github.com/jjlharrison/gulp-rtlcss
// @TODO: combine media queries with https://github.com/hail2u/node-css-mqpacker
// @TODO: file size reports to console
