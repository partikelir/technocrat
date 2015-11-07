<?php // ==== CONTENT: LIST ==== // ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
  <div class="entry-thumbnail">
    <?php echo ubik_imagery(
      $html     = '',
      $id       = pendrell_thumbnail_id(),
      $caption  = '',
      $title    = '',
      $align    = '',
      $url      = get_permalink(),
      $size     = 'thumbnail',
      $alt      = '',
      $rel      = '',
      $class    = array_merge( get_post_class(), array( 'overlay ' ) ),
      $contents = '',
      $context  = 'list'
    ); ?>
  </div>
  <div class="entry-content-wrapper">
    <header class="entry-header">
      <?php do_action( 'pendrell_entry_header' ); ?>
    </header>
    <div class="entry-content">
      <?php pendrell_views_list_content(); ?>
    </div>
  </div>
</article>
