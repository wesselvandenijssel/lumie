<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

add_action('acf/init', 'hook_image_methods', 20);

function hook_image_methods(): void {

	add_filter('intermediate_image_sizes', 'remove_default_img_sizes', 10, 1);

	function remove_default_img_sizes($sizes) {
		$targets = ['medium_large', '1536x1536', '2048x2048'];

		foreach ($sizes as $size_index => $size) {
			if (in_array($size, $targets)) {
				unset($sizes[$size_index]);
			}
		}

		return $sizes;
	}

	$fields = [
		[
			'label' => esc_html__('Afbeelding', 'mbeffect'),
			'type' => 'image',
			'name' => 'image',
			'return_format' => 'id',
			'mime_types' => 'svg, png, jpg, jpeg, webp',
			'key' => 'clone_image_image_group_image',
		]
	];

	if (!empty(get_field('image_settings_group', 'utilities')['thumbnails'])) {
		$fields[] = [
			'label' => esc_html__('Afbeelding formaat', 'mbeffect'),
			'type' => 'select',
			'name' => 'image_size',
			'key' => 'clone_image_image_group_image_size',
			'choices' => array_merge(['full'], get_intermediate_image_sizes()),
			'return_format' => 'label',
		];
	}

	if (function_exists('acf_add_local_field_group')) :
		acf_add_local_field_group([
			'key' => 'clone_image',
			'title' => esc_html__('Kloon: Image', 'mbeffect'),
			'fields' => [
				[
					'label' => esc_html__('Afbeelding', 'mbeffect'),
					'type' => 'group',
					'name' => 'image_group',
					'key' => 'clone_image_image_group',
					'sub_fields' => $fields
				],
			],
		]);
	endif;
}
