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

/**
 * Queue every popup that is marked as a startup popup, so footer_popups() renders it.
 *
 * Runs on template_redirect because that is late enough for the queue to be filled
 * before the footer, and early enough that nothing has been output yet.
 *
 * @return void
 */
function add_startup_popups(): void {
	$query = new WP_Query([
		'post_type' => 'popup',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'name',
		'order' => 'ASC',
		'no_found_rows' => true,
	]);

	if (!$query->have_posts()) {
		return;
	}

	while ($query->have_posts()) : $query->the_post();
		$popup_id = get_the_ID();

		if (empty(get_field('startup', $popup_id))) {
			continue;
		}

		// An empty day selection means the popup runs every day. ACF returns null
		// rather than an empty array when nothing is checked, hence the cast.
		$days = array_map('intval', (array) (get_field('startup_day', $popup_id) ?: []));

		if (!empty($days) && !in_array((int) current_time('w'), $days, true)) {
			continue;
		}

		add_global_popup($popup_id);
	endwhile;

	wp_reset_postdata();
}
add_action('template_redirect', 'add_startup_popups');
