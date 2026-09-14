<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

if (function_exists('acf_add_local_field_group')) :
	acf_add_local_field_group([
		'key' => 'clone_buttons',
		'title' => esc_html__('Kloon: Button', 'lumie'),
		'fields' => [
			[
				'key' => 'clone_buttons_buttons_group',
				'label' => esc_html__('Button(s)', 'lumie'),
				'name' => 'buttons_group',
				'type' => 'flexible_content',
				'button_label' => esc_html__('Nieuwe button', 'lumie'),
				'layouts' => [
					[
						'key' => 'clone_buttons_buttons_group_primary',
						'name' => 'primary',
						'label' => esc_html__('Button - Primair', 'lumie'),
						'sub_fields' => [
							[
								'key' => 'clone_buttons_buttons_group_primary_button_primary',
								'label' => esc_html__('Button', 'lumie'),
								'name' => 'button_primary',
								'type' => 'clone',
								'clone' => [
									'clone_buttons_singular_button',
								],
								'display' => 'seamless',
								'layout' => 'block',
							],
						],
					],
					[
						'key' => 'clone_buttons_buttons_group_secondary',
						'name' => 'secondary',
						'label' => esc_html__('Button - Secundair', 'lumie'),
						'sub_fields' => [
							[
								'key' => 'clone_buttons_buttons_group_secondary_button_secondary',
								'label' => esc_html__('Button', 'lumie'),
								'name' => 'button_secondary',
								'type' => 'clone',
								'clone' => [
									'clone_buttons_singular_button',
								],
								'display' => 'seamless',
								'layout' => 'block',
							],
						],
					],
					[
						'key' => 'clone_buttons_buttons_group_tertiary',
						'name' => 'tertiary',
						'label' => esc_html__('Button - Tertiair', 'lumie'),
						'sub_fields' => [
							[
								'key' => 'clone_buttons_buttons_group_tertiary_button_tertiary',
								'label' => esc_html__('Button', 'lumie'),
								'name' => 'button_tertiary',
								'type' => 'clone',
								'clone' => [
									'clone_buttons_singular_button',
								],
								'display' => 'seamless',
								'layout' => 'block',
							],
						],
					],
					[
						'key' => 'clone_buttons_buttons_group_phone',
						'name' => 'phone',
						'label' => esc_html__('Telefoonnummer', 'lumie'),
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'clone_buttons_buttons_group_phone_title_attr',
								'label' => esc_html__('Title attribuut', 'lumie'),
								'name' => 'title_attr',
								'type' => 'text',
							],
						],
					],
				]
			],
			[
				'key' => 'clone_buttons_singular_button',
				'label' => esc_html__('Button', 'lumie'),
				'name' => 'singular_button',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'clone_buttons_singular_button_display',
						'label' => esc_html__('Weergave', 'lumie'),
						'name' => 'display',
						'type' => 'select',
						'choices' => [
							'always' => esc_html__('Altijd', 'lumie'),
							'phone' => esc_html__('Alleen op telefoon', 'lumie'),
							'desktop' => esc_html__('Alleen op desktop', 'lumie'),
						],
						'wrapper' => [
							'width' => '50',
						],
					],
					[
						'key' => 'clone_buttons_singular_button_button_type',
						'label' => esc_html__('Type', 'lumie'),
						'name' => 'button_type',
						'type' => 'select',
						'wrapper' => [
							'width' => '50',
						],
						'choices' => [
							'link' => 'link',
							'popup' => 'popup',
						],
						'return_format' => 'value',
					],
					[
						'key' => 'clone_buttons_singular_button_button_text',
						'label' => esc_html__('Tekst', 'lumie'),
						'name' => 'button_text',
						'type' => 'text',
						'wrapper' => [
							'width' => '50',
						],
					],
					[
						'key' => 'clone_buttons_singular_button_button_link',
						'label' => esc_html__('Link', 'lumie'),
						'name' => 'button_link',
						'type' => 'link',
						'conditional_logic' => [
							[
								[
									'field' => 'clone_buttons_singular_button_button_type',
									'operator' => '==',
									'value' => 'link',
								],
							],
						],
						'wrapper' => [
							'width' => '50',
						],
						'return_format' => 'array',
					],
					[
						'key' => 'clone_buttons_singular_button_button_popup',
						'label' => esc_html__('Popup', 'lumie'),
						'name' => 'button_popup',
						'type' => 'select',
						'conditional_logic' => [
							[
								[
									'field' => 'clone_buttons_singular_button_button_type',
									'operator' => '==',
									'value' => 'popup',
								],
							],
						],
						'wrapper' => [
							'width' => '33',
						],
						'choices' => [],
						'return_format' => 'value',
					],
					[
						'key' => 'clone_buttons_singular_button_icon_before',
						'label' => esc_html__('Icoon voor', 'lumie'),
						'name' => 'icon_before',
						'type' => 'font-awesome',
						'custom_icon_set' => 'ACFFA_custom_icon_list_v6_Icons',
						'icon_sets' => [
							'custom',
						],
						'return_format' => 'value',
						'wrapper' => [
							'width' => '50',
						],
					],
					[
						'key' => 'clone_buttons_singular_button_icon_after',
						'label' => esc_html__('Icoon na', 'lumie'),
						'name' => 'icon_after',
						'type' => 'font-awesome',
						'custom_icon_set' => 'ACFFA_custom_icon_list_v6_Icons',
						'icon_sets' => [
							'custom',
						],
						'return_format' => 'value',
						'wrapper' => [
							'width' => '50',
						],
					],
				],
			],
		],
	]);
endif;
