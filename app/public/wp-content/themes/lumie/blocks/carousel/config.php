<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'lumie'),
			'type' => 'accordion',
		],

		'title' => [
			'label' => esc_html__('Titel', 'lumie'),
			'type' => 'clone',
			'clone' => [
				'clone_titles_block_title',
			],
			'display' => 'seamless',
		],

		'slides' => [
			'label' => esc_html__('Slides', 'lumie'),
			'type' => 'repeater',
			'layout' => 'block',
			'button_label' => esc_html__('Nieuwe slide', 'lumie'),
			'min' => 1,
			'sub_fields' => [
				[
					'key' => 'field_carousel_slides_type',
					'label' => esc_html__('Type', 'lumie'),
					'name' => 'type',
					'type' => 'button_group',
					'choices' => [
						'image' => esc_html__('Afbeelding', 'lumie'),
						'video' => esc_html__('Video', 'lumie'),
					],
					'default_value' => 'image',
				],
				[
					'key' => 'field_carousel_slides_image',
					'label' => esc_html__('Afbeelding', 'lumie'),
					'name' => 'image',
					'type' => 'image',
					'return_format' => 'id',
					'mime_types' => 'png,jpeg,jpg,webp',
					'conditional_logic' => [
						[
							[
								'field' => 'field_carousel_slides_type',
								'operator' => '==',
								'value' => 'image',
							],
						],
					],
				],
				[
					'key' => 'field_carousel_slides_video',
					'label' => esc_html__('Video', 'lumie'),
					'name' => 'video',
					'type' => 'textarea',
					'conditional_logic' => [
						[
							[
								'field' => 'field_carousel_slides_type',
								'operator' => '==',
								'value' => 'video',
							],
						],
					],
				],
			],
		],

	],
];
