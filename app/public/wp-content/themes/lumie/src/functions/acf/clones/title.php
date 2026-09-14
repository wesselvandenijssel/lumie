<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.


if (function_exists('acf_add_local_field_group')) :

	acf_add_local_field_group([
		'key' => 'clone_titles',
		'title' => esc_html__('Kloon: Titel', 'lumie'),
		'fields' => [
			[
				'key' => 'clone_titles_block_title',
				'label' => esc_html__('Titel', 'lumie'),
				'name' => 'block_title',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'clone_titles_block_title_suptitle',
						'label' => esc_html__('Boventitel', 'lumie'),
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
						'label' => esc_html__('Titel', 'lumie'),
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
						'label' => esc_html__('Ondertitel', 'lumie'),
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
						'label' => esc_html__('Type titel', 'lumie'),
						'name' => 'type',
						'type' => 'select',
						'wrapper' => [
							'width' => '50',
						],
						'choices' => [
							'default' => esc_html__('Standaard', 'lumie'),
							'h1' => esc_html__('h1', 'lumie'),
							'h2' => esc_html__('h2', 'lumie'),
							'h3' => esc_html__('h3', 'lumie'),
						],
						'default_value' => 'default',
						'return_format' => 'value',
					],
				],
			],
		],
	]);
endif;
