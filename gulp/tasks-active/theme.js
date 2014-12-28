// ==== THEME ==== //

// These tasks are specific to WordPress themes

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../config').theme
;

// Copy PHP source files to the `build` folder
gulp.task('theme-php', ['theme-ubik'], function() {
  return gulp.src(config.php.src)
  .pipe(plugins.changed(config.php.dest))
  .pipe(gulp.dest(config.php.dest));
});

// Copy everything under `src/languages` indiscriminately
gulp.task('theme-lang', function() {
  return gulp.src(config.lang.src)
  .pipe(plugins.changed(config.lang.dest))
  .pipe(gulp.dest(config.lang.dest));
});

// Copy Ubik components into the build directory
gulp.task('theme-ubik', function() {

  // Iterate through the Ubik array and wrap each component in a glob pattern to handle the nested directory structure
  config.ubik.components.forEach(function(component, i, components) {
    components[i] = config.ubik.src+'ubik-'+component+'/**/*';
  });

  // Combine the list of components with the ignore glob; this allows us to copy only the files we need without anything extra from the original GitHub repos
  config.ubik.components = config.ubik.components.concat(config.ubik.src+'ubik/**/*', config.ubik.ignore);

  // Let's go!
  return gulp.src(config.ubik.components)
  .pipe(plugins.changed(config.ubik.dest))
  .pipe(gulp.dest(config.ubik.dest));
});

// All the theme tasks in one
gulp.task('theme', ['theme-lang', 'theme-php']);
