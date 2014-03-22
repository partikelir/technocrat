# PENDRELL 0.6

Pendrell is minimalist WordPress theme for personal blogs. It began as a fork of Twenty Twelve (formerly a child theme of the same). My original intent was to tweak Twenty Twelve to allow for two types of content to co-exist in harmony:

* Blog entries set with beautiful, legible type
* Portfolio items featuring big, bold full-width images

Many WordPress themes provide for one or the other but few handle both types of content with elegance, flexibility, and simplicity.

You can see Pendrell in action on my blog: http://synapticism.com



## INSTALLATION

Drop the 'pendrell/pendrell' directory into /wp-content/themes/ and activate it via the WordPress admin interface.


### CONFIGURATION

This theme has no options page; modify the `functions-config-sample.php` file, renaming it to `functions-config.php`, if you wish to change any of the default settings. Pendrell's settings are meant to be self-explanatory; read the comments for more direction.


### DEVELOPMENT

Two commands to get started hacking Pendrell:

`npm install`
`gem install sass`

To build Pendrell after modifications:

`gulp`


### PLUGINS

The following plugins are not exactly dependencies but you will probably want to install them to get the most out of Pendrell:

* [Ubik](https://github.com/synapticism/ubik), my all-purpose WordPress toolkit, designed for use with this very theme
* [My fork](https://github.com/synapticism/wp-post-formats) of Crowd Favorite's [WP-Post-Formats plugin](https://github.com/crowdfavorite/wp-post-formats)
* [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) since this theme uses custom image sizes
* [Jetpack](https://github.com/Automattic/jetpack), especially for its Markdown module



## FEATURES

### GENERAL ENHANCEMENTS

* Entry content type set at 18px by default for easier reading
* Consistent vertical rhythm including jQuery-based image padding
* Better, more informative and SEO-friendly page titles
* Improved post format styling and support
* Google Web Font loading via setting in `functions-config.php`
* Gulp build script CSS/JS minification via Gulp build script
* Built-in contact form page template; no need for a plugin
* jQuery-based search query markup; surrounds search terms with `<mark>`
* jQuery-based HTML5 pullquotes; `<span class="pullquote">Text</span>` will be transformed into `<aside class="pulledquote">Text</aside>` and prepended
* Syntax highlighting via [Prism](http://prismjs.com)


### HACKS, TWEAKS, & SNIPPETS

Most of this stuff is being moved to Ubik.

* Clean search rewrites (e.g. website.com/search/query/ instead of website.com?s=query)
* Context-dependent posts per page (12 items per screen on portfolios, 25 on search, user default elsewhere)



## MARKUP PATTERNS

Several custom markup patterns can be used in posts.


### REFERENCES

This creates a tidy list of references with a hanging indent. Use it for scholarly references.

```
<footer class="references">
  <h3>References</h3>
  <ul>
    <li>A reference. (2014). <cite>A title</cite>. A journal. 100(1), 1-10.</li>
  </ul>
</footer>
```


### QUOTATIONS

This pattern is used by the `content-quote.php` template part. No need to enter the paragraph tags into the post editor but the `footer` tag needs to be on its own line. Links are optional.

```
<blockquote cite="http://synapticism.com">
  <p>This is an example of a blockquote with a HTML5-compliant citation.</p>
  <footer>An Author, <cite><a href="http://synapticism.com">The Title Of A Work</a></cite>, 2014.</footer>
</blockquote>
```


### SYNTAX HIGHLIGHTING

Pendrell ships with [Prism](http://prismjs.com/). Syntax highlighting is easy:

```
<pre>
  <code class="language-php"><?php echo "Hello world!"; ?></code>
</pre>
```

The same can be accomplished with Jetpack's Markdown add-on by using three back-ticks immediately followed by the class name e.g. `language-php`.



## TO DO

This is a disorganized mess :)

* AJAXify image attachment pages
* Better image gallery
* Smarter 404 page
* Related posts
* Favicons?
* Bookmarks template with support for link categories, link descriptions, and private links (see bookmarks.php)
* Additional microformat support
    * rel-tag: http://microformats.org/wiki/rel-tag
    * hNews: http://microformats.org/wiki/hnews
    * hCard: http://microformats.org/wiki/hcard



## LICENSE

GNU General Public License: http://www.gnu.org/licenses/gpl.html
