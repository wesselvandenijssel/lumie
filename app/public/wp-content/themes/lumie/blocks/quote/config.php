<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'lumie'),
			'type' => 'accordion',
		],

		'title' => [
			'label' => esc_html__('Citaat', 'lumie'),
			'type' => 'wysiwyg',
			'delay' => true,
			'media_upload' => false,
			'toolbar' => 'title',

		],

		'author_name' => [
			'label' => esc_html__('Naam', 'lumie'),
			'type' => 'text',
			'wrapper' => [
				'width' => '50',
			],
		],

		'author_role' => [
			'label' => esc_html__('Functie', 'lumie'),
			'type' => 'text',
			'wrapper' => [
				'width' => '50',
			],
		],

	],
];
