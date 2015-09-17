// ==== CONFIGURATION ==== //

// Project paths
var project     = 'pendrell'
  , src         = './src/'
  , build       = './build/'
  , dist        = './dist/'+project+'/'
  , bower       = './bower_components/'
  , composer    = './vendor/'
  , modules     = './node_modules/'
;

// Project settings
module.exports = {

  bower: {
    normalize: { // Copies `normalize.css` from `bower_components` to `src/scss` and renames it to allow for it to imported as a Sass file
      src: bower+'normalize.css/normalize.css'
    , dest: src+'scss'
    , rename: '_normalize.scss'
    }
  },

  browsersync: {
    files: [build+'/**', '!'+build+'/**.map'] // Exclude map files
  , notify: false // In-line notifications (the blocks of text saying whether you are connected to the BrowserSync server or not)
  , open: false // Set to false if you don't like the browser window opening automatically
  , port: 3000 // Port number for the live version of the site; default: 3000
  , proxy: 'synaptic.dev:8080' // Using a proxy instead of the built-in server as we have server-side rendering to do via WordPress
  , watchOptions: {
      debounceDelay: 2000 // Delay for events called in succession for the same file/event
    }
  },

  // Icons from each set will be copied to the theme folder and combined to make a master icon sheet
  icons: {
    dest: src+'icons/'
  , awesome: {
      src: bower+'font-awesome-svg-png/black/svg/' // Doesn't matter whether you're black or white
    , prefix: 'awe-'
    , icons: [
        'caret-down'
      , 'comment'
      , 'sort'
      ]
    }
  , elusive: {
      src: bower+'elusive-iconfont/dev/icons-svg/'
    , prefix: 'elu-'
    , icons: []
    }
  , foundation: {
      src: bower+'foundation-icon-fonts/svgs/'
    , prefix: '' // All files are already namespaced 'fi-'
    , icons: []
    }
  , ionicons: {
      src: bower+'ionicons/src/'
    , prefix: 'ion-'
    , icons: [
        'image'
      , 'search'
      ]
    }
  , material: {
      src: bower+'material-design-icons/'
    , prefix: 'gmd-'
    , icons: [
        //[ 'action', 'ic_description_48px' ]
      ]
    }
  , open: {
      src: bower+'open-iconic/svg/'
    , prefix: 'open-'
    , icons: []
    }
  , typicons: {
      src: bower+'typicons/src/svg/'
    , prefix: 'typ-'
    , icons: [
        'arrow-left-thick'
      , 'arrow-right-thick'
      , 'arrow-up-thick'
      , 'cancel'
      , 'document-text'
      , 'key'
      , 'location'
      , 'spanner'
      , 'th-list'
      ]
    }
  , custom: {
      src: src+'icons-custom/*.svg'
    , prefix: 'ico-'
    }
  },

  images: {
    build: { // Copies images from `src` to `build`; does not optimize
      src: src+'**/*(*.png|*.jpg|*.jpeg|*.gif)'
    , dest: build
    }
  , dist: {
      src: [dist+'**/*(*.png|*.jpg|*.jpeg|*.gif)', '!'+dist+'screenshot.png', '!'+dist+'icons.*.png'] // No need to compress the PNG fallback
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
    // Bundles are defined by a name and an array of chunks to concatenate; warning: it's up to you to manage dependencies!
    bundles: {
      core: ['core']
    , contact: ['contact']
    , magnific: ['magnific']
    , pf: ['pf', 'core']
    , pf_prism: ['pf', 'prism', 'core']
    , pg8: ['pg8', 'core']
    , pg8_pf: ['pg8', 'pf', 'core']
    , pg8_prism: ['pg8', 'prism', 'core']
    , pg8_pf_prism: ['pg8', 'pf', 'prism', 'core']
    , prism: ['prism', 'core']
    }
    // Chunks are arrays of globs matching source files that combine to provide specific functionality
    // Some chunks are defined on a line by line basis to improve git workflow
  , chunks: {
      core: [
        modules+'svg4everybody/svg4everybody.js'
      , modules+'svg.icon.js/svg.icon.js'
      , modules+'selectric/public/jquery.selectric.js'
      , modules+'timeago/jquery.timeago.js'
      , modules+'autosize/dist/autosize.js'
      , src+'js/responsive-menu.js'
      , src+'js/skip-link-focus-fix.js'
      , src+'js/core.js'
      ]
    , contact: [
        modules+'jquery-validation/dist/jquery.validate.js'
      , src+'js/contact-form.js'
      ]
    , magnific: [
        bower+'parse-srcset/src/parse-srcset.js'
      , src+'js/magnific-popup-1.js' // Must precede core Magnific Popup scripts
      , modules+'magnific-popup/src/js/core.js'
      , modules+'magnific-popup/src/js/image.js'
      , modules+'magnific-popup/src/js/gallery.js'
      , modules+'magnific-popup/src/js/fastclick.js'
      , src+'js/magnific-popup-2.js' // Must follow core Magnific Popup scripts
      ]
    , pf: [
        modules+'picturefill/dist/picturefill.js' // Includes matchMedia in this iteration
      ]
    , pg8: [
        modules+'html5-history-api/history.js'
      , modules+'spin.js/spin.js'
      , modules+'spin.js/jquery.spin.js'
      , modules+'wp-ajax-page-loader/wp-ajax-page-loader.js'
      , src+'js/page-loader.js'
      ]
    , prism: [
        modules+'prismjs/components/prism-core.js'
      , modules+'prismjs/components/prism-markup.js'
      , modules+'prismjs/components/prism-css.js'
      , modules+'prismjs/components/prism-css-extras.js'
      , modules+'prismjs/components/prism-clike.js'
      , modules+'prismjs/components/prism-javascript.js'
      , modules+'prismjs/components/prism-scss.js'
      , modules+'prismjs/components/prism-php.js'
      , modules+'prismjs/components/prism-php-extras.js'
      ]
    }
  , dest: build+'js/' // Where the scripts end up
  , lint: {
      src: [src+'js/**/*.js'] // Lint core scripts (for everything else we're relying on the original authors)
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
      src: src+'scss/**/*.scss'
    , dest: build
    }
  , dist: {
      src: build+'**/*.css'
    , dest: dist
    }
  , compiler: 'ruby-sass' // 'ruby-sass' or 'libsass'
  , autoprefixer: { browsers: ['> 3%', 'last 2 versions', 'ie 9', 'ios 6', 'android 4'] }
  , minify: { keepSpecialComments: 1, roundingPrecision: 5 }
  , rubySass: { // Don't forget to run `gem install sass`; Compass is not included by default
      loadPath: ['src/scss', bower] // Adds the `bower_components` directory to the load path so you can @import directly
    , precision: 8
    , sourcemap: true
  }
  , libsass: { // For future reference: settings for Libsass, a promising project that hasn't reached feature parity with Ruby Sass just yet
      includePaths: [bower]
    , precision: 8
    }
  },

  svg: {
    src: src+'icons/**/*.svg'
  , dest: build+'img/'
  , transform: {
      before: {
        run: function ($) {
          // Remove various attributes to allow for greater control via CSS
          $('[fill]').removeAttr('fill');
          $('[fill-rule]').removeAttr('fill-rule');
          $('[clip-rule]').removeAttr('clip-rule');
        },
        parserOptions: { xmlMode: true }
      }
    }
  },

  theme: {
    lang: {
      src: src+'languages/**/*' // Glob matching any language files you'd like to copy over
    , dest: build+'languages/'
    }
  , php: {
      src: src+'**/*.php'
    , dest: build
    }
  , ubik: {
      src: composer+'synapticism/' // Root folder for all Ubik components
    , dest: build+'modules' // Location in the `build` folder Ubik components should be copied to
      // The following Ubik components will be copied into the theme's build folder
      // Items marked * are required for the theme to function
      // Each item is on its own line to allow for a better git workflow (e.g. for different branches it is easy to comment out unnecessary components)
    , components: [
        'admin'
      , 'analytics'
      , 'cleaner' // *
      , 'colophon' // *
      , 'comments'
      , 'excerpt' // *
      , 'excluder'
      , 'feed'
      , 'fonts' // *
      , 'imagery' // *
      , 'lingual'
      , 'links'
      , 'markdown'
      , 'meta' // *
      , 'places'
      , 'photo-meta'
      , 'post-formats'
      , 'quick-terms'
      , 'recordpress'
      , 'related'
      , 'search' // *
      , 'series'
      , 'seo'
      , 'svg-icons' // *
      , 'terms' // *
      , 'text' // *
      , 'time' // *
      , 'title' // *
      , 'views' // *
      ]
    , ignore: ['!'+bower+'ubik*/**/*.json', '!'+bower+'ubik*/**/readme.*'] // Glob(s) matching files to ignore when copying from Bower
    }
  },

  utils: {
    clean: [build+'**/.DS_Store'] // A glob matching junk files to clean out of `build`
  , wipe: [dist] // Clear things out before packaging; @TODO: also clear out the `build` folder
  , icons: src+'icons/'
  , dist: {
      src: build+'**/*'
    , dest: dist
    }
  },

  watch: { // What to watch before triggering each specified task
    src: {
      styles:       src+'scss/**/*.scss'
    , scripts:      src+'js/**/*.js'
    , images:       src+'**/*(*.png|*.jpg|*.jpeg|*.gif)'
    , theme:        src+'**/*.php'
    , livereload:   build+'**/*'
    }
  , watcher: 'livereload' // Who watches the watcher? Easily switch between BrowserSync ('browsersync') and Livereload ('livereload')
  }
}
