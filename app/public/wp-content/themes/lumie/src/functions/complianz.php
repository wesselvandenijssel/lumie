<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Adds scripts to the Complianz blocklist, so they only run after the visitor gives consent.
 *
 * - Google Tag Manager only holds the Crazy Egg heatmaps, which track visitors: statistics.
 * - The customer journey stores every page view in localStorage and attaches it to Gravity Forms
 *   entries, which links the browsing history to a name and email address: marketing.
 *
 * @param array $tags Scripts Complianz blocks before consent
 * @return array
 */
function lumie_complianz_blocked_scripts(array $tags): array {
	$tags[] = [
		'name' => 'Google Tag Manager (Crazy Egg)',
		'category' => 'statistics',
		'urls' => ['googletagmanager.com/gtm.js'],
		'enable_placeholder' => 0,
	];

	$tags[] = [
		'name' => 'Klantreis',
		'category' => 'marketing',
		'urls' => ['lumie-customer-journey/assets/js/tracking.js'],
		'enable_placeholder' => 0,
	];

	return $tags;
}
add_filter('cmplz_known_script_tags', 'lumie_complianz_blocked_scripts');

/**
 * Removes defer and async from scripts that Complianz blocked as type="text/plain", since those attributes are invalid there.
 *
 * @param string $html The page HTML after the Complianz cookie blocker
 * @return string
 */
function lumie_complianz_clean_blocked_scripts(string $html): string {
	return preg_replace_callback('/<script\b[^>]*\btype="text\/plain"[^>]*>/i', function (array $match): string {
		return preg_replace('/\s(?:defer|async|data-wp-strategy="[^"]*")(?=[\s>])/i', '', $match[0]);
	}, $html);
}
add_filter('cmplz_cookie_blocker_output', 'lumie_complianz_clean_blocked_scripts');
