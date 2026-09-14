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

		get_flex_content('field_cards_content'),

		'cards' => [
			'label' => esc_html__('Kaartjes', 'mbeffect'),
			'type' => 'repeater',
			'layout' => 'block',
			'button_label' => esc_html__('Nieuw kaartje', 'mbeffect'),
			'sub_fields' => [
				[
					'key' => 'field_cards_cards_image',
					'label' => esc_html__('Afbeelding', 'mbeffect'),
					'name' => 'image',
					'type' => 'image',
					'mime_types' => 'png, jpg, jpeg, webp',
					'return_format' => 'id',
				],
				[
					'key' => 'field_cards_cards_title',
					'label' => esc_html__('Titel', 'mbeffect'),
					'name' => 'title',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_cards_cards_link',
					'label' => esc_html__('Link', 'mbeffect'),
					'name' => 'link',
					'type' => 'link',
					'wrapper' => [
						'width' => '50',
					],
				],
			],
		],

	],
];
