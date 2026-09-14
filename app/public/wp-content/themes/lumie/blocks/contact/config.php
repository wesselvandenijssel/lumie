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

		'forms' => [
			'label' => esc_html__('Formulieren', 'mbeffect'),
			'type' => 'repeater',
			'button_label' => esc_html__('Nieuw formulier', 'mbeffect'),
			'sub_fields' => [
				[
					'key' => 'field_contact_forms_name',
					'name' => 'name',
					'label' => esc_html__('Naam', 'mbeffect'),
					'type' => 'text',
				],
				[
					'key' => 'field_contact_forms_form',
					'name' => 'form',
					'label' => esc_html__('Formulier', 'mbeffect'),
					'instructions' => wp_kses_post(__('Selecteer hier het formulier', 'mbeffect')),
					'type' => 'select',
					'choices' => get_all_forms(),
				],
			],
		],

		'selection' => [
			'label' => esc_html__('Selectie', 'mbeffect'),
			'type' => 'radio',
			'choices' => [
				'none' => esc_html__('Geen', 'mbeffect'),
				'contact_details' => esc_html__('Contactgegevens', 'mbeffect'),
				'content' => esc_html__('Content', 'mbeffect'),
			],
		],

		'order' => [
			'label' => esc_html__('Volgorde', 'mbeffect'),
			'type' => 'true_false',
			'ui_on_text' => esc_html__('Formulier links, content rechts', 'mbeffect'),
			'ui_off_text' => esc_html__('Formulier rechts, content links', 'mbeffect'),
			'ui' => true,
			'conditional_logic' => [
				[
					[
						'field' => 'field_contact_selection',
						'operator' => '!=',
						'value' => 'none',
					],
				],
			],
		],

		'content' => [
			'label' => esc_html__('Content', 'mbeffect'),
			'type' => 'group',
			'sub_fields' => [
				get_flex_content('field_contact_content_content'),
			],
			'conditional_logic' => [
				[
					[
						'field' => 'field_contact_selection',
						'operator' => '==',
						'value' => 'contact_details',
					],
				],
				[
					[
						'field' => 'field_contact_selection',
						'operator' => '==',
						'value' => 'content',
					],
				],
			],
		],

	],
];
