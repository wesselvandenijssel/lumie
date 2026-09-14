<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

// add colors to color pallete in Gutenberg
function add_custom_text_color_pallete() {

	// The new colors we are going to add - the name of the colors are set by the naming of "Name that color": https://chir.ag/projects/name-that-color/#414141
	$newColorPalette = [
		[
			'name' => esc_html__('White', 'mbeffect'),
			'slug' => 'white',
			'color' => '#ffffff',
		],

		[
			'name' => esc_html__('Spring Wood', 'mbeffect'),
			'slug' => 'spring-wood',
			'color' => '#F5F2EB',
		],

		[
			'name' => esc_html__('Pearl Bush', 'mbeffect'),
			'slug' => 'pearl-bush',
			'color' => '#EAE5D8',
		],
	];

	// Apply the color palette containing the new colors:
	add_theme_support('editor-color-palette', $newColorPalette);
}
add_action('after_setup_theme', 'add_custom_text_color_pallete');

// add gradients to color pallete in Gutenberg
function add_custom_gradient_color_pallete() {

	$newColorPalette = [
		[
			'name' => esc_html__('Dark to Light', 'mbeffect'),
			'gradient' => 'linear-gradient(135deg, #2e333e 0%, #ebebeb 100%)',
			'slug' => 'dark-to-light',
		],
	];

	// Apply the color palette containing the new colors:
	add_theme_support('editor-gradient-presets', $newColorPalette);
}
// Disabled by default
// add_action('after_setup_theme', 'add_custom_gradient_color_pallete');
