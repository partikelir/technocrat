// ==== GULPFILE ==== //

// This configuration follows the modular design of the `gulp-starter` project by Dan Tello: https://github.com/greypants/gulp-starter
// Explore the task files under `/gulp/tasks-active` for more...

var requireDir = require('require-dir');
requireDir('./gulp/tasks-active');

// @TODO: a proper wipe task; currently only the `dist` folder is wiped
// @TODO: SVG sprites
// @TODO: browsersync tuning
// @TODO: error handling; we don't want gulp to drop anytime there's an error in a stylesheet or whatever
// @TODO: reduce watch list to avoid file system errors
// @TODO: source maps wherever appropriate
// @TODO: reduce unnecessary wrapper plugins; see: https://github.com/sogko/gulp-recipes/tree/master/unnecessary-wrapper-gulp-plugins
// @TODO: integrate deployment and git updating?
// @TODO: expand on i18n functionality; explore the possibility of generating POT files from source
