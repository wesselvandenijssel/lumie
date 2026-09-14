<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'lumie'),
			'type' => 'accordion',
		],

		'type' => [
			'label' => esc_html__('Type', 'lumie'),
			'type' => 'button_group',
			'choices' => [
				'image' => esc_html__('Afbeelding', 'lumie'),
				'video' => esc_html__('Video', 'lumie'),
			],
			'default_value' => 'image',
			'wrapper' => [
				'width' => '50',
			],
		],

		'image' => [
			'label' => esc_html__('Afbeelding', 'lumie'),
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
			'label' => esc_html__('Video', 'lumie'),
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
			'label' => esc_html__('Tekst over de banner', 'lumie'),
			'instructions' => esc_html__('Grote tekst rechtsonder in de banner.', 'lumie'),
			'type' => 'wysiwyg',
			'tabs' => 'visual',
			'toolbar' => 'title',
			'media_upload' => false,
			'delay' => true,
		],

		'title' => [
			'label' => esc_html__('Titel', 'lumie'),
			'type' => 'clone',
			'clone' => [
				'clone_titles_block_title',
			],
			'display' => 'seamless',
		],

		get_flex_content('field_image-banner_content'),

	],
];
