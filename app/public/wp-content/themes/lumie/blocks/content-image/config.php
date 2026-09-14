<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'mbeffect'),
			'type' => 'accordion',
		],

		'order' => [
			'label' => esc_html__('Volgorde', 'mbeffect'),
			'type' => 'true_false',
			'ui_on_text' => esc_html__('Tekst links, afbeelding rechts', 'mbeffect'),
			'ui_off_text' => esc_html__('Tekst rechts, afbeelding links', 'mbeffect'),
			'ui' => true,
		],

		'title' => [
			'label' => esc_html__('Titel', 'mbeffect'),
			'type' => 'clone',
			'clone' => [
				0 => 'clone_titles_block_title',
			],
			'display' => 'seamless',
		],

		get_flex_content('field_content_image_content'),

		'image' => [
			'label' => esc_html__('Afbeelding', 'mbeffect'),
			'type' => 'clone',
			'clone' => [
				0 => 'clone_image_image_group',
			],
			'display' => 'seamless',
		],

		'video' => [
			'label' => esc_html__('Video', 'mbeffect'),
			'type' => 'oembed',
		],
	],
];
