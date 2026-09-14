<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

if (function_exists('acf_add_local_field_group')) :
	acf_add_local_field_group([
		'key' => 'settings_author',
		'title' => esc_html__('Auteur instellingen', 'lumie'),
		'fields' => [
			[
				'key' => 'settings_author_image',
				'name' => 'image',
				'type' => 'image',
				'label' => esc_html__('Portretfoto', 'lumie'),
				'return_format' => 'id',
				'mime_types' => 'jpg,jpeg,png,webp,svg',
				'wrapper' => [
					'width' => '25',
				],
			],
			[
				'key' => 'settings_author_excerpt',
				'name' => 'excerpt',
				'type' => 'textarea',
				'label' => esc_html__('Korte bio', 'lumie'),
				'wrapper' => [
					'width' => '75',
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'user_form',
					'operator' => '==',
					'value' => 'all',
				],
			],
		],
	]);
endif;
