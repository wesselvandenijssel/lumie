<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

if (function_exists('acf_add_local_field_group')) :
	acf_add_local_field_group([
		'key' => 'settings_popup',
		'title' => esc_html__('Pop-up instellingen', 'mbeffect'),
		'fields' => [

			get_flex_content('settings_popup_content'),

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
