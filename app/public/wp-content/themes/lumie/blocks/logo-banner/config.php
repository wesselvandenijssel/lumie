<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'lumie'),
			'type' => 'accordion',
		],

		'text' => [
			'label' => esc_html__('Label', 'lumie'),
			'type' => 'text',
		],

		'logos' => [
			'label' => esc_html__('Logo\'s', 'lumie'),
			'type' => 'repeater',
			'layout' => 'block',
			'button_label' => esc_html__('Nieuw logo', 'lumie'),
			'sub_fields' => [
				[
					'key' => 'field_logo-banner_logos_logo',
					'label' => esc_html__('Logo', 'lumie'),
					'name' => 'logo',
					'type' => 'image',
					'mime_types' => 'svg, png, jpg, jpeg, webp',
					'return_format' => 'id',
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_logo-banner_logos_link',
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
