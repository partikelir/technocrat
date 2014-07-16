<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.10
 */
?>

	<?php echo pendrell_image_markup(
    $html = '',
    $id = pendrell_thumbnail_id(),
    $caption = get_the_title(),
    $title = '',
    $align = '',
    $url = get_permalink(),
    $size = 'third-square',
    $alt = '',
    $rel = '',
    $classes = get_post_class()
  ); ?>
