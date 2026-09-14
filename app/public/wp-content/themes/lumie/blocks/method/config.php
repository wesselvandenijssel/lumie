<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'mbeffect'),
			'type' => 'accordion',
		],

		'title' => [
			'label' => esc_html__('Titel', 'mbeffect'),
			'type' => 'clone',
			'clone' => [
				'clone_titles_block_title',
			],
			'display' => 'seamless',
		],

		get_flex_content('field_method_content'),

		'steps' => [
			'label' => esc_html__('Stappen', 'mbeffect'),
			'type' => 'repeater',
			'layout' => 'block',
			'button_label' => esc_html__('Nieuwe stap', 'mbeffect'),
			'sub_fields' => [
				[
					'key' => 'field_method_steps_image',
					'label' => esc_html__('Afbeelding', 'mbeffect'),
					'name' => 'image',
					'type' => 'image',
					'mime_types' => 'png, jpg, jpeg, webp',
					'return_format' => 'id',
				],
				[
					'key' => 'field_method_steps_suptitle',
					'label' => esc_html__('Boventitel', 'mbeffect'),
					'name' => 'suptitle',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_method_steps_title',
					'label' => esc_html__('Titel', 'mbeffect'),
					'name' => 'title',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_method_steps_description',
					'label' => esc_html__('Beschrijving', 'mbeffect'),
					'name' => 'description',
					'type' => 'wysiwyg',
					'delay' => true,
				],
			],
		],

		'buttons' => [
			'label' => esc_html__('Button(s)', 'mbeffect'),
			'type' => 'clone',
			'clone' => [
				'clone_buttons_buttons_group',
			],
			'display' => 'seamless',
		],

	],
];
