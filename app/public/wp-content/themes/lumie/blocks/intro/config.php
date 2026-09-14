<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'lumie'),
			'type' => 'accordion',
		],

		'content' => [
			'label' => esc_html__('Tekst', 'lumie'),
			'type' => 'wysiwyg',
			'media_upload' => false,
			'delay' => true,
		],

		'specifications' => [
			'label' => esc_html__('Specificaties', 'lumie'),
			'type' => 'repeater',
			'layout' => 'table',
			'button_label' => esc_html__('Nieuwe specificatie', 'lumie'),
			'sub_fields' => [
				[
					'key' => 'field_intro_specifications_label',
					'label' => esc_html__('Label', 'lumie'),
					'name' => 'label',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_intro_specifications_value',
					'label' => esc_html__('Waarde', 'lumie'),
					'name' => 'value',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
				],
			],
		],

		'buttons' => [
			'label' => esc_html__('Button(s)', 'lumie'),
			'type' => 'clone',
			'clone' => [
				'clone_buttons_buttons_group',
			],
			'display' => 'seamless',
		],

	],
];
