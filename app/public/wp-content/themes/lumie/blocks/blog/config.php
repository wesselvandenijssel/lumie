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
				'category' => esc_html__('Categorie', 'lumie'),
			],
			'wrapper' => [
				'width' => '50',
			],
		],

		'posts' => [
			'label' => esc_html__('Berichten', 'lumie'),
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
			'label' => esc_html__('Categorie', 'lumie'),
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
			'label' => esc_html__('Aantal', 'lumie'),
			'instructions' => wp_kses_post(__('-1 voor alle berichten', 'lumie')),
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
