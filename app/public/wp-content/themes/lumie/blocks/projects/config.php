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

		get_flex_content('field_projects_content'),

		'selection' => [
			'label' => esc_html__('Selectie', 'lumie'),
			'type' => 'button_group',
			'choices' => [
				'newest' => esc_html__('Nieuwste', 'lumie'),
				'random' => esc_html__('Willekeurig', 'lumie'),
				'specific' => esc_html__('Specifiek', 'lumie'),
			],
			'wrapper' => [
				'width' => '50',
			],
		],

		'projects' => [
			'label' => esc_html__('Projecten', 'lumie'),
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
			'label' => esc_html__('Aantal', 'lumie'),
			'instructions' => wp_kses_post(__('-1 voor alle projecten', 'lumie')),
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
			'label' => esc_html__('Button(s)', 'lumie'),
			'type' => 'clone',
			'clone' => [
				'clone_buttons_buttons_group',
			],
			'display' => 'seamless',
		],
	],
];
