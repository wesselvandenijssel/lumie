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

		get_flex_content('field_text-cards_content'),

		'cards' => [
			'label' => esc_html__('Kaartjes', 'lumie'),
			'type' => 'repeater',
			'layout' => 'block',
			'button_label' => esc_html__('Nieuw kaartje', 'lumie'),
			'sub_fields' => [
				[
					'key' => 'field_text-cards_cards_image',
					'label' => esc_html__('Afbeelding', 'lumie'),
					'name' => 'image',
					'type' => 'image',
					'mime_types' => 'png, jpg, jpeg, webp',
					'return_format' => 'id',
				],
				[
					'key' => 'field_text-cards_cards_suptitle',
					'label' => esc_html__('Boventitel', 'lumie'),
					'name' => 'suptitle',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_text-cards_cards_title',
					'label' => esc_html__('Titel', 'lumie'),
					'name' => 'title',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_text-cards_cards_content',
					'label' => esc_html__('Content', 'lumie'),
					'name' => 'content',
					'type' => 'text',
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_text-cards_cards_link',
					'label' => esc_html__('Link', 'lumie'),
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
