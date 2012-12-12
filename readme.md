# PENDRELL 0.1

Pendrell is a simple child theme for the default Twenty Twelve WordPress theme. I designed it for my own use, not for distribution, but you may find it to be a good starting point for your own projects.

**THIS THEME IS STILL UNDER DEVELOPMENT; DO NOT DEPLOY!**

## INSTALLATION

You must have Twenty Twelve installed before using Pendrell. Drop the 'pendrell' directory into /wp-content/themes/ and activate it via the WordPress admin interface.

There are several options at the top of the functions-config.php file. Most of these should be self-explanatory. No theme options page is included.

## FEATURES

Above and beyond what Twenty Twelve provides, of course...

### WORKING

* Slightly different look and feel; still clean and responsive
* Custom header on top of navigation instead of below
* Better, more informative and SEO-friendly page titles
* Human-readable dates on recent entries (e.g. posted 21 hours ago)
* Standardized entry meta data display across post formats
* Comment and edit post actions are now clickable buttons
* Comment button is now below content and only visible in post archives
* Full post format support with Crowd Favorite's WP-Post-Formats plugin (recommended): https://github.com/crowdfavorite/wp-post-formats
* Redesigned for full-width design and photography portfolios
* EXIF data and other information for images on attachment pages
* Thumbnail-based navigation within image galleries
* Clean search rewrites (e.g. website.com/search/query/ instead of website.com?s=query)
* Unique search results redirect to matching post in one step
* Serif, sans-serif, and monospace font stack switching
* Reset admin-side HTML editor to a nicer font stack
* Full-width images are dynamically displayed on pages using the full-width template
* Disqus compatibility; simply activate and it'll look sharp

### STILL TO DO

In no particular order...

* Internationalization (i18n)
* Smart 404 page
* Related posts
* Easily disable comments altogether
* Test for IE compatibility
* Favicon/Apple touch icons
* Post format icons
* jQuery-based pullquotes; `<span class="pullquote">Text</span>` will be transformed into `<aside class="pulledquote">Text</aside>` and prepended
* jQuery-based search query markup; surrounds search terms with `<mark>`
* Infinite scrolling option
* Image slider e.g. Orbit: http://www.zurb.com/playground/orbit-jquery-image-slider
* Front-end posting: http://scribu.net/wordpress/front-end-editor http://wordpress.org/extend/plugins/posthaste/
* Bookmarks template with support for link categories, link descriptions, and private links (see bookmarks.php)
* CSS minification
* Additional microformat support
    * rel-tag: http://microformats.org/wiki/rel-tag
    * hNews: http://microformats.org/wiki/hnews
    * hCard: http://microformats.org/wiki/hcard

## ADDITIONAL RESOURCES

### RECOMMENDED PLUGINS

These are some plugins I tend to use on my sites.

* Akismet: http://wordpress.org/extend/plugins/akismet
* AudioPlayer: http://wordpress.org/extend/plugins/audio-player/
* Contact Form 7: http://wordpress.org/extend/plugins/contact-form-7/
* Regenerate Thumbnails: http://wordpress.org/extend/plugins/regenerate-thumbnails/
* Sociable: http://wordpress.org/extend/plugins/sociable/
* Subscribe2: http://wordpress.org/extend/plugins/subscribe2/
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

## LICENCE

GNU General Public Licence 2.0: http://www.gnu.org/licenses/gpl-2.0.html
