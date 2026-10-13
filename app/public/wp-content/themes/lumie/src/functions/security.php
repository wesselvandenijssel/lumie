<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Removes the user endpoints from the REST API for visitors who are not logged in.
 *
 * @param array $endpoints The registered REST routes
 * @return array
 */
function lumie_hide_rest_users(array $endpoints): array {
	if (is_user_logged_in()) return $endpoints;

	foreach (array_keys($endpoints) as $route) {
		if (str_starts_with($route, '/wp/v2/users')) {
			unset($endpoints[$route]);
		}
	}

	return $endpoints;
}
add_filter('rest_endpoints', 'lumie_hide_rest_users');

add_filter('xmlrpc_enabled', '__return_false');

/**
 * Removes the XML-RPC discovery links from the page head, since XML-RPC is disabled.
 */
function lumie_remove_xmlrpc_links(): void {
	remove_action('wp_head', 'rsd_link');
}
add_action('init', 'lumie_remove_xmlrpc_links');

/**
 * Refuses every XML-RPC request, including the system methods that the xmlrpc_enabled filter does not cover.
 */
function lumie_block_xmlrpc(): void {
	if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
		status_header(403);
		exit;
	}
}
add_action('init', 'lumie_block_xmlrpc', 1);
