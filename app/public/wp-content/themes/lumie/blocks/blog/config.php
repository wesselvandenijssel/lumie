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
				'category' => esc_html__('Categorie', 'mbeffect'),
			],
			'wrapper' => [
				'width' => '50',
			],
		],

		'posts' => [
			'label' => esc_html__('Berichten', 'mbeffect'),
			'type' => 'post_object',
			'post_type' => [
				'post',
			],
			'return_format' => 'id',
			'multiple' => true,
			'conditional_logic' => [
				[
					[
						'field' => 'field_blog_selection',
						'operator' => '==',
						'value' => 'specific',
					],
				],
			],
		],

		'category' => [
			'label' => esc_html__('Categorie', 'mbeffect'),
			'type' => 'taxonomy',
			'taxonomy' => 'category',
			'field_type' => 'select',
			'return_format' => 'id',
			'conditional_logic' => [
				[
					[
						'field' => 'field_blog_selection',
						'operator' => '==',
						'value' => 'category',
					],
				],
			],
		],

		'amount' => [
			'label' => esc_html__('Aantal', 'mbeffect'),
			'instructions' => wp_kses_post(__('-1 voor alle berichten', 'mbeffect')),
			'type' => 'number',
			'default_value' => 3,
			'conditional_logic' => [
				[
					[
						'field' => 'field_blog_selection',
						'operator' => '==',
						'value' => 'newest',
					],
				],
				[
					[
						'field' => 'field_blog_selection',
						'operator' => '==',
						'value' => 'random',
					],
				],
			],
		],
	],
];
