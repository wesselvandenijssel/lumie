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

		'image' => [
			'label' => esc_html__('Afbeelding', 'lumie'),
			'type' => 'image',
			'return_format' => 'id',
			'mime_types' => 'png,jpeg,jpg,webp',
		],
	],
];
