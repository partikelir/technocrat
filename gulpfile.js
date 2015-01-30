// ==== GULPFILE ==== //

// This configuration follows the modular design of the `gulp-starter` project by Dan Tello: https://github.com/greypants/gulp-starter
// Explore the task files under `/gulp/tasks-active` for more...

var requireDir = require('require-dir');
requireDir('./gulp/tasks-active');

// @TODO: a proper wipe task; currently only the `dist` folder is wiped (wait for Gulp 4)
// @TODO: px to rem CSS post-processing?
// @TODO: support for multiple icon sheets; currently we'd need to copy all tasks/config settings to make a second set
// @TODO: browsersync tuning; only open a new window when needed
// @TODO: better error handling; we don't want gulp to drop anytime there's an error in a stylesheet or whatever (wait for Gulp 4)
// @TODO: source maps wherever appropriate (wait for Libsass to mature)
// @TODO: reduce unnecessary wrapper plugins; see: https://github.com/sogko/gulp-recipes/tree/master/unnecessary-wrapper-gulp-plugins
// @TODO: expand on i18n functionality; explore the possibility of generating POT files from source
// @TODO: rtl support with https://github.com/jjlharrison/gulp-rtlcss
// @TODO: combine media queries with https://github.com/frontendfriends/gulp-combine-mq
// @TODO: integrate deployment and git updating?
// @TODO: html5-history-api v4.2 (next release) switches the low and high compatibility files; we'll need to update to use history.js instead of ie8+
