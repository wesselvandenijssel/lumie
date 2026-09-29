<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'sparkles' => [
			'label' => esc_html__('Sparkles', 'lumie'),
			'instructions' => esc_html__('Laat sparkles op verschillende plekken in de achtergrond verschijnen en weer verdwijnen.', 'lumie'),
			'type' => 'true_false',
			'ui' => true,
		],

	],
];
