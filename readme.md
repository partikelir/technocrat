# PENDRELL 0.7

Pendrell is minimalist yet full-featured WordPress theme for personal blogs. It began as a fork of Twenty Twelve (formerly a child theme of the same).

![Pendrell example screenshot](/pendrell/screenshot.png "Pendrell example screenshot")

You can see Pendrell in action on my blog, [Synapticism](http://synapticism.com).



## INSTALLATION

Drop the 'pendrell/pendrell' directory into /wp-content/themes/ and activate it via the WordPress admin interface.

### CONFIGURATION

This theme has no options page; modify the `functions-config-sample.php` file, renaming it to `functions-config.php`, if you wish to change any of the default settings. Pendrell's settings are meant to be self-explanatory; read the comments for more direction.

### DEVELOPMENT

Two commands to get started hacking Pendrell:

```
npm install
gem install sass
```

To build Pendrell after modifications: `gulp`

### PLUGINS

The following plugins are not exactly dependencies but you will probably want to install them to get the most out of Pendrell:

* [Ubik](https://github.com/synapticism/ubik), my all-purpose WordPress toolkit, designed for use with this very theme
* [My fork](https://github.com/synapticism/wp-post-formats) of Crowd Favorite's [WP-Post-Formats plugin](https://github.com/crowdfavorite/wp-post-formats)
* [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) since this theme uses custom image sizes
* [Jetpack](https://github.com/Automattic/jetpack), especially for its Markdown module



## FEATURES

* Entry content type set at 18px by default for easier reading
* Consistent vertical rhythm (exclude images; that's just too much trouble)
* Improved post format styling and support for asides, images, links, quotations, and status updates
* Google Web Font loading via setting in `functions-config.php`
* CSS/JS minification via Gulp build script
* Built-in contact form page template; no need for a plugin
* jQuery-based search query markup; surrounds search terms with `<mark>`
* Syntax highlighting via [Prism](http://prismjs.com)
* Clean search rewrites (e.g. website.com/search/query/ instead of website.com?s=query)
* Context-dependent posts per page (12 items per screen on portfolios, 25 on search, user default elsewhere)



## USAGE

* Make judicious use of the `<!--more-->` tag.



## LICENSE

GNU General Public License: http://www.gnu.org/licenses/gpl.html
