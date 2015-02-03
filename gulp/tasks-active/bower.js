// ==== BOWER ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../../gulpconfig').bower
;

// This task is executed on `bower update` which is in turn triggered by `npm update`; use this to copy and transform source files from `bower_components`
gulp.task('bower', ['bower-icons', 'bower-normalize']);

// Support for multiple icon sources; use one task per set to handle different prefixes and source locations
gulp.task('bower-icons', ['bower-ionicons', 'bower-typicons']);

// Ionicons; copy specified SVG icon source files to the theme for assembly into a master icon sheet
gulp.task('bower-ionicons', function() {
  var iconset = config.iconsets.ionicons;

  // Iterate through the icon set array and set the full path of the source file
  iconset.icons.forEach(function(icon, i, icons) {
    icons[i] = iconset.src+icon+'.svg';
  });
  return gulp.src(iconset.icons)
  .pipe(plugins.rename({ prefix: iconset.prefix }))
  .pipe(plugins.changed(iconset.dest))
  .pipe(plugins.imagemin())
  .pipe(gulp.dest(iconset.dest));
});

// Typicons; copy specified SVG icon source files to the theme for assembly into a master icon sheet
gulp.task('bower-typicons', function() {
  var iconset = config.iconsets.typicons;

  // Iterate through the icon set array and set the full path of the source file
  iconset.icons.forEach(function(icon, i, icons) {
    icons[i] = iconset.src+icon+'.svg';
  });
  return gulp.src(iconset.icons)
  .pipe(plugins.rename({ prefix: iconset.prefix }))
  .pipe(plugins.changed(iconset.dest))
  .pipe(plugins.imagemin())
  .pipe(gulp.dest(iconset.dest));
});

// Used to get around Sass's inability to properly @import vanilla CSS; see: https://github.com/sass/sass/issues/556
gulp.task('bower-normalize', function() {
  return gulp.src(config.normalize.src)
  .pipe(plugins.changed(config.normalize.dest))
  .pipe(plugins.rename(config.normalize.rename))
  .pipe(gulp.dest(config.normalize.dest));
});
