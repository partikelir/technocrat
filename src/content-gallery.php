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
  $class    = get_post_class(),
  $contents = pendrell_image_overlay( get_comments_number() . ' ' . pendrell_icon( 'ion-chatbubble', __( 'Comments', 'pendrell' ) ) ),
  $group    = 3
);
