// ==== ICONS ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , del         = require('del')
  , config      = require('../../gulpconfig').icons
;

// Each of the following tasks copies specified SVG icon source files to the theme for assembly into a master icon sheet
// For more info see: http://synapticism.com/dev/roll-your-own-svg-sprite-sheets-with-bower-and-gulp/

// Font Awesome
gulp.task('icons-awesome', function() {
  var iconset = config.awesome;

  // Iterate through the icon set array and set the full path of the source file
  iconset.icons.forEach(function(icon, i, icons) {
    icons[i] = iconset.src+icon+'.svg';
  });
  return gulp.src(iconset.icons)
  .pipe(plugins.rename({ prefix: iconset.prefix }))
  .pipe(plugins.changed(config.dest))
  .pipe(plugins.imagemin())
  .pipe(gulp.dest(config.dest));
});

// Foundation
gulp.task('icons-foundation', function() {
  var iconset = config.foundation;

  // Iterate through the icon set array and set the full path of the source file
  iconset.icons.forEach(function(icon, i, icons) {
    icons[i] = iconset.src+icon+'.svg';
  });
  return gulp.src(iconset.icons)
  .pipe(plugins.rename({ prefix: iconset.prefix }))
  .pipe(plugins.changed(config.dest))
  .pipe(plugins.imagemin())
  .pipe(gulp.dest(config.dest));
});

// Material Design Icons
gulp.task('icons-material', function() {
  var iconset = config.material;

  // Iterate through the icon set array and set the full path of the source file
  iconset.icons.forEach(function(icon, i, icons) {
    icons[i] = iconset.src+icon[0]+'/svg/production/'+icon[1]+'.svg';
  });
  return gulp.src(iconset.icons)
  .pipe(plugins.flatten())
  .pipe(plugins.rename(function(path) { path.basename = iconset.prefix + path.basename.replace(/_/g, '-') })) // Standardize filenames
  .pipe(plugins.changed(config.dest))
  .pipe(plugins.imagemin())
  .pipe(gulp.dest(config.dest));
});

// Ionicons
gulp.task('icons-ion', function() {
  var iconset = config.ionicons;

  // Iterate through the icon set array and set the full path of the source file
  iconset.icons.forEach(function(icon, i, icons) {
    icons[i] = iconset.src+icon+'.svg';
  });
  return gulp.src(iconset.icons)
  .pipe(plugins.rename({ prefix: iconset.prefix }))
  .pipe(plugins.changed(config.dest))
  .pipe(plugins.imagemin())
  .pipe(gulp.dest(config.dest));
});

// Typicons
gulp.task('icons-typ', function() {
  var iconset = config.typicons;

  // Iterate through the icon set array and set the full path of the source file
  iconset.icons.forEach(function(icon, i, icons) {
    icons[i] = iconset.src+icon+'.svg';
  });
  return gulp.src(iconset.icons)
  .pipe(plugins.rename({ prefix: iconset.prefix }))
  .pipe(plugins.changed(config.dest))
  .pipe(plugins.imagemin())
  .pipe(gulp.dest(config.dest));
});

// Custom icons
gulp.task('icons-custom', function() {
  return gulp.src(config.custom.src)
  .pipe(plugins.rename({ prefix: config.custom.prefix }))
  .pipe(plugins.changed(config.dest))
  .pipe(plugins.imagemin())
  .pipe(gulp.dest(config.dest));
});

// Utility function to clean out the icons folder prior to building a new sprite sheet (use this anytime you change icons)
gulp.task('icons-clean', function(cb) {
  del(config.dest, cb)
});

// Support for multiple icon sources; use one task per set to handle different prefixes and source locations
gulp.task('icons', ['icons-awesome', 'icons-foundation', 'icons-ion', 'icons-material', 'icons-typ', 'icons-custom']);
