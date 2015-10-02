<?php // ==== CONTENT: GALLERY ==== //

echo ubik_imagery(
  $html     = '',
  $id       = pendrell_thumbnail_id(),
  $caption  = get_the_title(),
  $title    = '',
  $align    = '',
  $url      = get_permalink(),
  $size     = 'third-square',
  $alt      = '',
  $rel      = '',
  $class    = array_merge( get_post_class(), array( 'overlay ' ) ),
  $contents = pendrell_image_overlay_contents(),
  $context  = array( 'group', 'responsive' )
);
