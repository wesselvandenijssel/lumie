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

		get_flex_content('field_electrolytes_content'),

		'items' => [
			'label' => esc_html__('Tegels', 'lumie'),
			'type' => 'repeater',
			'layout' => 'block',
			'max' => 4,
			'collapsed' => 'field_electrolytes_items_title',
			'button_label' => esc_html__('Nieuwe tegel', 'lumie'),
			'sub_fields' => [
				[
					'key' => 'field_electrolytes_items_symbol',
					'label' => esc_html__('Symbool', 'lumie'),
					'name' => 'symbol',
					'type' => 'text',
					'maxlength' => 5,
					'wrapper' => [
						'width' => '25',
					],
				],
				[
					'key' => 'field_electrolytes_items_title',
					'label' => esc_html__('Titel', 'lumie'),
					'name' => 'title',
					'type' => 'text',
					'wrapper' => [
						'width' => '75',
					],
				],
				[
					'key' => 'field_electrolytes_items_description',
					'label' => esc_html__('Beschrijving', 'lumie'),
					'name' => 'description',
					'type' => 'textarea',
					'rows' => 3,
					'new_lines' => '',
				],
			],
		],

	],
];
