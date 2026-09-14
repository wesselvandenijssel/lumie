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
			'wrapper' => [
				'width' => '50',
			],
		],

		'size' => [
			'label' => esc_html__('Grootte', 'mbeffect'),
			'type' => 'button_group',
			'choices' => [
				'450' => esc_html__('450px hoog', 'mbeffect'),
				'650' => esc_html__('650px hoog', 'mbeffect'),
				'850' => esc_html__('850px hoog', 'mbeffect'),
			],
			'default_value' => '450',
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
						'field' => 'field_hero_type',
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
						'field' => 'field_hero_type',
						'operator' => '==',
						'value' => 'video',
					],
				],
			],
		],

		'title' => [
			'label' => esc_html__('Titel', 'mbeffect'),
			'type' => 'clone',
			'clone' => [
				'clone_titles_block_title',
			],
			'display' => 'seamless',
		],

		get_flex_content('field_hero_content'),

	],
];
