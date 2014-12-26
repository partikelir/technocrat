// ==== CONFIGURATION ==== //

// Project paths
var project     = 'pendrell'
  , src         = './src/'
  , build       = './build/'
  , dist        = './dist/'+project+'/'
  , bower       = './bower_components/'
;

// Project settings
module.exports = {

  bower: {
    normalize: { // Copies normalize from `bower_components` to `src/scss` and renames it
      src: bower+'normalize.css/normalize.css'
    , rename: '_normalize.scss'
    , dest: src+'scss/lib'
    }
  },

  browsersync: {
    files: [build+'/**', '!'+build+'/**.map'] // Exclude map files
  , notify: false // In-line notifications (the blocks of text saying whether you are connected to the BrowserSync server or not)
  , open: true // Set to false if you don't like the browser window opening automatically
  , port: 3000 // Port number for the live version of the site; default: 3000
  , proxy: 'synaptic.dev:8080' // Using a proxy instead of the built-in server as we have server-side rendering to do via WordPress
  , watchOptions: {
      debounceDelay: 2000 // Delay for events called in succession for the same file/event
    }
  },

  images: {
    build: { // Copies images from `src` to `build`; does not optimize
      src: src+'**/*(*.png|*.jpg|*.jpeg|*.gif)'
    , dest: build
    }
  , dist: {
      src: [dist+'**/*.png', dist+'**/*.jpg', dist+'**/*.jpeg', dist+'**/*.gif', '!'+dist+'screenshot.png', '!'+dist+'svg/*.png'] // No need to compress the PNG fallback
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
    chunks: { // Chunks are arrays of globs matching src files that provide specific functionality
      core: [src+'js/navigation.js', src+'js/core.js']
    , contact: [bower+'jquery-validation/dist/jquery.validate.js', src+'js/contact-form.js']
    , html5shiv: [bower+'html5shiv/dist/html5shiv.js']
    , pf: [bower+'picturefill/dist/picturefill.js']
    , pg8: [bower+'html5-history-api/history.iegte8.js', bower+'spin.js/spin.js', bower+'spin.js/jquery.spin.js', src+'js/page-loader.js']
    , prism: [src+'js/prism.js']
    , xn8: [bower+'html5-history-api/history.iegte8.js', bower+'spin.js/spin.js', bower+'spin.js/jquery.spin.js', bower+'ajaxinate/src/ajaxinate.js', bower+'ajaxinate-wp/src/ajaxinate-wp.js', src+'js/ajaxinate.js']
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
      src: [src+'js/**/*.js', '!'+src+'js/prism.js'] // Only lint theme-specific scripts (but not our custom build of Prism); for everything else you're on your own
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
      src: [src+'scss/*.scss', '!'+src+'scss/_*.scss'] // Ignore partials
    , dest: build
    }
  , dist: {
      src: [dist+'**/*.css', '!'+dist+'**/*.min.css']
    , minify: { keepSpecialComments: 1 }
    , dest: dist
    }
  , autoprefixer: { browsers: ['> 3%', 'last 2 versions', 'ie 9', 'ios 6', 'android 4'] }
    , rename: { suffix: '.min' }
    , minify: { keepSpecialComments: 1 }
    , rubySass: { // Don't forget to run `gem install sass`; Compass is not included by default
        loadPath: bower // Adds the `bower_components` directory to the load path so you can @import directly
      , precision: 8
      , 'sourcemap=none': true // Not yet ready for prime time! Sass 3.4 has srcmaps on by default but this causes some problems from the Gulp toolchain
    }
    , sass: {
        includePaths: [bower]
      , precision: 8
    }
  },

  svg: {
    images: {
      src: build+'svg/**/*.svg'
    , dest: build+'svg/'
    }
  , sprites: {
      src: src+'svg/**/*.svg'
    , dest: build+'svg/' // Processed sprites end up here
    , options: {
        cssFile: '../../src/scss/lib/_svg_sprites_map.scss' // Relative path from svg.sprites.dest is required here
      , layout: 'diagonal'
      //, padding: 5
      , preview: false
      , selector: '%f' // CSS selector to create; %f = filename
      , svg: {
          sprite: 'p-sprite.svg' // Filename for the sprite
        }
      , templates: {
          css: require('fs').readFileSync(src+'scss/templates/_svg_sprites_map.scss', 'utf-8') // Relative to sprites.src
        }
      , transformData: function(data, config) { // Relative path to the sprite files from the stylesheet
          data.pngPath = 'svg/p-sprite.svg';
          data.svgPath = 'svg/p-sprite.png';
          data.padding = 5; // Needs to be set here when transforming data
          return data;
        },
      }
    }
  },

  theme: {
    lang: {
      src: src+'languages/**/*'
    , dest: build+'languages/'
    }
  , php: {
      src: src+'**/*.php'
    , dest: build
    }
  , ubik: {
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
    , path: bower // Path to the original copy of all Ubik components
    }
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
    src: {
      styles:       src+'scss/**/*.scss'
    , scripts:      [src+'js/**/*.js', bower+'**/*.js']
    , images:       src+'**/*(*.png|*.jpg|*.jpeg|*.gif)'
    , theme:        src+'**/*.php'
    , livereload:   [build+'**/*']
    }
  , watcher: 'browsersync' // Who watches the watcher? Easily switch between BrowserSync and Livereload (use 'livereload')
  }
}
