<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'lumie'),
			'type' => 'accordion',
		],

		get_flex_content('field_content_columns_left', 'content_left', esc_html__('Linker kolom', 'lumie')),

		get_flex_content('field_content_columns_right', 'content_right', esc_html__('Rechter kolom', 'lumie')),

	],
];
