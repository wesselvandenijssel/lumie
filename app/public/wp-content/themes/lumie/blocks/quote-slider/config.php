<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'lumie'),
			'type' => 'accordion',
		],

		'quotes' => [
			'label' => esc_html__('Citaten', 'lumie'),
			'type' => 'repeater',
			'layout' => 'block',
			'button_label' => esc_html__('Nieuw citaat', 'lumie'),
			'min' => 1,
			'sub_fields' => [
				[
					'key' => 'field_quote-slider_quotes_team_member',
					'label' => esc_html__('Teamlid', 'lumie'),
					'name' => 'team_member',
					'type' => 'post_object',
					'post_type' => [
						'team_member',
					],
					'return_format' => 'id',
					'required' => true,
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_quote-slider_quotes_project',
					'label' => esc_html__('Favoriete project', 'lumie'),
					'name' => 'project',
					'type' => 'post_object',
					'post_type' => [
						'project',
					],
					'return_format' => 'id',
					'allow_null' => true,
					'wrapper' => [
						'width' => '50',
					],
				],
				[
					'key' => 'field_quote-slider_quotes_quote',
					'label' => esc_html__('Citaat', 'lumie'),
					'name' => 'quote',
					'type' => 'wysiwyg',
					'delay' => true,
					'media_upload' => false,
					'toolbar' => 'title',
				],
			],
		],

	],
];
