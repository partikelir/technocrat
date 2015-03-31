<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.10
 */

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
  $contents = pendrell_image_overlay_metadata(),
  $context  = array( 'group', 'responsive' )
);
