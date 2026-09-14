<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

function lumie_scripts_and_styles() {
	if (!is_admin()) {
		// register main stylesheet
		wp_register_style('lumie-stylesheet', get_stylesheet_directory_uri() . '/dist/main.css', [], filemtime(get_stylesheet_directory() . '/dist/main.css'), 'all');

		// register font awesome
		wp_register_style('lumie-font-awesome', get_stylesheet_directory_uri() . '/node_modules/@awesome.me/kit-47e6dc046c/icons/css/all.min.css', [], filemtime(get_stylesheet_directory() . '/node_modules/@awesome.me/kit-47e6dc046c/icons/css/all.min.css'), 'all');

		wp_deregister_script('jquery');
		wp_register_script('jquery', get_stylesheet_directory_uri() . '/src/scripts/libs/jquery-min.js', [], filemtime(get_stylesheet_directory() . '/src/scripts/libs/jquery-min.js'), true);

		// comment reply script for threaded comments
		if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
			wp_enqueue_script('comment-reply');
		}

		// Footer scripts with defer so the browser can start fetching them during parsing
		wp_register_script('lumie-js', get_stylesheet_directory_uri() . '/dist/main.js', [], filemtime(get_stylesheet_directory() . '/dist/main.js'), ['strategy' => 'defer', 'in_footer' => true]);
		wp_register_script('lumie-fancybox', get_stylesheet_directory_uri() . '/dist/fancybox/fancybox.js', [], filemtime(get_stylesheet_directory() . '/dist/fancybox/fancybox.js'), ['strategy' => 'defer', 'in_footer' => true]);

		// enqueue styles and scripts
		wp_enqueue_style('lumie-stylesheet');
		wp_enqueue_style('lumie-font-awesome');
		wp_enqueue_script('lumie-js');
		wp_enqueue_script('lumie-fancybox');

		wp_deregister_style('gform_basic');
		wp_deregister_style('gform_theme_components');
		wp_deregister_style('gform_theme');
	}
}

/**
 * Keep jQuery and Gravity Forms scripts out of WP Rocket's "Delay JavaScript Execution".
 *
 * @param string[] $excluded Patterns (regex, matched against the full script tag) excluded from delay.
 * @return string[]
 */
add_filter('rocket_delay_js_exclusions', function (array $excluded): array {
	$excluded[] = 'jquery-min.js';
	$excluded[] = 'gravityforms';
	$excluded[] = 'gform';
	$excluded[] = 'plupload';
	$excluded[] = 'moxie';
	$excluded[] = 'wp-a11y';
	$excluded[] = 'wp-hooks';
	$excluded[] = 'wp-i18n';
	$excluded[] = 'wp-dom-ready';

	return $excluded;
});

add_action('admin_enqueue_scripts', 'load_admin_style');
function load_admin_style() {
	wp_enqueue_style('admin_css', get_template_directory_uri() . '/dist/admin.css', [], filemtime(get_stylesheet_directory() . '/dist/admin.css'), 'all');
}

add_action('wp_enqueue_scripts', 'lumie_dequeue_dashicons', 100);
function lumie_dequeue_dashicons() {
	if (!is_user_logged_in()) {
		wp_dequeue_style('dashicons');
		wp_deregister_style('dashicons');
	}
}

add_filter('wp_resource_hints', 'lumie_preconnect_hints', 10, 2);
function lumie_preconnect_hints(array $urls, string $relation_type): array {
	if ($relation_type === 'preconnect') {
		$preconnect = get_field('preconnect', 'utilities') ?: [];

		foreach ($preconnect as $row) {
			if (!empty($row['url'])) {
				$urls[] = $row['url'];
			}
		}
	}

	return $urls;
}
