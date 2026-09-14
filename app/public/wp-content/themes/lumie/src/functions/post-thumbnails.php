<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

// wp thumbnails
add_theme_support('post-thumbnails');

// Thumbnails
add_action('acf/init', 'hook_thumbnail_methods', 20);

/**
 * Hook methods for managing thumbnails.
 */
function hook_thumbnail_methods(): void {
	$image_settings = get_field('image_settings_group', 'utilities');

	if (!empty($image_settings) && !empty($image_settings['thumbnails'])) {
		add_image_size('Author thumb', 60, 60, true);
		add_image_size('Blog detail', 610, 500, true);
		add_image_size('Hero 900', 1920, 900, true);
		add_image_size('Hero mobile', 740, 250, true);
		add_image_size('Post', 400, 270, true);
	}
}
