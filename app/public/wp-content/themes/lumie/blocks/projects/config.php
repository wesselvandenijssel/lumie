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

		get_flex_content('field_projects_content'),

		'selection' => [
			'label' => esc_html__('Selectie', 'mbeffect'),
			'type' => 'button_group',
			'choices' => [
				'newest' => esc_html__('Nieuwste', 'mbeffect'),
				'random' => esc_html__('Willekeurig', 'mbeffect'),
				'specific' => esc_html__('Specifiek', 'mbeffect'),
			],
			'wrapper' => [
				'width' => '50',
			],
		],

		'projects' => [
			'label' => esc_html__('Projecten', 'mbeffect'),
			'type' => 'post_object',
			'post_type' => [
				'project',
			],
			'return_format' => 'id',
			'multiple' => true,
			'conditional_logic' => [
				[
					[
						'field' => 'field_projects_selection',
						'operator' => '==',
						'value' => 'specific',
					],
				],
			],
		],

		'amount' => [
			'label' => esc_html__('Aantal', 'mbeffect'),
			'instructions' => wp_kses_post(__('-1 voor alle projecten', 'mbeffect')),
			'type' => 'number',
			'default_value' => 3,
			'conditional_logic' => [
				[
					[
						'field' => 'field_projects_selection',
						'operator' => '==',
						'value' => 'newest',
					],
				],
				[
					[
						'field' => 'field_projects_selection',
						'operator' => '==',
						'value' => 'random',
					],
				],
			],
		],

		'buttons' => [
			'label' => esc_html__('Button(s)', 'mbeffect'),
			'type' => 'clone',
			'clone' => [
				'clone_buttons_buttons_group',
			],
			'display' => 'seamless',
		],
	],
];
