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

		'vacancies' => [
			'label' => esc_html__('Vacatures', 'mbeffect'),
			'type' => 'post_object',
			'post_type' => [
				'vacancy',
			],
			'return_format' => 'id',
			'multiple' => true,
			'conditional_logic' => [
				[
					[
						'field' => 'field_vacancies_selection',
						'operator' => '==',
						'value' => 'specific',
					],
				],
			],
		],

		'amount' => [
			'label' => esc_html__('Aantal', 'mbeffect'),
			'instructions' => esc_html__('-1 voor alle vacatures', 'mbeffect'),
			'type' => 'number',
			'default_value' => 3,
			'conditional_logic' => [
				[
					[
						'field' => 'field_vacancies_selection',
						'operator' => '==',
						'value' => 'newest',
					],
				],
				[
					[
						'field' => 'field_vacancies_selection',
						'operator' => '==',
						'value' => 'random',
					],
				],
			],
		],
	],
];
