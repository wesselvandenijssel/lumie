<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

if (function_exists('acf_add_local_field_group')) :
	acf_add_local_field_group([
		'key' => 'clone_footer_column',
		'title' => esc_html__('Kloon: Footer kolom', 'lumie'),
		'fields' => [
			[
				'key' => 'clone_footer_column_content',
				'label' => esc_html__('Footer kolom', 'lumie'),
				'name' => 'footer_column',
				'type' => 'flexible_content',
				'button_label' => esc_html__('Nieuwe contentregel', 'lumie'),
				'layouts' => [
					[
						'key' => 'clone_footer_column_content_layout_title',
						'name' => 'title',
						'label' => esc_html__('Titel', 'lumie'),
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'clone_footer_column_content_layout_title_title',
								'label' => esc_html__('Titel', 'lumie'),
								'name' => 'title',
								'type' => 'wysiwyg',
								'tabs' => 'visual',
								'toolbar' => 'title',
								'media_upload' => false,
								'delay' => true,
							],

							[
								'key' => 'clone_footer_column_content_layout_title_title_type',
								'label' => esc_html__('Type titel', 'lumie'),
								'name' => 'title_type',
								'type' => 'select',
								'wrapper' => [
									'width' => '50',
								],
								'choices' => [
									'default' => esc_html__('Standaard', 'lumie'),
									'h1' => esc_html__('h1', 'lumie'),
									'h2' => esc_html__('h2', 'lumie'),
									'h3' => esc_html__('h3', 'lumie'),
									'h4' => esc_html__('h4', 'lumie'),
								],
								'default_value' => 'h4',
								'return_format' => 'value',
							],
						],
					],

					[
						'key' => 'clone_footer_column_content_layout_image',
						'name' => 'image',
						'label' => esc_html__('Afbeelding', 'lumie'),
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'clone_footer_column_content_layout_image_image',
								'label' => esc_html__('Afbeelding', 'lumie'),
								'type' => 'clone',
								'clone' => [
									'clone_image_image_group',
								],
								'name' => 'image',

							],
						],
					],
					[
						'key' => 'clone_footer_column_content_layout_contact_details',
						'name' => 'contact_details',
						'label' => esc_html__('Contactgegevens', 'lumie'),
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'clone_footer_column_content_layout_contact_details_checkbox',
								'label' => esc_html__('Contactgegevens', 'lumie'),
								'name' => 'contact_details',
								'type' => 'checkbox',
								'choices' => [
									'phone' => esc_html__('Telefoonnummer', 'lumie'),
									'email' => esc_html__('E-mailadres', 'lumie'),
									'address' => esc_html__('Adres', 'lumie'),
								],
								'layout' => 'vertical',
								'return_format' => 'value',
							],
						],
					],
					[
						'key' => 'clone_footer_column_content_layout_social_media',
						'name' => 'social_media',
						'label' => esc_html__('Social media', 'lumie'),
						'display' => 'block',
					],
					[
						'key' => 'clone_footer_column_content_layout_menu',
						'name' => 'menu',
						'label' => esc_html__('Menu', 'lumie'),
						'display' => 'block',
						'sub_fields' => [
							[
								'key' => 'clone_footer_column_content_layout_menu_menu',
								'label' => esc_html__('Menu', 'lumie'),
								'name' => 'menu',
								'type' => 'select',
								'choices' => array_merge(
									['none' => esc_html__('Geen', 'lumie')],
									wp_get_nav_menus([
										'fields' => 'names'
									])
								),
								'return_format' => 'label',
								'wrapper' => [
									'width' => '25',
								],
							],
							[
								'key' => 'clone_footer_column_content_layout_menu_foldable',
								'label' => esc_html__('Uitklapbaar', 'lumie'),
								'name' => 'foldable',
								'type' => 'true_false',
								'ui_on_text' => esc_html__('Ja', 'lumie'),
								'ui_off_text' => esc_html__('Nee', 'lumie'),
								'ui' => true,
								'wrapper' => [
									'width' => '25',
								],
							],
							[
								'key' => 'clone_footer_column_content_layout_menu_foldable_text',
								'label' => esc_html__('"Toon meer" tekst', 'lumie'),
								'name' => 'foldable_text',
								'type' => 'text',
								'default_value' => esc_html__('Toon meer', 'lumie'),
								'wrapper' => [
									'width' => '25',
								],
								'conditional_logic' => [
									[
										[
											'field' => 'clone_footer_column_content_layout_menu_foldable',
											'operator' => '==',
											'value' => 1,
										],
									],
								],
							],
							[
								'key' => 'clone_footer_column_content_layout_menu_visible_amount',
								'label' => esc_html__('Aantal zichtbare menu-items', 'lumie'),
								'name' => 'visible_amount',
								'type' => 'number',
								'default_value' => 4,
								'wrapper' => [
									'width' => '25',
								],
								'conditional_logic' => [
									[
										[
											'field' => 'clone_footer_column_content_layout_menu_foldable',
											'operator' => '==',
											'value' => 1,
										],
									],
								],
							],
						],
					],
				],
			],
		],
	]);

endif;
