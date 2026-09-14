<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

if (function_exists('acf_add_local_field_group')) :
	acf_add_local_field_group([
		'key' => 'clone_buttons',
		'title' => esc_html__('Kloon: Button', 'mbeffect'),
		'fields' => [
			[
				'key' => 'clone_buttons_buttons_group',
				'label' => esc_html__('Button(s)', 'mbeffect'),
				'name' => 'buttons_group',
				'type' => 'flexible_content',
				'button_label' => esc_html__('Nieuwe button', 'mbeffect'),
				'layouts' => [
					[
						'key' => 'clone_buttons_buttons_group_primary',
						'name' => 'primary',
						'label' => esc_html__('Button - Primair', 'mbeffect'),
						'sub_fields' => [
							[
								'key' => 'clone_buttons_buttons_group_primary_button_primary',
								'label' => esc_html__('Button', 'mbeffect'),
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
						'label' => esc_html__('Button - Secundair', 'mbeffect'),
						'sub_fields' => [
							[
								'key' => 'clone_buttons_buttons_group_secondary_button_secondary',
								'label' => esc_html__('Button', 'mbeffect'),
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
						'key' => 'clone_buttons_buttons_group_phone',
						'name' => 'phone',
						'label' => esc_html__('Telefoonnummer', 'mbeffect'),
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'clone_buttons_buttons_group_phone_title_attr',
								'label' => esc_html__('Title attribuut', 'mbeffect'),
								'name' => 'title_attr',
								'type' => 'text',
							],
						],
					],
				]
			],
			[
				'key' => 'clone_buttons_singular_button',
				'label' => esc_html__('Button', 'mbeffect'),
				'name' => 'singular_button',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'clone_buttons_singular_button_display',
						'label' => esc_html__('Weergave', 'mbeffect'),
						'name' => 'display',
						'type' => 'select',
						'choices' => [
							'always' => esc_html__('Altijd', 'mbeffect'),
							'phone' => esc_html__('Alleen op telefoon', 'mbeffect'),
							'desktop' => esc_html__('Alleen op desktop', 'mbeffect'),
						],
						'wrapper' => [
							'width' => '50',
						],
					],
					[
						'key' => 'clone_buttons_singular_button_button_type',
						'label' => esc_html__('Type', 'mbeffect'),
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
						'label' => esc_html__('Tekst', 'mbeffect'),
						'name' => 'button_text',
						'type' => 'text',
						'wrapper' => [
							'width' => '50',
						],
					],
					[
						'key' => 'clone_buttons_singular_button_button_link',
						'label' => esc_html__('Link', 'mbeffect'),
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
						'label' => esc_html__('Popup', 'mbeffect'),
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
						'label' => esc_html__('Icoon voor', 'mbeffect'),
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
						'label' => esc_html__('Icoon na', 'mbeffect'),
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
