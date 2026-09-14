<?php

/**
 * Plugin Name: Lumie Customer Journey
 * Plugin URI: https://www.lumiedrinkz.nl/
 * Description: Tracks the customer journey across pages via localStorage and stores it as entry meta in Gravity Forms.
 * Version: 0.2.2
 * Author: Lumie
 * Author URI: https://www.lumiedrinkz.nl/
 * License: MIT
 * Text Domain: lumie-customer-journey
 * Requires Plugins: gravityforms
 */

if (!defined('ABSPATH')) {
	exit;
}

define('MB_CJ_VERSION', '0.2.2');
define('MB_CJ_DIR', plugin_dir_path(__FILE__));
define('MB_CJ_URL', plugin_dir_url(__FILE__));

require_once MB_CJ_DIR . 'libraries/plugin-update-checker/plugin-update-checker.php';

$mb_cj_update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
	'https://plugins.mbeffect.nl/mb-customer-journey/metadata.php',
	__FILE__,
	'mb-customer-journey'
);

$mb_cj_update_checker->addQueryArgFilter(function (array $args) {
	$site = home_url();
	$ts = time();

	$args['site'] = $site;
	$args['ts'] = $ts;

	return $args;
});

// Gravity Forms integration
add_action('plugins_loaded', 'mb_cj_bootstrap_gravityforms');

function mb_cj_bootstrap_gravityforms() {
	if (!class_exists('GFForms')) {
		add_action('admin_notices', 'mb_cj_gravityforms_missing_notice');
		return;
	}

	require_once MB_CJ_DIR . 'includes/gravityforms/class-mb-cj-gravityforms.php';
	MB_CJ_GravityForms::register_hooks();
}

function mb_cj_gravityforms_missing_notice() {
	if (!current_user_can('activate_plugins')) {
		return;
	}

	echo '<div class="notice notice-error"><p>'
		. esc_html__('MB Customer Journey requires Gravity Forms to be active.', 'mb-customer-journey')
		. '</p></div>';
}

add_action('wp_enqueue_scripts', 'mb_cj_enqueue_scripts');

/**
 * Returns the path (+ query string) for the current page, safe for all page types.
 * get_permalink() / get_the_title() without an ID pull from the loop's current post,
 * which is wrong on archive and search pages.
 */
function mb_cj_current_path(): string {
	$uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '/';
	$path = wp_parse_url($uri, PHP_URL_PATH) ?: '/';
	$qs = wp_parse_url($uri, PHP_URL_QUERY);

	return $qs ? "{$path}?{$qs}" : $path;
}

/**
 * Returns the human-readable title for the current page, safe for all page types.
 */
function mb_cj_current_title(): string {
	if (is_singular()) {
		return wp_strip_all_tags(get_the_title(get_queried_object_id())) ?: get_bloginfo('name');
	}

	if (is_search()) {
		return sprintf(__('Search: %s', 'mb-customer-journey'), get_search_query());
	}

	if (is_archive()) {
		return wp_strip_all_tags(get_the_archive_title()) ?: get_bloginfo('name');
	}

	return get_bloginfo('name');
}

function mb_cj_enqueue_scripts() {
	wp_enqueue_script(
		'mb-customer-journey',
		MB_CJ_URL . 'assets/js/tracking.js',
		[],
		MB_CJ_VERSION,
		// Load in <head> with defer so it runs ASAP but doesn't block render.
		['strategy' => 'defer', 'in_footer' => false]
	);

	// Check for the reset signal set by PHP after a non-AJAX form submission.
	$clear_journey = false;
	if (!empty($_COOKIE['mb_cj_clear_journey'])) {
		$clear_journey = true;
		setcookie('mb_cj_clear_journey', '', [
			'expires' => time() - 3600,
			'path' => defined('COOKIEPATH') ? COOKIEPATH : '/',
			'domain' => defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '',
			'secure' => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		]);
	}

	wp_localize_script(
		'mb-customer-journey',
		'mbCJ',
		[
			'path' => mb_cj_current_path(),
			'title' => mb_cj_current_title(),
			'clearJourney' => $clear_journey,
		]
	);
}

// ---------------------------------------------------------------------------
// WP Rocket – exclude our script from every optimisation that could delay or
// break it (minify, combine, defer JS, delay JS execution).
// ---------------------------------------------------------------------------
add_filter('rocket_exclude_js', 'mb_cj_rocket_exclude_js');
add_filter('rocket_defer_inline_js', 'mb_cj_rocket_exclude_js');
add_filter('rocket_exclude_defer_js', 'mb_cj_rocket_exclude_js');
add_filter('rocket_delay_js_exclusions', 'mb_cj_rocket_exclude_js');

function mb_cj_rocket_exclude_js(array $list): array {
	$list[] = 'mb-customer-journey/assets/js/tracking.js';
	return $list;
}
