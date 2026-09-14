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
	$image_settings = get_field('image_settings_group', 'options');

	if (!empty($image_settings) && !empty($image_settings['thumbnails'])) {
		add_image_size('Author thumb', 60, 60, true);
		add_image_size('Blog detail', 610, 500, true);
		add_image_size('Card', 425, 560, true);
		add_image_size('CTA banner', 700, 425, true);
		add_image_size('Gallery', 600, 365, true);
		add_image_size('Gallery XL', 600, 870, true);
		add_image_size('Gallery full', 1170, 630, true);
		add_image_size('Hero 450', 1920, 675, true); // Height times 1.5 for parallax
		add_image_size('Hero 650', 1920, 975, true); // Height times 1.5 for parallax
		add_image_size('Hero 850', 1920, 1275, true); // Height times 1.5 for parallax
		add_image_size('Hero mobile', 740, 250, true);
		add_image_size('Image banner', 1920, 1580, true); // Height times 1.5 for parallax
		add_image_size('Method', 340, 250, true);
		add_image_size('Post', 400, 270, true);
		add_image_size('Premium content', 1920, 1520, true); // Height times 1.5 for parallax
		add_image_size('Project Landscape', 570, 390, true);
		add_image_size('Project Portrait', 340, 450, true);
		add_image_size('Project XL', 1385, 730, true);
		add_image_size('Quote slider', 655, 855, true);
		add_image_size('Team', 312, 400, true);
		add_image_size('Team mini', 100, 130, true);
		add_image_size('Text card', 425, 295, true);
	}
}
