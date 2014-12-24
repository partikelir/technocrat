// ==== BROWSERSYNC ==== //

var gulp        = require('gulp')
  , browsersync = require('browser-sync')
  , config      = require('../config').browsersync
;

gulp.task('browsersync', ['build'], function(){
  browsersync(config);
});
