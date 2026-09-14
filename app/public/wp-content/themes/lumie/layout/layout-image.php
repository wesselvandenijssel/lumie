<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$image = $image ?? [];
$class = $class ?? '';

if (empty($image) || empty($image['image'])) return;

$image_class = '';

if (!empty($class)) {
	$image_class = ['class' => $class];
}

$image_class['loading'] = 'lazy';

if (isset($image['image_size']) && !empty($image['image_size'])) {
	echo wp_get_attachment_image($image['image'], $image['image_size'], false, $image_class);
} else {
	echo wp_get_attachment_image($image['image'], 'full', false, $image_class);
}
