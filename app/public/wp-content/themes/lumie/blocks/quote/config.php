<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'mbeffect'),
			'type' => 'accordion',
		],

		'title' => [
			'label' => esc_html__('Citaat', 'mbeffect'),
			'type' => 'wysiwyg',
			'delay' => true,
			'media_upload' => false,
			'toolbar' => 'title',

		],

		'author_name' => [
			'label' => esc_html__('Naam', 'mbeffect'),
			'type' => 'text',
			'wrapper' => [
				'width' => '50',
			],
		],

		'author_role' => [
			'label' => esc_html__('Functie', 'mbeffect'),
			'type' => 'text',
			'wrapper' => [
				'width' => '50',
			],
		],

	],
];
