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

The following plugins are not quite dependencies but you will probably want to install them to get the most out of Pendrell:

* Ubik, my WordPress toolkit: https://github.com/synapticism/ubik
* My fork of Crowd Favorite's WP-Post-Formats plugin: https://github.com/synapticism/wp-post-formats
* Regenerate Thumbnails: http://wordpress.org/extend/plugins/regenerate-thumbnails/



## FEATURES

### GENERAL ENHANCEMENTS

* Entry content type set at 18px by default for easier reading
* Consistent vertical rhythm including jQuery-based image padding
* Comment and edit post actions are now clickable buttons
* Comment button is now below content and invisible on single posts
* Improved post format styling and support
* Standardized entry meta data display across post formats
* More descriptive archive headers for different content types
* Google Web Font loading
* Gulp build script CSS/JS minification via Gulp build script
* Contact form page template


### HACKS, TWEAKS, & SNIPPETS

* Better, more informative and SEO-friendly page titles
* Clean search rewrites (e.g. website.com/search/query/ instead of website.com?s=query)
* jQuery-based search query markup; surrounds search terms with `<mark>`
* jQuery-based HTML5 pullquotes; `<span class="pullquote">Text</span>` will be transformed into `<aside class="pulledquote">Text</aside>` and prepended
* Context-dependent posts per page (12 items per screen on portfolios, 25 on search, user default elsewhere)
* Custom site header and other cruft from Twenty Twelve removed



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
