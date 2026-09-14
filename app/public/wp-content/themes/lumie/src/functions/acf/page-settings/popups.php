<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

if (function_exists('acf_add_local_field_group')) :
	acf_add_local_field_group([
		'key' => 'settings_popup',
		'title' => esc_html__('Pop-up instellingen', 'lumie'),
		'fields' => [

			get_flex_content('settings_popup_content'),

			[
				'key' => 'settings_popup_startup',
				'label' => esc_html__('Opstart pop-up', 'lumie'),
				'instructions' => esc_html__('Toon deze pop-up automatisch bij het openen van de website. De bezoeker krijgt hem niet opnieuw te zien zodra hij hem wegklikt.', 'lumie'),
				'name' => 'startup',
				'type' => 'true_false',
				'ui' => true,
			],

			[
				'key' => 'settings_popup_age_gate',
				'label' => esc_html__('Leeftijdscheck (18+)', 'lumie'),
				'instructions' => esc_html__('De bezoeker moet zijn geboortedatum invullen voordat hij de site in mag. Deze pop-up is dan niet weg te klikken en verschijnt direct, dus de vertraging hieronder wordt genegeerd. Verplicht volgens de Reclamecode voor Alcoholhoudende Dranken.', 'lumie'),
				'name' => 'age_gate',
				'type' => 'true_false',
				'ui' => true,
				'conditional_logic' => [
					[
						[
							'field' => 'settings_popup_startup',
							'operator' => '==',
							'value' => '1',
						],
					],
				],
			],

			[
				'key' => 'settings_popup_startup_delay',
				'label' => esc_html__('Vertraging', 'lumie'),
				'instructions' => esc_html__('Aantal seconden voordat de pop-up verschijnt.', 'lumie'),
				'name' => 'startup_delay',
				'type' => 'number',
				'default_value' => 3,
				'min' => 0,
				'max' => 60,
				'append' => esc_html__('sec.', 'lumie'),
				'wrapper' => [
					'width' => '50',
				],
				'conditional_logic' => [
					[
						[
							'field' => 'settings_popup_startup',
							'operator' => '==',
							'value' => '1',
						],
					],
				],
			],

			[
				'key' => 'settings_popup_startup_day',
				'label' => esc_html__('Alleen op deze dagen', 'lumie'),
				'instructions' => esc_html__('Laat leeg om de pop-up elke dag te tonen.', 'lumie'),
				'name' => 'startup_day',
				'type' => 'checkbox',
				'multiple' => true,
				'allow_null' => true,
				'choices' => [
					'1' => esc_html__('Maandag', 'lumie'),
					'2' => esc_html__('Dinsdag', 'lumie'),
					'3' => esc_html__('Woensdag', 'lumie'),
					'4' => esc_html__('Donderdag', 'lumie'),
					'5' => esc_html__('Vrijdag', 'lumie'),
					'6' => esc_html__('Zaterdag', 'lumie'),
					'0' => esc_html__('Zondag', 'lumie'),
				],
				'wrapper' => [
					'width' => '50',
				],
				'conditional_logic' => [
					[
						[
							'field' => 'settings_popup_startup',
							'operator' => '==',
							'value' => '1',
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
					'value' => 'popup',
				],
			],
		],
	]);
endif;
