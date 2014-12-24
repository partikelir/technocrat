// ==== CONFIGURATION ==== //

// Paths
var project     = 'pendrell'
  , build       = './build/'
  , dist        = './dist/'+project+'/'
  , source      = './src/' // 'source' instead of 'src' to avoid confusion with gulp.src
  , lang        = 'languages/'
  , bower       = './bower_components/'
;

// Project configuration
module.exports = {
  paths: { // @TODO: remove
    build: build
  , dist: dist
  , source: source
  , lang: lang
  , bower: bower
  },

  bower: {
    normalize: { // Copies normalize from `bower_components` to `src/scss` and renames it
      src: bower+'normalize.css/normalize.css'
    , rename: '_base_normalize.scss'
    , dest: source+'scss'
    }
  },

  browsersync: {
    server: {
      baseDir: [build, source] // We're serving the src folder as well for Sass sourcemap linking
    },
    files: [
      build + "/**",
      "!" + build + "/**.map" // Exclude map files
    ]
  },

  images: {
    build: { // Copies images from `src` to `build`; does not optimize
      src: source+'**/*(*.png|*.jpg|*.jpeg|*.gif)'
    , dest: build
    }
  , dist: {
      src: [dist+'**/*.png', dist+'**/*.jpg', dist+'**/*.jpeg', dist+'**/*.gif', '!'+dist+'screenshot.png']
    , imagemin: {
        optimizationLevel: 7
      , progressive: true
      , interlaced: true
      }
    , dest: dist
    }
  },

  livereload: {
    port: 35729
  },

  scripts: {
    chunks: { // Chunks are arrays of globs matching source files that provide specific functionality
      core: [source+'js/navigation.js', source+'js/core.js']
    , contact: [bower+'jquery-validation/dist/jquery.validate.js', source+'js/contact-form.js']
    , html5shiv: [bower+'html5shiv/dist/html5shiv.js']
    , pf: [bower+'picturefill/dist/picturefill.js']
    , pg8: [bower+'html5-history-api/history.iegte8.js', bower+'spin.js/spin.js', bower+'spin.js/jquery.spin.js', source+'js/page-loader.js']
    , prism: [source+'js/prism.js']
    , xn8: [bower+'html5-history-api/history.iegte8.js', bower+'spin.js/spin.js', bower+'spin.js/jquery.spin.js', bower+'ajaxinate/src/ajaxinate.js', bower+'ajaxinate-wp/src/ajaxinate-wp.js', source+'js/ajaxinate.js']
    },
    bundles: { // Bundles are defined by a name and an array of chunks to concatenate; warning: it's up to you to manage dependencies!
      core: ['core']
    , contact: ['contact']
    , html5shiv: ['html5shiv']
    , pf: ['pf', 'core']
    , pf_prism: ['pf', 'prism', 'core']
    , pg8: ['pg8', 'core']
    , pg8_pf: ['pg8', 'pf', 'core']
    , pg8_prism: ['pg8', 'prism', 'core']
    , pg8_pf_prism: ['pg8', 'pf', 'prism', 'core']
    , prism: ['prism', 'core']
    , xn8: ['xn8', 'core']
    , xn8_prism: ['xn8', 'prism', 'core']
    }
  , dest: build+'js/' // Where the scripts end up
  , lint: {
      src: [source+'js/**/*.js', '!'+source+'js/prism.js'] // Only lint theme-specific scripts (but not our custom build of Prism); for everything else you're on your own
    }
  , minify: {
      src: [build+'js/**/*.js', '!'+build+'js/**/*.min.js'] // Avoid recursive min.min.min.js
    , rename: { suffix: '.min' }
    , uglify: {}
    , dest: build+'js/'
    }
  , namespace: 'p-' // Script filenames will be prefaced with this
  },

  styles: {
    build: {
      src: [source+'scss/*.scss', '!'+source+'scss/_*.scss'] // Ignore partials
    , dest: build
    , sass: { // Don't forget to run `gem install sass`; Compass is not included by default
        loadPath: bower // Adds the `bower_components` directory to the load path so you can @import directly
      , precision: 8
      , 'sourcemap=none': true // Not yet ready for prime time! Sass 3.4 has sourcemaps on by default but this causes some problems from the Gulp toolchain
    }
    , autoprefixer: { browsers: ['last 2 versions', 'ie 9', 'ios 6', 'android 4'] }
    , rename: { suffix: '.min' }
    , minify: { keepSpecialComments: 1 }
    }
  , dist: {
      src: [dist+'**/*.css', '!'+dist+'**/*.min.css']
    , minify: { keepSpecialComments: 1 }
    , dest: dist
    }
  },

  svg: {
    // Coming soon
  },

  theme: {
    lang: {
      src: source+lang+'**/*'
    , dest: build+lang
    }
  , php: {
      src: source+'**/*.php'
    , dest: build
    }
  },

  ubik: {
    // The following Ubik components will be copied into the theme's build folder
    // Items marked * are required for the theme to function
    // Each item is on its own line to allow for a better git workflow
    components: [
      'admin'
    , 'analytics'
    , 'cleaner'
    , 'comments'
    , 'excerpt' // *
    , 'excluder'
    , 'favicons'
    , 'feed'
    , 'imagery' // *
    , 'lingual'
    , 'markdown'
    , 'meta' // *
    , 'places'
    , 'post-formats'
    , 'quick-terms'
    , 'recordpress'
    , 'related'
    , 'search' // *
    , 'series'
    , 'seo'
    , 'terms' // *
    , 'time' // *
    , 'title' // *
    ]
  , dest: build+'modules' // Ubik components end up here
  , ignore: ['!'+bower+'ubik*/**/*.json', '!'+bower+'ubik*/**/readme.*'] // Glob(s) matching files to ignore when copying from Bower
  , path: bower // Original location of Ubik components to copy
  },

  utils: {
    clean: [build+'**/.DS_Store'] // A glob matching junk files to clean out of `build`
  , wipe: [dist] // Clear things out before packaging; @TODO: also clear out the `build` folder
  , dist: {
      src: [build+'**/*', '!'+build+'**/*.min.css']
    , dest: dist
    }
  },

  watch: { // What to watch before triggering each specified task
    styles:       source+'scss/**/*.scss'
  , scripts:      [source+'js/**/*.js', bower+'**/*.js']
  , images:       source+'**/*(*.png|*.jpg|*.jpeg|*.gif)'
  , theme:        source+'**/*.php'
  , livereload:   [build+'**/*']
  }
}
