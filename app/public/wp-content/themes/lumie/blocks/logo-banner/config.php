<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'mbeffect'),
			'type' => 'accordion',
		],

		'text' => [
			'label' => esc_html__('Label', 'mbeffect'),
			'type' => 'text',
		],

		'logos' => [
			'label' => esc_html__('Logo\'s', 'mbeffect'),
			'type' => 'repeater',
			'layout' => 'block',
			'button_label' => esc_html__('Nieuw logo', 'mbeffect'),
			'sub_fields' => [
				[
					'key' => 'field_logo-banner_logos_logo',
					'label' => esc_html__('Logo', 'mbeffect'),
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
