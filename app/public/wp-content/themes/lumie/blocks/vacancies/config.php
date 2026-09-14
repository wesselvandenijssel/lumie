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

		'vacancies' => [
			'label' => esc_html__('Vacatures', 'lumie'),
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
			'label' => esc_html__('Aantal', 'lumie'),
			'instructions' => esc_html__('-1 voor alle vacatures', 'lumie'),
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
