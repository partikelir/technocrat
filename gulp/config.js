// ==== CONFIGURATION ==== //

// Paths
var project     = 'pendrell'
  , build       = './build/'
  , dist        = './dist/'+project+'/'
  , source      = './src/' // 'source' instead of 'src' to avoid confusion with gulp.src
  , lang        = 'languages/'
  , bower       = './bower_components/'
;

// Ubik components (array); ubikPlugins specifies optional theme-specific components; ubikCore is required for this theme to function
var ubikPlugins = ['admin', 'analytics', 'cleaner', 'comments', 'excluder', 'feed', 'lingual', 'markdown', 'places', 'post-formats', 'quick-terms', 'recordpress', 'related', 'series', 'seo']
  , ubikCore    = ['excerpt', 'favicons', 'imagery', 'meta', 'search', 'terms', 'time', 'title']
  , ubik        = ubikCore.concat(ubikPlugins)
;

// Project configuration
module.exports = {
  paths: {
    build: build
  , dist: dist
  , source: source
  , lang: lang
  , bower: bower
  },
  bower: {
    normalize: {
      src: bower+'normalize.css/normalize.css'
    , name: '_base_normalize.scss'
    , dest: source+'scss'
    }
  },
  browsersync: {
    server: {
      baseDir: [build, source] // We're serving the src folder as well for sass sourcemap linking
    },
    files: [
      build + "/**",
      "!" + build + "/**.map" // Exclude Map files
    ]
  },
  images: {
    src: source+'**/*(*.png|*.jpg|*.jpeg|*.gif)'
  , dest: build
  },
  ubik: {
    // The following Ubik components will be copied into the theme's build folder
    plugins: [
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
  }
}

/*
module.exports = {

  sass: {
    src: src + "/sass/*.{sass,scss}",
    dest: dest,
    settings: {
      // Required if you want to use SASS syntax
      // See https://github.com/dlmanning/gulp-sass/issues/81
      sourceComments: 'map',
      imagePath: '/images' // Used by the image-url helper
    }
  },
  images: {
    src: src + "/images/**",
    dest: dest + "/images"
  },
  markup: {
    src: src + "/htdocs/**",
    dest: dest
  },
  browserify: {
    // Enable source maps
    debug: true,
    // Additional file extentions to make optional
    extensions: ['.coffee', '.hbs'],
    // A separate bundle will be generated for each
    // bundle config in the list below
    bundleConfigs: [{
      entries: src + '/javascript/app.coffee',
      dest: dest,
      outputName: 'app.js'
    }, {
      entries: src + '/javascript/head.coffee',
      dest: dest,
      outputName: 'head.js'
    }]
  }
};*/
