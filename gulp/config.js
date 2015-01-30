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
    normalize: { // Copies `normalize.css` from `bower_components` to `src/scss` and renames it to allow for it to imported as a Sass file
      src: bower+'normalize.css/normalize.css'
    , dest: src+'scss/lib'
    , rename: '_normalize.scss'
    }
  , iconsets: { // Icons from each set will be copied to the theme folder and combined to make a master icon sheet
      ionicons: {
        src: bower+'ionicons/src/'
      , dest: src+'svg/'
      , prefix: 'ion-'
      , icons: [
          'search'
        ]
      }
    , typicons: {
        src: bower+'typicons.font/src/svg/'
      , dest: src+'svg/'
      , prefix: 'typ-'
      , icons: [
          'edit'
        //, 'link'
        //, 'location'
        //, 'rss'
        //, 'media-play'
        //, 'media-pause'
        //, 'media-fast-forward'
        //, 'media-rewind'
        //, 'media-stop'
        //, 'volume'
        //, 'volume-down'
        //, 'volume-mute'
        //, 'volume-up'
        ]
      }
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
    bundles: { // Bundles are defined by a name and an array of chunks to concatenate; warning: it's up to you to manage dependencies!
      core: ['icons', 'core']
    , contact: ['contact']
    , pf: ['pf', 'icons', 'core']
    , pf_prism: ['pf', 'prism', 'icons', 'core']
    , pg8: ['pg8', 'icons', 'core']
    , pg8_pf: ['pg8', 'pf', 'icons', 'core']
    , pg8_prism: ['pg8', 'prism', 'icons', 'core']
    , pg8_pf_prism: ['pg8', 'pf', 'prism', 'icons', 'core']
    , prism: ['prism', 'icons', 'core']
    }
  , chunks: { // Chunks are arrays of globs matching source files that combine to provide specific functionality
      core: [src+'js/navigation.js', src+'js/skip-link-focus-fix.js', src+'js/core.js']
    , contact: [bower+'jquery-validation/dist/jquery.validate.js', src+'js/contact-form.js']
    , icons: [bower+'svg4everybody/svg4everybody.js', src+'js/iconize.js']
    , pf: [bower+'picturefill/dist/picturefill.js']
    , pg8: [bower+'html5-history-api/history.iegte8.js', bower+'spin.js/spin.js', bower+'spin.js/jquery.spin.js', bower+'wp-ajax-page-loader/wp-ajax-page-loader.js', src+'js/page-loader.js']
    , prism: [ // Prism components are on their own line to make it easy to define a custom build to suit whatever code you use on your blog
        bower+'prism/components/prism-core.js'
      , bower+'prism/components/prism-markup.js'
      , bower+'prism/components/prism-css.js'
      , bower+'prism/components/prism-css-extras.js'
      , bower+'prism/components/prism-clike.js'
      , bower+'prism/components/prism-javascript.js'
      , bower+'prism/components/prism-scss.js'
      , bower+'prism/components/prism-php.js'
      , bower+'prism/components/prism-php-extras.js'
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
  , sass: { // For future reference: settings for Libsass, a promising project that hasn't reached feature parity with Ruby Sass just yet
      includePaths: [bower]
    , precision: 8
    }
  },

  svg: {
    svg2png: { // PNG fallback generation; for use with SVG For Everybody; only needed if you support IE 8 and below
      src: src+'svg/**/*.svg'
    , dest: build+'img/'
    }
  , svgstore: { // Reference: https://github.com/w0rm/gulp-svgstore
      src: src+'svg/**/*.svg'
    , dest: build+'img/'
    , options: {
        fileName: 'icons.svg'
      //, inlineSvg: true // Uncomment if you are injecting the SVG file into the document
      , prefix: 'i-'
      }
    }
  , transform: function ($){
      // Transform the SVG file here; e.g. `$('svg').attr('style', 'display:none');`
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
      src: bower // Root folder for all Ubik components
    , dest: build+'modules' // Location in the `build` folder Ubik components should be copied to
      // The following Ubik components will be copied into the theme's build folder
      // Items marked * are required for the theme to function
      // Each item is on its own line to allow for a better git workflow (e.g. for different branches it is easy to comment out unnecessary components)
    , components: [
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
      , 'links'
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
    , ignore: ['!'+bower+'ubik*/**/*.json', '!'+bower+'ubik*/**/readme.*'] // Glob(s) matching files to ignore when copying from Bower
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
  , watcher: 'livereload' // Who watches the watcher? Easily switch between BrowserSync ('browsersync') and Livereload ('livereload')
  }
}