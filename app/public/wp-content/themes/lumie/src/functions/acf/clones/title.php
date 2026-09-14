<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.


if (function_exists('acf_add_local_field_group')) :

	acf_add_local_field_group([
		'key' => 'clone_titles',
		'title' => esc_html__('Kloon: Titel', 'mbeffect'),
		'fields' => [
			[
				'key' => 'clone_titles_block_title',
				'label' => esc_html__('Titel', 'mbeffect'),
				'name' => 'block_title',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'clone_titles_block_title_suptitle',
						'label' => esc_html__('Boventitel', 'mbeffect'),
						'name' => 'suptitle',
						'type' => 'wysiwyg',
						'tabs' => 'visual',
						'toolbar' => 'title',
						'delay' => true,
						'media_upload' => false,
						'wrapper' => [
							'width' => '50',
						],
					],
					[
						'key' => 'clone_titles_block_title_main_title',
						'label' => esc_html__('Titel', 'mbeffect'),
						'name' => 'main_title',
						'type' => 'wysiwyg',
						'tabs' => 'visual',
						'toolbar' => 'title',
						'media_upload' => false,
						'delay' => true,
						'wrapper' => [
							'width' => '50',
						],
					],
					[
						'key' => 'clone_titles_block_title_subtitle',
						'label' => esc_html__('Ondertitel', 'mbeffect'),
						'name' => 'subtitle',
						'type' => 'wysiwyg',
						'tabs' => 'visual',
						'toolbar' => 'title',
						'delay' => true,
						'media_upload' => false,
						'wrapper' => [
							'width' => '50',
						],
					],
					[
						'key' => 'clone_titles_block_title_group_title_type',
						'label' => esc_html__('Type titel', 'mbeffect'),
						'name' => 'type',
						'type' => 'select',
						'wrapper' => [
							'width' => '50',
						],
						'choices' => [
							'default' => esc_html__('Standaard', 'mbeffect'),
							'h1' => esc_html__('h1', 'mbeffect'),
							'h2' => esc_html__('h2', 'mbeffect'),
							'h3' => esc_html__('h3', 'mbeffect'),
						],
						'default_value' => 'default',
						'return_format' => 'value',
					],
				],
			],
		],
	]);
endif;
