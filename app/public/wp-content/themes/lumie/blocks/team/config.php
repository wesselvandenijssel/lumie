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

		'content' => [
			'label' => esc_html__('Tekst', 'mbeffect'),
			'type' => 'wysiwyg',
			'tabs' => 'visual',
			'toolbar' => 'basic',
			'media_upload' => false,
			'delay' => true,
		],

		'selection' => [
			'label' => esc_html__('Selectie', 'mbeffect'),
			'type' => 'button_group',
			'choices' => [
				'all' => esc_html__('Alle teamleden', 'mbeffect'),
				'specific' => esc_html__('Specifiek', 'mbeffect'),
			],
			'default_value' => 'all',
			'wrapper' => [
				'width' => '50',
			],
		],

		'team_members' => [
			'label' => esc_html__('Teamleden', 'mbeffect'),
			'type' => 'post_object',
			'post_type' => [
				'team_member',
			],
			'return_format' => 'id',
			'multiple' => true,
			'conditional_logic' => [
				[
					[
						'field' => 'field_team_selection',
						'operator' => '==',
						'value' => 'specific',
					],
				],
			],
		],

	],
];
