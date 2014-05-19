# PENDRELL: USAGE

## PLUGINS

Apart from [Ubik](https://github.com/synapticism/ubik), which is pretty much mandatory, these plugins are recommended for use with Pendrell:

* [Ubik](https://github.com/synapticism/ubik), my all-purpose WordPress toolkit, designed for use with this very theme. **Highly recommended!**
* [My fork](https://github.com/synapticism/wp-post-formats) of Crowd Favorite's [WP-Post-Formats plugin](https://github.com/crowdfavorite/wp-post-formats).
* [Jetpack](https://github.com/Automattic/jetpack), especially for its Markdown module.
* [Google XML sitemaps](http://www.arnebrachhold.de/redir/sitemap-home/).
* [Akismet](http://akismet.com/).
* [WP-Super-Cache](http://ocaoimh.ie/wp-super-cache/).

Utilities:

* [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) since Pendrell uses custom image sizes.
* [Un-attach and re-attach media attachments](http://wordpress.org/plugins/unattach-and-re-attach-attachments/) in case you get mixed up.
* [Post type switcher](http://wordpress.org/extend/post-type-switcher/).



## PUBLISHING

### IMAGE SHORTCODE

If [Ubik](https://github.com/synapticism/ubik) is active Pendrell will handle images and image captions with an image shortcode. There is no need to write your own shortcode; simply insert an image from the media library and it should look something like this:

```
[image id="1001" url="http://yourwebsite.com/a-post/the-attachment" size="medium" alt="Alt text goes here."/]
```

Captions are contained within the image shortcode and can include other shortcodes and basic text formatting HTML (e.g. `<em>`, `<strong>`, etc.) and links:

```
[image id="1001" url="http://yourwebsite.com/a-post/the-attachment" size="medium" alt="Alt text goes here."]This is a caption![/image]
```

Image layout is a bit tricky at first but easy once you know what you are doing. Pendrell specifies two extra image sizes, `medium-half` and `medium-third`, that correspond to a half and a third of the `$content-width` variable (which defaults to 960 px).

These image sizes are designed to allow a harmonious layout of images in columns without mucking around with a lot of excess markup. All you have to do is set the align property to `left`. (The default, `none`, does not need to be explicitly specified.) Captions work beautifully, though you'll probably want to keep them approximately the same length to preserve visual harmony.

An example of a half-width layout:

```
[image id="1001" align="left" url="http://yourwebsite.com/a-post/the-attachment" size="medium-half" alt="Alt text goes here."]This is a caption![/image]

[image id="1002" url="http://yourwebsite.com/a-post/another-attachment" size="medium-half" alt="Alt text goes here."]This is a caption![/image]
```

An example of a third-width layout:

```
[image id="1001" align="left" url="http://yourwebsite.com/a-post/the-attachment" size="medium-half" alt="Alt text goes here."]This is a caption![/image]

[image id="1002" url="http://yourwebsite.com/a-post/another-attachment" size="medium-half" alt="Alt text goes here."]This is a caption![/image]
```



## IMAGE POST FORMAT

Image posts should be used as a wrapper for attachments. Set a featured image and write about the photo in the body of the post itself. Give the attachment a title and caption. *Do not* enter a description unless you want it echoed into the image post itself.



## MARKUP PATTERNS

Several custom markup patterns for use in WordPress posts.

### REFERENCES

This creates a tidy list of references with a hanging indent. Use it for scholarly references.

```
<footer class="references">
  <h3>References</h3>
  <ul>
    <li itemprop="citation">A reference. (2014). <cite>A title</cite>. A journal. 100(1), 1-10.</li>
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
