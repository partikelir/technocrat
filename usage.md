# USAGE

**Note: this file is outdated.**

## PLUGINS

Apart from [Ubik](https://github.com/synapticism/ubik), which is pretty much mandatory, these plugins are recommended for use with Pendrell:

* [Ubik](https://github.com/synapticism/ubik), my all-purpose WordPress toolkit, designed for use with this very theme. **Highly recommended!**
* [My fork](https://github.com/synapticism/wp-post-formats) of Crowd Favorite's [WP-Post-Formats plugin](https://github.com/crowdfavorite/wp-post-formats).
* [JP Markdown](http://wordpress.org/plugins/jetpack-markdown/) to enable Markdown.
* [Google XML sitemaps](http://www.arnebrachhold.de/redir/sitemap-home/).
* [WP-Super-Cache](http://ocaoimh.ie/wp-super-cache/).
* [Akismet](http://akismet.com/).

Utilities:

* [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) since Pendrell uses custom image sizes.
* [Un-attach and re-attach media attachments](http://wordpress.org/plugins/unattach-and-re-attach-attachments/) in case you get mixed up.
* [Post type switcher](http://wordpress.org/extend/post-type-switcher/).



## PUBLISHING

### IMAGE MANAGEMENT

Pendrell makes extensive use of featured image/post thumbnail functionality.

### IMAGE SHORTCODE

If [Ubik](https://github.com/synapticism/ubik) is active Pendrell will handle images and image captions with an image shortcode. There is no need to write your own shortcode; simply insert an image from the media library and it should look something like this:

```
[image id="1001" alt="Alt text goes here."/]
```

Captions are contained within the image shortcode and can include other shortcodes and basic text formatting HTML (e.g. `<em>`, `<strong>`, etc.) and links:

```
[image id="1001" alt="Alt text goes here."]This is a caption![/image]
```

Image layout with Ubik is easy; just use the `[group]` shortcode:

```
[group][image id="1001" alt="Alt text goes here."]This is a caption![/image]

[image id="1002" alt="More alt text goes here."]This is another caption![/image][/group]
```

You can also specify sizes like `half` and `third` (defined in `functions.php` to save bandwidth:

```
[group][image id="1001" size="third-square" alt="Alt text goes here."]This is a caption![/image]

[image id="1002" size="third-square" alt="More alt text goes here."]This is another caption![/image]

[image id="1003" size="third-square" alt="Even more alt text."]A third caption![/image][/group]
```

This will output a responsive HTML5-compliant image layout using `figure` and `figcaption`.

Dig into Ubik's code to learn more.



## POST FORMATS

Pendrell supports five post formats at present. At present (autumn 2014) I recommend using [my fork](https://github.com/synapticism/wp-post-formats) of Crowd Favorite's [WP-Post-Formats plugin](https://github.com/crowdfavorite/wp-post-formats) to manage post formats on the back-end.

### ASIDE

Longer than a status but not as important as a regular blog posting. Use asides as the name would imply: as a side comment of no great importance that does not feature any imagery.

### GALLERY

Full-width display, otherwise no different than a regular post. Insert images in the usual way; attachments and featured images won't be shown by default.

### IMAGE

Full-width display. Image posts should be used as a wrapper for attachments. Set a featured image and write about the photo in the body of the post itself. Give the attachment a title and caption. *Do not* enter a description on the image unless you want it echoed into the image post itself.

### QUOTATION

Don't put quotation marks around your quotation! The body of the post should contain the body of the quotation, nothing more.

### STATUS

Should be short (less than 140 characters).



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

Pendrell ships with [Prism](http://prismjs.com/) but must be activated in `functions-config.php`. Syntax highlighting is easy:

```
<pre>
  <code class="language-php"><?php echo "Hello world!"; ?></code>
</pre>
```

The same can be accomplished with JP Markdown by using three back-ticks immediately followed by the class name *e.g.* `language-php`.

If you want to highlight any languages I haven't included in the Prism file shipped with Pendrell you can roll your own and replace `src/js/prism.js` before running `gulp dist` and uploading a new distribution.



### NOT RECOMMENDED

The WordPress code base is littered with old functions that are hard to work with, produce ugly markup, or simply aren't very good. To polish the output of these functions or to replicate the functionality might be out of scope for this theme--and so there are a few things I recommend *not* using:

- In-post pagination with `<!--nextpage-->` and so on. This calls `wp_link_pages()`, a function that doesn't even allow you to specify a divider between page numbers. Avoid!
- Audio, video, or any other media shortcode; I haven't yet messed around with any of these as I don't use them myself.

