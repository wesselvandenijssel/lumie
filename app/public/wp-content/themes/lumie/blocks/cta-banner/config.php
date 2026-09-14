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

		get_flex_content('field_cta-banner_content'),

		'image' => [
			'label' => esc_html__('Afbeelding', 'mbeffect'),
			'type' => 'image',
			'return_format' => 'id',
			'mime_types' => 'png,jpeg,jpg,webp',
		],

		'type' => [
			'label' => esc_html__('Type', 'mbeffect'),
			'type' => 'select',
			'ui' => true,
			'choices' => [
				'none' => esc_html__('Geen', 'mbeffect'),
				'brochure' => esc_html__('Brochure', 'mbeffect'),
				'person' => esc_html__('Contactpersoon', 'mbeffect'),
			],
			'default_value' => 'none',
		],

		'brochure_image' => [
			'label' => esc_html__('Afbeelding brochure', 'mbeffect'),
			'type' => 'image',
			'return_format' => 'id',
			'mime_types' => 'png,jpeg,jpg,webp',
			'max_upload_size' => '256KB',
			'conditional_logic' => [
				[
					[
						'field' => 'field_cta-banner_type',
						'operator' => '==',
						'value' => 'brochure',
					],
				],
			],
		],

		'person' => [
			'label' => esc_html__('Contactpersoon', 'mbeffect'),
			'type' => 'post_object',
			'post_type' => [
				'team_member',
			],
			'return_format' => 'id',
			'conditional_logic' => [
				[
					[
						'field' => 'field_cta-banner_type',
						'operator' => '==',
						'value' => 'person',
					],
				],
			],
		],

	],
];
