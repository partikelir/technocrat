# PENDRELL

Pendrell is minimal yet full-featured WordPress theme originally based on Twenty Twelve.

![Pendrell example screenshot](/pendrell/screenshot.png "Pendrell example screenshot")

You can see Pendrell in action on my blog, [Synapticism](http://synapticism.com).



## FEATURES

* HTML5-compliant markup; clean and efficient CSS3 stylesheet.
* Big, beautiful typesetting for easier reading.
* Consistent vertical rhythm (excluding images; that's just too much trouble).
* Full-width view for image and gallery post format posts increases font size and removes sidebar. Great for photo-blogging.
* Improved post format styling and support for asides, images (really just a thin wrapper for attachments), links, quotations, and status updates.
* Google web font support; configure in `functions.php`.
* Built-in contact form page template; no need for a wasteful plugin.
* Automated CSS/JS minification via Gulp build script.
* Code highlighting via [Prism](http://prismjs.com).
* Smart context-dependent search form.
* Optional author information box.
* Much, much more;



## INSTALLATION

Drop the 'pendrell/pendrell' directory into /wp-content/themes/ and activate it via the WordPress admin interface.

### CONFIGURATION

This theme has no options page; modify the `functions-config-sample.php` file, renaming it to `functions-config.php`, if you wish to change any of the default settings. Pendrell's settings are meant to be self-explanatory; read the comments for more direction.

### DEVELOPMENT

I develop Pendrell on a local OS X development environment provisioned with npm, gulp, gem, and bower. If you know your way around those tools here is how to get started:

```
npm install
bower install
gem install sass
```

To build Pendrell after modification: `gulp`.

Pendrell is written in Sass without Compass. [Normalize.css](https://necolas.github.io/normalize.css/) and [Eric Meyer's reset](http://meyerweb.com/eric/tools/css/reset/) are integrated by default.

*Fork this repo to receive updates as development continues.*

### PLUGINS

Pendrell is designed for use with [Ubik](https://github.com/synapticism/ubik), my all-purpose WordPress plugin toolkit. It includes all the theme-agnostic snippets, hacks, and other functionality that would usually be included in a theme (but shouldn't be). You will want to install and configure Ubik to get the most out of Pendrell.

Apart from that I have prepared a list of recommended plugins in [usage.md](/usage.md).



## LICENSE

Copyright 2012-2014 [Alexander Synaptic](http://alexandersynaptic.com). Licensed under the GPLv3: http://www.gnu.org/licenses/gpl.txt

Please link back to [my web site](http://synapticism.com) and/or [this GitHub repository](https://github.com/synapticism/pendrell) if you make use of this theme!
