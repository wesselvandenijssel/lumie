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
			'wrapper' => [
				'width' => '50',
			],
		],

		'size' => [
			'label' => esc_html__('Grootte', 'lumie'),
			'type' => 'button_group',
			'choices' => [
				'450' => esc_html__('450px hoog', 'lumie'),
				'650' => esc_html__('650px hoog', 'lumie'),
				'850' => esc_html__('850px hoog', 'lumie'),
			],
			'default_value' => '450',
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
						'field' => 'field_hero_type',
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
						'field' => 'field_hero_type',
						'operator' => '==',
						'value' => 'video',
					],
				],
			],
		],

		'title' => [
			'label' => esc_html__('Titel', 'lumie'),
			'type' => 'clone',
			'clone' => [
				'clone_titles_block_title',
			],
			'display' => 'seamless',
		],

		get_flex_content('field_hero_content'),

	],
];
