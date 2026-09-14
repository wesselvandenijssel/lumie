<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'mbeffect'),
			'type' => 'accordion',
		],

		'content' => [
			'label' => esc_html__('Tekst', 'mbeffect'),
			'type' => 'wysiwyg',
			'media_upload' => false,
			'delay' => true,
		],

		'specifications' => [
			'label' => esc_html__('Specificaties', 'mbeffect'),
			'type' => 'repeater',
			'layout' => 'table',
			'button_label' => esc_html__('Nieuwe specificatie', 'mbeffect'),
			'sub_fields' => [
				[
					'key' => 'field_intro_specifications_label',
					'label' => esc_html__('Label', 'mbeffect'),
					'name' => 'label',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_intro_specifications_value',
					'label' => esc_html__('Waarde', 'mbeffect'),
					'name' => 'value',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
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
