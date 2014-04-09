# Pendrell usage notes

## POST FORMATS

Pendrell is equipped with aside, image, link, quotation, and status post formats.

### IMAGES

Image posts should be used as a wrapper for attachments. Set a featured image and write about the photo in the body of the post itself. Give the attachment a title and caption but don't enter a description as this will be echoed into the image post itself.



## MARKUP PATTERNS

Several custom markup patterns for use in WordPress posts.

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
