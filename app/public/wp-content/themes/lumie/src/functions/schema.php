<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Adds the social profiles, contact point and address from the theme settings to the Organization node of Yoast.
 * Yoast is the only source of structured data, so changes in the theme settings show up in the schema as well.
 *
 * @param array $data The Organization schema piece
 * @return array
 */
add_filter('wpseo_schema_organization', 'lumie_schema_organization', 11);
function lumie_schema_organization(array $data): array {
	$social_media = get_field('social_media', 'options') ?: [];
	$same_as = $data['sameAs'] ?? [];

	foreach ($social_media as $profile) {
		if (!empty($profile['url'])) {
			$same_as[] = esc_url_raw($profile['url']);
		}
	}

	if (!empty($same_as)) {
		$data['sameAs'] = array_values(array_unique($same_as));
	}

	$contact = get_field('contact_details', 'options') ?: [];
	$contact_point = [
		'@type' => 'ContactPoint',
		'contactType' => 'customer service',
		'availableLanguage' => ['nl'],
	];

	if (!empty($contact['email']) && is_email($contact['email'])) {
		$contact_point['email'] = sanitize_email($contact['email']);
	}

	if (!empty($contact['phone'])) {
		// Schema expects international numbers, so a Dutch 0 prefix becomes +31
		$contact_point['telephone'] = preg_replace('/^0(?=[1-9])/', '+31', preg_replace('/[^0-9+]/', '', $contact['phone']));
	}

	// The contact page (ID 12) holds the form and the contact details
	if (get_post_status(12) === 'publish') {
		$contact_point['url'] = get_permalink(12);
	}

	$data['contactPoint'] = $contact_point;

	$address = $contact['address_data'] ?? [];

	if (!empty($address['street']) && !empty($address['city'])) {
		$data['address'] = [
			'@type' => 'PostalAddress',
			'streetAddress' => $address['street'],
			'postalCode' => $address['zip'] ?? '',
			'addressLocality' => $address['city'],
			'addressCountry' => 'NL',
		];
	}

	return $data;
}
