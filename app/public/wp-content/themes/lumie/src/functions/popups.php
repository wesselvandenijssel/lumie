<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

function load_popup_names($field) {
	$field['choices'] = [];

	$args = [
		'post_type' => 'popup',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'name',
		'order' => 'ASC',
	];
	$query = new WP_Query($args);

	if ($query->have_posts()) :
		while ($query->have_posts()) : $query->the_post();
			$value = get_the_ID();
			$label = get_the_title();
			$field['choices'][$value] = $label;
		endwhile;
	endif;

	wp_reset_query();

	return $field;
}
add_filter('acf/load_field/name=button_popup', 'load_popup_names');

function add_global_popup_var() {
	global $popups;
	$popups = [];
}
add_action('after_setup_theme', 'add_global_popup_var');

function add_global_popup($popup_id) {
	global $popups;

	if (empty($popup_id) || in_array($popup_id, $popups))
		return;

	$popups[] = $popup_id;
}

function footer_popups() {
	global $popups;

	if (empty($popups))
		return;

	foreach ($popups as $popup_id) {
		$popup_object = new BlockPopup($popup_id);
		$popup_object->get_popup();
	}
}

function popup_shortcode($atts, $content = null) {
	if (!empty($atts['popup_id'])) {
		add_global_popup($atts['popup_id']);

		return '<span class="show-popup" data-popup="' . $atts['popup_id'] . '">' . $content . '</span>';
	} else {
		return $content;
	}
}
add_shortcode('popup', 'popup_shortcode');
