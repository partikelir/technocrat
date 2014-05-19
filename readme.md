# PENDRELL 0.8

Pendrell is minimal yet full-featured WordPress theme originally based on Twenty Twelve.

![Pendrell example screenshot](/pendrell/screenshot.png "Pendrell example screenshot")

You can see Pendrell in action on my blog, [Synapticism](http://synapticism.com).



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

To build Pendrell after modifications: `gulp`.

Pendrell is written in Sass without Compass. [Normalize.css](https://necolas.github.io/normalize.css/) and [Eric Meyer's reset](http://meyerweb.com/eric/tools/css/reset/) are integrated by default. [Scut](https://davidtheclark.github.io/scut/), a lightweight Sass utility library, is also used.

*Fork this repo to receive updates as development continues.*

### PLUGINS

Pendrell is designed for use with [Ubik](https://github.com/synapticism/ubik), my all-purpose WordPress toolkit. You'll want to install and configure it to get the most out of this theme if you start playing around with it. Apart from that I have prepared a list of recommended plugins in [usage.md](/usage.md).



## FEATURES

* Entry content type set at 18px by default for easier reading.
* Consistent vertical rhythm (excluding images; that's just too much trouble).
* Improved post format styling and support for asides, images (really just a thin wrapper for attachments), links, quotations, and status updates.
* Google Web Font loading via setting in `functions-config.php`.
* Automated CSS/JS minification via Gulp build script.
* Built-in contact form page template; no need for a plugin.
* Syntax highlighting via [Prism](http://prismjs.com).



## USAGE

* Make judicious use of the `<!--more-->` tag.
* See [usage.md](/usage.md) for additional tips.



## LICENSE

Copyright 2012-2014 [Alexander Synaptic](http://alexandersynaptic.com). Licensed under the GPLv3: http://www.gnu.org/licenses/gpl.txt

Please link back to my web site (http://synapticism.com) and/or this GitHub repository (https://github.com/synapticism/pendrell) if you make use of this theme.
