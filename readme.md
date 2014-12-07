# PENDRELL

Pendrell is a minimal yet fully-featured WordPress theme. It features beautiful, legible typesetting alongside big, bold imagery. Use it for long-form journalism, photo-blogging, and more--but be prepared to dive into the code! Pendrell is something of a hacker theme; there is no options page, theme customizer, or bloat, and you won't find it in the WordPress theme repository. Instead, what you get is a powerful WordPress hacker theme [built with the goodness of Sass, Bower, and Gulp](https://github.com/synapticism/wordpress-gulp-bower-sass).

Pendrell is built with [Ubik](https://github.com/synapticism/ubik), my suite of WordPress components.

![Pendrell example screenshot](/dist/pendrell/screenshot.png "Pendrell example screenshot")

You can see Pendrell in action on my personal blog, [Synapticism](http://synapticism.com).



## FEATURES

* HTML5-compliant markup; clean and efficient CSS3 styling.
* Big, beautiful typesetting for [easy reading](http://ia.net/blog/100e2r/).
* Consistent vertical rhythm (excluding images; that's just too much trouble).
* Improved post format styling and support for asides, images (really just a thin wrapper for attachments), links, quotations, and status updates.
* Smart context-dependent search form.
* Full-width view for images and galleries. Great for photo-blogging.
* Built-in AJAX Page Loader script (click "next" and more content will appear). Not unlike Infinite Scroll but custom-coded for high performance.
* Built-in AJAX contact form page template; no need for a wasteful plugin.
* Truly responsive images with [Ubik Imagery](https://github.com/synapticism/ubik-imagery) and [Picturefill](https://github.com/scottjehl/picturefill).
* Google web font support; configure in `functions.php`.
* Code highlighting with [Prism](http://prismjs.com).
* Automated CSS/JS minification via Gulp build system. This theme is *highly* optimized.
* A zillion little optimizations via [Ubik](https://github.com/synapticism/ubik).
* Highly commented source code to walk you through everything that Pendrell can do.



## INSTALLATION

Clone the repo and run `npm install` then `gulp dist` to build Pendrell for production.

Drop the 'dist/pendrell' directory into `/wp-content/themes/` and activate it via the WordPress admin interface.

*Note: the copy hosted on GitHub lacks many dependencies that you can only get by building the theme from scratch!*

### REQUIREMENTS

To use the theme: WordPress 3.9+ and PHP 5.2. For development and customization: gem, npm, Sass, Bower, and Gulp.

### CONFIGURATION

**This theme has no options page**; modify the `src/functions-config.php` file if you wish to change any of the default settings specified in `src/functions-config-defaults.php`. Refer to the comments and patterns in the source code for more information. In general, most options can be set by using `define( 'PENDRELL_CONSTANT', value );`.

### DEVELOPMENT

I develop Pendrell on a local OS X development environment provisioned with Sass, Bower, and Gulp. (See [this post on my blog](http://synapticism.com/wordpress-theme-development-with-gulp-bower-and-sass/) for more details about this workflow.) Presumably you are working with a similar setup. To get started you'll need to have Sass installed: `gem install sass`. After that, `npm install` should get you up and running. (Bower is automatically called using npm's `scripts.postinstall` feature.)

At this point you can build Pendrell with the `gulp` command. This also starts up a watch process and a [LiveReload](http://livereload.com/) server (for use with the relevant [browser extension](http://feedback.livereload.com/knowledgebase/articles/86242-how-do-i-install-and-use-the-browser-extensions-)).

When making modifications in development be sure to alter files in the `src` folder and *nowhere else* (unless you know what you're doing). Local development can be facilitated by creating a symbolic link from `build` and/or `dist` to your WordPress `wp-content/themes` folder *e.g.* `ln -s ~/dev/work/pendrell/build ~/dev/localhost/wordpress/wp-content/themes/pendrell` (modified to suit your environment, of course).

To create a new production-ready distribution under `pendrell/dist/pendrell` use `gulp dist`. This can also be tested locally using a variation on the symbolic link command above.

Pendrell uses vanilla Sass (no Compass) alongside [Kipple](https://github.com/synapticism/kipple), my zygotic library of Sass hacks, [Normalize.css](https://necolas.github.io/normalize.css/), and [Eric Meyer's reset](http://meyerweb.com/eric/tools/css/reset/).



## PLUGINS

These plugins are recommended for use with Pendrell:

* [My fork](https://github.com/synapticism/wp-post-formats) of Crowd Favorite's [WP-Post-Formats plugin](https://github.com/crowdfavorite/wp-post-formats).
* [JP Markdown](http://wordpress.org/plugins/jetpack-markdown/) to enable Markdown. Works well with [Ubik Markdown](https://github.com/synapticism/ubik-markdown) (included in Pendrell).
* [WordPress SEO](https://wordpress.org/plugins/wordpress-seo/) by Yoast. Plays nice with [Ubik SEO](https://github.com/synapticism/ubik-seo) (included in Pendrell).
* [WP-Super-Cache](http://ocaoimh.ie/wp-super-cache/) or some other caching plugin. *Cache you must!*
* [Akismet](http://akismet.com/).

Utilities:

* [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) since Pendrell uses custom image sizes.
* [Un-attach and re-attach media attachments](http://wordpress.org/plugins/unattach-and-re-attach-attachments/) in case you get mixed up.
* [Post type switcher](http://wordpress.org/extend/post-type-switcher/).



## LICENSE

Copyright 2012-2014 [Alexander Synaptic](http://alexandersynaptic.com). Licensed under the GPLv3: http://www.gnu.org/licenses/gpl.txt

Please link back to [my web site](http://synapticism.com) and/or [this GitHub repository](https://github.com/synapticism/pendrell) if you make use of this theme!
