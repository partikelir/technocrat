# PENDRELL

Pendrell is a minimal yet fully-featured WordPress theme for single author personal blogs. It is designed to excel at displaying beautiful, legible type alongside big, bold imagery. Use it for long-form journalism, photo-blogging, and more--but be prepared to dive into the code! Pendrell is something of a hacker theme; there is no options page, theme customizer, or bloat, which means you won't find it in the WordPress theme repository. Instead, what you get is a powerful WordPress hacker theme [built with the goodness of Sass, Bower, and Gulp](https://github.com/synapticism/wordpress-gulp-bower-sass).

Pendrell requires a companion plugin, [Ubik](https://github.com/synapticism/ubik), to maximize the awesomeness. It'll run without Ubik but a lot of goodness will be missing.

![Pendrell example screenshot](/dist/pendrell/screenshot.png "Pendrell example screenshot")

You can see Pendrell in action on my personal blog, [Synapticism](http://synapticism.com).



## FEATURES

* HTML5-compliant markup; clean and efficient CSS3 styling.
* Big, beautiful typesetting for [easy reading](http://ia.net/blog/100e2r/).
* Consistent vertical rhythm (excluding images; that's just too much trouble).
* Full-width view for image and gallery post format posts increases font size and removes sidebar. Great for photo-blogging.
* Improved post format styling and support for asides, images (really just a thin wrapper for attachments), links, quotations, and status updates.
* Built-in contact form page template; no need for a wasteful plugin.
* Automated CSS/JS minification via Gulp build system. This theme is *highly* optimized.
* Google web font support; configure in `functions.php`.
* Code highlighting via [Prism](http://prismjs.com).
* Optional AJAX page loading with [Ajaxinate](https://github.com/synapticism/ajaxinate) and [Ajaxinate WP](https://github.com/synapticism/ajaxinate-wp).
* Smart context-dependent search form.
* Much, much more...



## INSTALLATION

Drop the 'dist/pendrell' directory into `/wp-content/themes/` and activate it via the WordPress admin interface.

### REQUIREMENTS

WordPress 3.9+ and PHP 5.3+ to run the theme and [Ubik](https://github.com/synapticism/ubik). For development and customization: gem, npm, Sass, Bower, and Gulp.

### CONFIGURATION

This theme has no options page; modify the `functions-config-sample.php` file, renaming it to `functions-config.php`, if you wish to change any of the default settings. Pendrell's settings are meant to be self-explanatory; read the comments for more directions.

### DEVELOPMENT

I develop Pendrell on a local OS X development environment provisioned with Sass, Bower, and Gulp. (See [this post on my blog](http://synapticism.com/wordpress-theme-development-with-gulp-bower-and-sass/) for more details about this workflow.) Presumably you are working with a similar setup. To get started you'll need to have Sass installed: `gem install sass`. After that, `npm install` should get you up and running. (Bower is automatically called using npm's `scripts.postinstall` feature.)

At this point you can build Pendrell with the `gulp` command. This also starts up a watch process and a [LiveReload](http://livereload.com/) server (for use with the relevant [browser extension](http://feedback.livereload.com/knowledgebase/articles/86242-how-do-i-install-and-use-the-browser-extensions-)).

When making modifications in development be sure to alter files in the `src` folder and *nowhere else* (unless you know what you're doing). Local development can be facilitated by creating a symbolic link from `build` and/or `dist` to your WordPress `wp-content/themes` folder *e.g.* `ln -s ~/dev/work/pendrell/build ~/dev/localhost/wordpress/wp-content/themes/pendrell` (modified to suit your environment, of course).

To create a new production-ready distribution under `pendrell/dist/pendrell` use `gulp dist`. This can also be tested locally using a variation on the symbolic link command above.

Pendrell uses vanilla Sass (no Compass) alongside [Kipple](https://github.com/synapticism/kipple), my zygotic library of Sass hacks, [Normalize.css](https://necolas.github.io/normalize.css/), and [Eric Meyer's reset](http://meyerweb.com/eric/tools/css/reset/).

### PLUGINS

Pendrell is designed for use with [Ubik](https://github.com/synapticism/ubik), my all-purpose WordPress plugin toolkit. It includes all the theme-agnostic snippets, hacks, and other functionality that would usually be included in a theme (but shouldn't be). You will want to install and configure Ubik to get the most out of Pendrell.

I have also been experimenting with selective page loading using [Ajaxinate](https://github.com/synapticism/ajaxinate). This functionality is disabled by default. There's no need to install anything extra, however; just flip the switch in `functions-config.php` and configured `/src/js/ajaxinate.js` to suit your modifications.

Apart from that I have prepared a list of recommended plugins and usage patterns in [usage.md](/usage.md).



## LICENSE

Copyright 2012-2014 [Alexander Synaptic](http://alexandersynaptic.com). Licensed under the GPLv3: http://www.gnu.org/licenses/gpl.txt

Please link back to [my web site](http://synapticism.com) and/or [this GitHub repository](https://github.com/synapticism/pendrell) if you make use of this theme!
