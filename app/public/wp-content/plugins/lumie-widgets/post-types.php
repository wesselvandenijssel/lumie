<?php

defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Adds the `mbwidget` custom post type
 */
function mbw_add_widget_post_type() {
	register_post_type(
		'mbwidget', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		[
			'labels' => [
				'name' => __('Widgets', 'mb-widget'),
				'singular_name' => __('Widget', 'mb-widget'),
				'all_items' => __('Alle widgets', 'mb-widget'),
				'add_new' => __('Nieuwe widget', 'mb-widget'),
				'add_new_item' => __('Nieuwe widget', 'mb-widget'),
				'edit' => __('Bewerken', 'mb-widget'),
				'edit_item' => __('Widget bewerken', 'mb-widget'),
				'new_item' => __('Nieuwe widget', 'mb-widget'),
				'view_item' => __('Widgets bekijken', 'mb-widget'),
				'search_items' => __('Widgets zoeken', 'mb-widget'),
				'not_found' =>  __('Geen widgets gevonden.', 'mb-widget'),
				'not_found_in_trash' => __('Geen widgets gevonden', 'mb-widget'),
				'parent_item_colon' => ''
			],
			'description' => __('', 'mb-widget'),
			'public' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 9,
			'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg height="12.023844mm" viewBox="0 0 25.385353 12.023844" width="25.385353mm" xmlns="http://www.w3.org/2000/svg"><g fill="#fff" stroke-width=".264583" transform="translate(-288.01821 -242.64453)"><path d="m299.60695 246.66613c-1.19063 0-2.27542.60854-2.8575 1.40229-.3175-.79375-1.37584-1.37583-2.48709-1.37583-.0265 0-.0265 0-.0529 0-.0794 0-.15875-.0265-.23813-.0265-1.19062 0-2.40771.635-2.98979 1.42875-.0265-.0794-.0794-.13229-.13229-.15875-.23813-.39688-.47625-.55563-.47625-.55563-1.05833-.97895-2.35479-.84666-2.35479-.84666v1.98437c.635-.10583.97896.29104 1.16416.68792.1323.29104.21167.60854.21167.89958v.58209 3.81h1.87854.39688v-3.91584c0-1.03187.66146-1.95791 1.77271-1.95791.92604 0 1.42875.66145 1.42875 1.98437v3.88938h2.43416v-3.91584c0-1.03187.66146-1.95791 1.77271-1.95791.92604 0 1.42875.66145 1.42875 1.98437v3.88938h2.27542v-4.20688c0-2.2225-.92604-3.62479-3.175-3.62479z"/><path d="m312.01591 247.61863c-.97896-.84667-2.27542-1.24354-3.54542-1.00542-.3175.0529-.55563.13229-.76729.21167v2.03729c1.16416-.47625 2.14312-.0529 2.14312-.0529.74084.3175 1.05834.82021 1.13771 1.29646.15875.635-.0794 1.32292-.52917 1.79917-.39687.42333-.87312.58208-1.29645.635-1.19063.13229-2.2225-.68792-2.38125-1.85208-.0529-.37042-.0265-.635-.0265-.635l-.0265.0265v-7.43479h-2.54l.0265 7.85813c-.0265 2.67229 2.51354 3.73062 2.51354 3.73062 1.905.97896 3.99521 0 3.99521 0 3.01625-1.34937 2.67229-3.86291 2.67229-3.86291-.0794-1.27-.71438-2.16959-1.37583-2.75167z"/></g></svg>'),
			'rewrite' => ['slug' => 'widget', 'with_front' => false],
			'has_archive' => false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'show_in_rest' => true,
			'supports' => [
				'title',
			]
		]
	);
}
add_action('init', 'mbw_add_widget_post_type');


/**
 * Adds the custom fields to the post type
 */
function mbw_add_widget_custom_fields() {
	if (!function_exists('acf_add_local_field_group') || !function_exists('acf_add_options_page')) return;

	$post_types = mbw_get_post_types();

	// Post type cf's
	acf_add_local_field_group([
		'key' => 'mbw_settings',
		'title' => __('Widget instellingen', 'mb-widget'),
		'fields' => [
			[
				'key' => 'mbw_settings_image',
				'name' => 'image',
				'label' => __('Afbeelding', 'mb-widget'),
				'instructions' => __('De afbeelding wordt als 40px bij 40px weergegeven, dus dit formaat wordt aangeraden om te uploaden.', 'mb-widget'),
				'type' => 'image',
				'mime_format' => 'webp,png,jpg,jpeg',
				'return_format' => 'id',
			],
			[
				'key' => 'mbw_settings_welcome_text',
				'name' => 'welcome_text',
				'label' => __('Welkomsttekst', 'mb-widget'),
				'type' => 'wysiwyg',
				'delay' => true,
				'media_upload' => false,
			],

			mbw_button_cfs('mbw_settings_'),

			[
				'key' => 'mbw_settings_display',
				'label' => __('Weergave', 'mb-widget'),
				'name' => 'display',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'mbw_settings_display_post_types',
						'label' => __('Post types', 'mb-widget'),
						'name' => 'post_types',
						'type' => 'select',
						'ui' => true,
						'multiple' => true,
						'choices' => $post_types,
					],

					// Shows a conditonal post type field per post type
					...array_map(
						fn($label, $post_type) => mbw_conditional_post_type($post_type, $label),
						$post_types,
						array_keys($post_types)
					),
				],
			],
			[
				'key' => 'mbw_settings_open',
				'label' => __('Automatisch openen', 'mb-widget'),
				'name' => 'open',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'mbw_settings_open_type',
						'label' => __('Type', 'mb-widget'),
						'name' => 'type',
						'type' => 'radio',
						'choices' => [
							'none' => __('Niet', 'mb-widget'),
							'page_visits' => __('Na aantal pagina bezoeken', 'mb-widget'),
							'scroll_percentage' => __('Na percentage scrollen', 'mb-widget'),
							'seconds' => __('Na aantal seconden', 'mb-widget'),
						],
						'default_value' => 'none',
						'wrapper' => [
							'width' => '25',
						],
					],
					[
						'key' => 'mbw_settings_open_page_visits',
						'label' => __('Aantal pagina bezoeken', 'mb-widget'),
						'name' => 'page_visits',
						'type' => 'number',
						'min' => 1,
						'conditional_logic' => [
							[
								[
									'field' => 'mbw_settings_open_type',
									'operator' => '==',
									'value' => 'page_visits',
								],
							],
						],
						'wrapper' => [
							'width' => '25',
						],
					],
					[
						'key' => 'mbw_settings_open_scroll_percentage',
						'label' => __('Scroll percentage', 'mb-widget'),
						'name' => 'scroll_percentage',
						'type' => 'number',
						'min' => 0,
						'max' => 100,
						'append' => '%',
						'conditional_logic' => [
							[
								[
									'field' => 'mbw_settings_open_type',
									'operator' => '==',
									'value' => 'scroll_percentage',
								],
							],
						],
						'wrapper' => [
							'width' => '25',
						],
					],
					[
						'key' => 'mbw_settings_open_seconds',
						'label' => __('Aantal seconden', 'mb-widget'),
						'name' => 'seconds',
						'type' => 'number',
						'min' => 0,
						'conditional_logic' => [
							[
								[
									'field' => 'mbw_settings_open_type',
									'operator' => '==',
									'value' => 'seconds',
								],
							],
						],
						'wrapper' => [
							'width' => '25',
						],
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'mbwidget',
				],
			],
		],
	]);

	// General settings cf's
	acf_add_options_page([
		'page_title' 	=> __('Instellingen', 'mb-widget'),
		'menu_title'	=> __('Instellingen', 'mb-widget'),
		'menu_slug' 	=> 'mbwidget',
		'post_id' 		=> 'mbwidget',
		'parent_slug'	=> 'edit.php?post_type=mbwidget',
	]);

	acf_add_local_field_group([
		'key' => 'mbw_general_settings',
		'title' => __('Algemene instellingen', 'mb-widget'),
		'fields' => [
			[
				'key' => 'mbw_general_settings_license',
				'name' => 'license',
				'label' => __('Licentie', 'mb-widget'),
				'type' => 'text',
			],
			[
				'key' => 'mbw_general_settings_button_primary',
				'name' => 'button_primary',
				'label' => __('Primaire button', 'mb-widget'),
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'mbw_general_settings_button_primary_color',
						'name' => 'color',
						'label' => __('Tekst kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(255,255,255,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_primary_background_color',
						'name' => 'background_color',
						'label' => __('Achtergrond kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(0,0,0,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_primary_border_color',
						'name' => 'border_color',
						'label' => __('Border kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(0,0,0,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_primary_color_hover',
						'name' => 'color_hover',
						'label' => __('Tekst hover kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(0,0,0,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_primary_background_color_hover',
						'name' => 'background_color_hover',
						'label' => __('Achtergrond hover kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(255,255,255,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_primary_border_color_hover',
						'name' => 'border_color_hover',
						'label' => __('Border hover kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(0,0,0,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
				],
			],
			[
				'key' => 'mbw_general_settings_button_secondary',
				'name' => 'button_secondary',
				'label' => __('Secundaire button', 'mb-widget'),
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'mbw_general_settings_button_secondary_color',
						'name' => 'color',
						'label' => __('Tekst kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(0,0,0,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_secondary_background_color',
						'name' => 'background_color',
						'label' => __('Achtergrond kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(255,255,255,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_secondary_border_color',
						'name' => 'border_color',
						'label' => __('Border kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(0,0,0,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_secondary_color_hover',
						'name' => 'color_hover',
						'label' => __('Tekst hover kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(255,255,255,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_secondary_background_color_hover',
						'name' => 'background_color_hover',
						'label' => __('Achtergrond hover kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(0,0,0,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
					[
						'key' => 'mbw_general_settings_button_secondary_border_color_hover',
						'name' => 'border_color_hover',
						'label' => __('Border hover kleur', 'mb-widget'),
						'type' => 'color_picker',
						'default_value' => 'rgba(0,0,0,1)',
						'enable_opacity' => true,
						'return_format' => 'string',
						'wrapper' => [
							'width' => '16.5',
						],
					],
				],
			],
		],

		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'mbwidget',
				],
			],
		],
	]);
}
add_action('init', 'mbw_add_widget_custom_fields', 15);

function mbw_conditional_post_type($post_type, $post_type_label) {
	return [
		'key' => 'mbw_settings_display_' . $post_type,
		'label' => sprintf(__('%s berichttype', 'mb-widget'), $post_type_label),
		'name' => $post_type,
		'type' => 'group',
		'sub_fields' => [
			[
				'key' => 'mbw_settings_display_' . $post_type . '_display',
				'label' => sprintf(__('%s weergave', 'mb-widget'), $post_type_label),
				'name' => 'display',
				'type' => 'radio',
				'choices' => [
					'everywhere' => __('Overal weergeven', 'mb-widget'),
					'hide_on' => __('Overal weergeven, behalve …', 'mb-widget'),
					'show_on' => __('Alleen weergeven op …', 'mb-widget'),
				],
				'wrapper' => [
					'width' => '50',
				],
			],
			[
				'key' => 'mbw_settings_display_' . $post_type . '_hide',
				'label' => __('Overal weergeven, behalve …', 'mb-widget'),
				'name' => 'hide',
				'type' => 'post_object',
				'post_type' => [$post_type],
				'multiple' => true,
				'return_format' => 'id',
				'wrapper' => [
					'width' => '50',
				],
				'conditional_logic' => [
					[
						[
							'field' => 'mbw_settings_display_' . $post_type . '_display',
							'operator' => '==',
							'value' => 'hide_on',
						],
					],
				],
			],
			[
				'key' => 'mbw_settings_display_' . $post_type . '_show',
				'label' => __('Alleen weergeven op …', 'mb-widget'),
				'name' => 'show',
				'type' => 'post_object',
				'post_type' => [$post_type],
				'multiple' => true,
				'return_format' => 'id',
				'wrapper' => [
					'width' => '50',
				],
				'conditional_logic' => [
					[
						[
							'field' => 'mbw_settings_display_' . $post_type . '_display',
							'operator' => '==',
							'value' => 'show_on',
						],
					],
				],
			],
		],
		'conditional_logic' => [
			[
				[
					'field' => 'mbw_settings_display_post_types',
					'operator' => '==contains',
					'value' => $post_type,
				],
			],
		],
	];
}

function mbw_button_cfs(string $key_prefix, int $depth = 1): array {
	$max_depth = 3;

	return [
		'key' => $key_prefix . 'buttons',
		'label' => __('Buttons', 'mb-widget'),
		'name' => 'buttons',
		'type' => 'repeater',
		'collapsed' => $key_prefix . 'buttons_text',
		'button_label' => __('Nieuwe button', 'mb-widget'),
		'layout' => 'block',
		'sub_fields' => [
			[
				'key' => $key_prefix . 'buttons_text',
				'label' => __('Button tekst', 'mb-widget'),
				'name' => 'text',
				'type' => 'text',
				'wrapper' => [
					'width' => '20',
				],
			],
			[
				'key' => $key_prefix . 'buttons_id',
				'label' => __('ID-attribuut', 'mb-widget'),
				'name' => 'id',
				'type' => 'text',
				'wrapper' => [
					'width' => '20',
				],
			],
			[
				'key' => $key_prefix . 'buttons_type',
				'label' => __('Type', 'mb-widget'),
				'name' => 'type',
				'type' => 'select',
				'choices' => [
					'primary' => __('Primair', 'mb-widget'),
					'secondary' => __('Secundair', 'mb-widget'),
				],
				'default' => 'primary',
				'wrapper' => [
					'width' => '20',
				],
			],
			[
				'key' => $key_prefix . 'buttons_action',
				'label' => __('Actie', 'mb-widget'),
				'name' => 'action',
				'type' => 'select',
				'choices' => array_merge(
					[
						'link' => __('Link', 'mb-widget'),
						'content' => __('Content weergeven', 'mb-widget'),
						'click-event' => __('Aangepast klik-event', 'mb-widget'),
					],
					$depth < $max_depth ? ['buttons' => __('Button(s) weergeven', 'mb-widget')] : []
				),
				'default' => 'link',
				'wrapper' => [
					'width' => '20',
				],
			],
			[
				'key' => $key_prefix . 'buttons_link',
				'label' => __('Link', 'mb-widget'),
				'name' => 'link',
				'type' => 'link',
				'conditional_logic' => [
					[
						[
							'field' => $key_prefix . 'buttons_action',
							'operator' => '==',
							'value' => 'link',
						],
					],
				],
				'wrapper' => [
					'width' => '20',
				],
			],
			[
				'key' => $key_prefix . 'buttons_content',
				'label' => __('Content', 'mb-widget'),
				'name' => 'content',
				'type' => 'wysiwyg',
				'delay' => true,
				'media_upload' => false,
				'conditional_logic' => [
					[
						[
							'field' => $key_prefix . 'buttons_action',
							'operator' => '==',
							'value' => 'content',
						],
					],
				],
			],
			[
				'key' => $key_prefix . 'buttons_recursive',
				'label' => __('Geneste buttons', 'mb-widget'),
				'name' => 'recursive',
				'type' => 'group',
				'sub_fields' => array_filter([
					[
						'key' => $key_prefix . 'buttons_recursive_content',
						'label' => __('Content', 'mb-widget'),
						'name' => 'content',
						'type' => 'wysiwyg',
						'delay' => true,
						'media_upload' => false,
					],
					$depth < $max_depth ? mbw_button_cfs($key_prefix . 'buttons_recursive_buttons', $depth + 1) : null,
				]),
				'conditional_logic' => [
					[
						[
							'field' => $key_prefix . 'buttons_action',
							'operator' => '==',
							'value' => 'buttons',
						],
					],
				],
			],
		],
	];
}
