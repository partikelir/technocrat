# PENDRELL 0.5

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
* Human-readable dates on recent entries (e.g. posted 21 hours ago)
* Clean search rewrites (e.g. website.com/search/query/ instead of website.com?s=query)
* jQuery-based search query markup; surrounds search terms with `<mark>`
* jQuery-based HTML5 pullquotes; `<span class="pullquote">Text</span>` will be transformed into `<aside class="pulledquote">Text</aside>` and prepended
* Context-dependent posts per page (12 items per screen on portfolios, 25 on search, user default elsewhere)
* Custom site header and other cruft from Twenty Twelve removed

### PORTFOLIO ITEMS

This section is the first part of Pendrell I worked on and correspondingly the least polished. I haven't come back to it in a while but plan to revisit it some day.

* Redesigned for full-width multimedia portfolios and image galleries with big, bold images
* Portfolio items are standard posts formatted any way you like and assigned to a portfolio category e.g. "Portfolio"
* This allows portfolio items to flow alongside other content on your blog
* Use the more tag `<!--more-->` after the first or second image in a portfolio item to keep things tidy
* Use half-width images side by side with `alignleft` as needed; these are also responsive
* Portfolio category archives are responsive, shifting between 3, 2, or 1 column display using media queries
* Portfolio item thumbnails default to the first attached image; if you wish to use another just set the featured image
* Thumbnail-based navigation on image attachments
* EXIF data and other information for image attachments
* Full-width images are dynamically displayed on pages using the full-width template

Portfolio items in Pendrell are not custom post types or anything fancy like that (though they should be); simply make a standard entry under the "Portfolio" category and format your portfolio items however you like. Advanced users can customize what category or categories are treated as portfolio items; just edit `functions-config.php` and modify the `$pendrell_portfolio_cats` variable to include the slugs of any categories you wish to use this feature with. Personally, I have a "Portfolio" parent category with two children, "Design" and "Photography"; all of these plus the "Creative" category are included as defaults in Pendrell.

I happen to like using a mixture of `full-width`, `half-width`, and `third-width` image sizes and regularly feature several different images with each portfolio entry (e.g. a series of photographs, alternate cuts from the same design project, etc.). Layout is a snap: use the media uploader and insert images as "Full Width" (960px wide), "Half Width" (465px wide), or "Third Width" (300px wide). All three sizes come in two versions, either hard cropped or not. Generally speaking, you will want to double or triple up half- and third-width images and apply the `alignleft` style to all but the last in a couplet or triplet, which should be styled with `alignnone`. Managing this is a breeze in the media uploader. The idea here is to let full-width images sprawl and contain half- and third-width images within set boundaries for a nicer layout. If the images you are posting are approximately square you may wish to use the hard cropped versions, otherwise try to ensure that the heights of the images you are laying side by side are approximately the same.

I recommend linking to attachment pages when posting images to your portfolio. The attachment pages in Pendrell are functional, offering EXIF data where available, a link to full-size source material, and thumbnail-based image navigation at the bottom.

Portfolio items are not segregated from other content; your creative projects will appear alongside the rest of your blog entries and still look sharp with a sidebar and widgets. I highly recommend making judicious use of the `<--more-->` tag when posting portfolio items with many images (unless, of course, you'd like to have image-heavy posts co-exist with your regular blog entries).

### PLACES

Places are a Custom Post Type for building a geographic database. This is still in development and very new. I am building it mainly for my own use and may transform it into a proper WordPress plugin at some point.

### SERIES

There is a lightweight series taxonomy for linking posts together. Usage is close to self-explanatory; simply activate in `functions-config.php` and add a series to any post. Everything else should be taken care of.



## MARKUP PATTERNS

Several custom markup patterns can be used in posts.

### REFERENCES

```
<footer class="references">
  <h3>References</h3>
  <ul>
    <li>A reference. (2014). <cite>A title</cite>. A journal. 100(1), 1-10.</li>
  </ul>
</footer>
```

### QUOTATIONS

```
<blockquote cite="http://synapticism.com">
<p>This is an example of a blockquote with a HTML5-compliant citation.</p>
<footer>An Author, <cite><a href="http://synapticism.com">The Title Of A Work</a></cite>, 2014.</footer>
</blockquote>
```



## TO DO

### PRIORITIES

* AJAXify image attachment pages
* Easily disable comments altogether

### MIGHT BE NICE

* Smarter 404 page
* Related posts
* Favicon/Apple touch icons
* Bookmarks template with support for link categories, link descriptions, and private links (see bookmarks.php)
* Additional microformat support
    * rel-tag: http://microformats.org/wiki/rel-tag
    * hNews: http://microformats.org/wiki/hnews
    * hCard: http://microformats.org/wiki/hcard

### PROBABLY NOT

* Internationalization (i18n)
* Better gallery styling
* Test for IE compatibility

## ADDITIONAL RESOURCES

This is mainly a list of links I've maintained for my own reference.

### SUGGESTED PLUGINS

* Akismet: http://wordpress.org/extend/plugins/akismet
* AudioPlayer: http://wordpress.org/extend/plugins/audio-player/
* SyntaxHighlighter Evolved: http://wordpress.org/extend/plugins/syntaxhighlighter/
* WP-PageNavi: http://wordpress.org/extend/plugins/wp-pagenavi/
* WP-PostRatings: http://wordpress.org/extend/plugins/wp-postratings/
* WP Super Cache: http://wordpress.org/extend/plugins/wp-super-cache/
* XML Sitemap Generator: http://wordpress.org/extend/plugins/google-sitemap-generator/

### KEY REFERENCES

* Dive Into HTML5: http://diveintohtml5.ep.io/
* Google Page Speed Recommendations: http://code.google.com/speed/page-speed/docs/rendering.html
* SEOMoz: http://www.seomoz.org/
* WordPress Codex
    * CSS Coding Standards: http://codex.wordpress.org/CSS_Coding_Standards
    * i18n: http://codex.wordpress.org/I18n_for_WordPress_Developers
    * Theme Review: http://codex.wordpress.org/Theme_Review
    * Validation: http://codex.wordpress.org/Validating_a_Website

### PERSONALITIES

* Alex King: http://alexking.org/
* Andrew Nacin: http://nacin.com/
* Harry Roberts @ CSS Wizardry: http://csswizardry.com/ http://csswizardry.com/type-tips/
* Jeff Starr @ DigWP & Perishable Press: http://digwp.com http://perishablepress.com/
* Justin Tadlock: http://justintadlock.com/
* Mark Jaquith: http://markjaquith.wordpress.com/
* Scribu: http://scribu.net/

### USEFUL ARTICLES

* More about the WP-Post-Formats plugin: http://alexking.org/blog/2011/10/25/wordpress-post-formats-admin-ui
* Technical Web Typography: Guidelines and Techniques http://coding.smashingmagazine.com/2011/03/14/technical-web-typography-guidelines-and-techniques/
* The Incredible Em and Elastic Layouts: http://jontangerine.com/log/2007/09/the-incredible-em-and-elastic-layouts-with-css
* WordPress SEO: http://yoast.com/articles/wordpress-seo/

### MORE SNIPPETS

* http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file/
* http://css-tricks.com/snippets/wordpress/
* http://code.hyperspatial.com/category/all-code/wordpress-code/
* http://wpsnipp.com/
* http://perishablepress.com/press/2009/12/01/stupid-wordpress-tricks/

## LICENSE

GNU General Public License: http://www.gnu.org/licenses/gpl.html
