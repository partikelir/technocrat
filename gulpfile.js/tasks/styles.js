// ==== STYLES ==== //

var gulp          = require('gulp')
  , gutil         = require('gulp-util')
  , plugins       = require('gulp-load-plugins')({ camelize: true })
  , config        = require('../../gulpconfig').styles
  , autoprefixer  = require('autoprefixer')
  , pleaseFilters = require('pleeease-filters')
  , zindex        = require('postcss-zindex')
  , processors    = [autoprefixer(config.autoprefixer), pleaseFilters, zindex]
  //, Eyeglass      = require("eyeglass").Eyeglass
  //, eyeglass      = new Eyeglass(config.libsass)
;

// Build stylesheets from source SCSS files with the Ruby version of Sass
gulp.task('styles-rubysass', function() {
  return plugins.rubySass(config.build.src, config.rubySass)
  .on('error', gutil.log) // Log errors instead of killing the process
  .pipe(plugins.postcss(processors))
  .pipe(plugins.cssnano(config.minify))
  .pipe(plugins.sourcemaps.write('./')) // No need to init; this is set in the configuration
  .pipe(gulp.dest(config.build.dest));
});

// Build stylesheets from source SCSS files with libsass
gulp.task('styles-libsass', function() {
  return gulp.src(config.build.src)
  .pipe(plugins.sourcemaps.init())
  //.pipe(plugins.sass(eyeglass.sassOptions()).on("error", plugins.sass.logError))
  .pipe(plugins.sass(config.libsass))
  .pipe(plugins.postcss(processors))
  .pipe(plugins.cssnano(config.minify))
  .pipe(plugins.sourcemaps.write('./'))
  .pipe(gulp.dest(config.build.dest)); // Drops the unminified CSS file into the `build` folder
});

// Easily configure the Sass compiler from `/gulpconfig.js`
gulp.task('styles', ['styles-'+config.compiler]);
