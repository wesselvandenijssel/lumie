<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'mbeffect'),
			'type' => 'accordion',
		],

		'type' => [
			'label' => esc_html__('Type', 'mbeffect'),
			'type' => 'button_group',
			'choices' => [
				'image' => esc_html__('Afbeelding', 'mbeffect'),
				'video' => esc_html__('Video', 'mbeffect'),
			],
			'default_value' => 'image',
			'wrapper' => [
				'width' => '50',
			],
		],

		'image' => [
			'label' => esc_html__('Afbeelding', 'mbeffect'),
			'type' => 'image',
			'return_format' => 'id',
			'mime_types' => 'png,jpeg,jpg,webp',
			'conditional_logic' => [
				[
					[
						'field' => 'field_image-banner_type',
						'operator' => '==',
						'value' => 'image',
					],
				],
			],
		],

		'video' => [
			'label' => esc_html__('Video', 'mbeffect'),
			'type' => 'textarea',
			'conditional_logic' => [
				[
					[
						'field' => 'field_image-banner_type',
						'operator' => '==',
						'value' => 'video',
					],
				],
			],
		],

		'text' => [
			'label' => esc_html__('Tekst over de banner', 'mbeffect'),
			'instructions' => esc_html__('Grote tekst rechtsonder in de banner.', 'mbeffect'),
			'type' => 'wysiwyg',
			'tabs' => 'visual',
			'toolbar' => 'title',
			'media_upload' => false,
			'delay' => true,
		],

		'title' => [
			'label' => esc_html__('Titel', 'mbeffect'),
			'type' => 'clone',
			'clone' => [
				'clone_titles_block_title',
			],
			'display' => 'seamless',
		],

		get_flex_content('field_image-banner_content'),

	],
];
