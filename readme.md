# PENDRELL 0.2

Pendrell is a child theme for Twenty Twelve designed to allow two types of content to co-exist in harmony:

* Blog entries set with beautiful, legible type
* Portfolio items featuring big, bold full-width images

Many WordPress themes provide for one or the other but few handle both types of content with elegance and simplicity. Portfolio items in Pendrell are not custom post types or anything fancy like that; simply post under the "Portfolio" category and format your portfolio items however you like. 

**THIS THEME IS STILL UNDER DEVELOPMENT!**

See Pendrell in action on my blog: http://synapticism.com

## INSTALLATION

You must have Twenty Twelve installed before using Pendrell. Drop the 'pendrell' directory into /wp-content/themes/ and activate it via the WordPress admin interface.

This theme has no options page; you will have to get your feet wet and modify the `functions-config-sample.php` file, renaming it to `functions-config.php`, if you wish to change any of the defaults. Most of these should be self-explanatory; read the comments for more direction.

## RECOMMENDED PLUGINS

Not quite dependencies but you will probably want to install these plugins to get the most out of Pendrell:

* Crowd Favorite's WP-Post-Formats plugin: https://github.com/crowdfavorite/wp-post-formats
* Regenerate Thumbnails: http://wordpress.org/extend/plugins/regenerate-thumbnails/

## FEATURES

### GENERAL ENHANCEMENTS

* Custom site header on top of navigation instead of below
* Entry content type set at 16px for easier reading
* Standardized entry meta data display across post formats
* Comment and edit post actions are now clickable buttons
* Comment button is now below content and invisible on single posts
* Improved post format styling and support
* More descriptive archive headers for different content types
* Removed page margin at top and bottom to make more efficient use of vertical space
* Disqus compatibility; simply activate and it'll look sharp

### PORTFOLIO ITEMS & IMAGES

* Redesigned for full-width multimedia portfolios and image galleries with big, bold images
* Portfolio items are standard posts formatted any way you like and assigned to a portfolio category e.g. "Portfolio"
* This allows portfolio items to flow alongside other content on your blog
* Use the more tag `<!--more-->` after the first or second image in a portfolio item to keep things tidy
* Portfolio category archives are responsive, shifting between 3, 2, or 1 column display using media queries
* Thumbnail-based navigation on image attachments
* EXIF data and other information for image attachments
* Full-width images are dynamically displayed on pages using the full-width template

### HACKS, TWEAKS, & SNIPPETS

* Better, more informative and SEO-friendly page titles
* Human-readable dates on recent entries (e.g. posted 21 hours ago)
* Thumbnail fallback: if a featured image isn't set the appropriately-sized thumbnail of the first image will be displayed
* Clean search rewrites (e.g. website.com/search/query/ instead of website.com?s=query)
* Singleton search results redirect to matching post in one step
* jQuery-based search query markup; surrounds search terms with `<mark>`
* jQuery-based HTML5 pullquotes; `<span class="pullquote">Text</span>` will be transformed into `<aside class="pulledquote">Text</aside>` and prepended
* Context-dependent posts per page (12 items per screen on portfolios, 25 on search, user default elsewhere)
* Reset admin-side HTML editor to a nicer font stack

## TO DO

In no particular order...

* Internationalization (i18n)
* Serif, sans-serif, and monospace font stack switching
* Smart 404 page
* Better gallery styling
* AJAXify image attachment pages
* Related posts
* Easily disable comments altogether
* Test for IE compatibility
* Favicon/Apple touch icons
* Post format icons?
* Infinite scrolling option
* Front-end posting: http://scribu.net/wordpress/front-end-editor http://wordpress.org/extend/plugins/posthaste/
* Bookmarks template with support for link categories, link descriptions, and private links (see bookmarks.php)
* CSS minification
* Additional microformat support
    * rel-tag: http://microformats.org/wiki/rel-tag
    * hNews: http://microformats.org/wiki/hnews
    * hCard: http://microformats.org/wiki/hcard

## ADDITIONAL RESOURCES

### SUGGESTED PLUGINS

* Akismet: http://wordpress.org/extend/plugins/akismet
* AudioPlayer: http://wordpress.org/extend/plugins/audio-player/
* Contact Form 7: http://wordpress.org/extend/plugins/contact-form-7/
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

GNU General Public Licence: http://www.gnu.org/licenses/gpl.html
