<?php

/**
 * JSON-LD Schema generator
 *
 * Outputs structured data (schema.org) for:
 * - Organization (default)
 * - Article (single blog posts)
 *
 * Also extends Yoast schema:
 * - On single posts: wrap Yoast WebPage as isPartOf an Article node
 * - On author archives: add worksFor to Yoast Person node
 *
 * Dependencies:
 * - WordPress core
 * - Advanced Custom Fields (ACF)
 * - Yoast SEO (filters are safe if Yoast is not active)
 *
 * @package MB_Schema
 */

defined('ABSPATH') || exit('Forbidden');

if (!function_exists('get_field')) {
	return;
}

/**
 * Get global post object safely.
 *
 * @return WP_Post|null
 */
function mb_schema_get_post_data() {
	global $post;
	return $post instanceof WP_Post ? $post : null;
}

/**
 * Get site language in IETF format (e.g. nl-NL).
 *
 * @return string
 */
function mb_schema_get_language() {
	$locale = function_exists('determine_locale') ? determine_locale() : get_locale();
	$lang = str_replace('_', '-', (string) $locale);
	return $lang !== '' ? $lang : 'nl-NL';
}

/**
 * Build a Schema ImageObject.
 *
 * @param int|string $image_id_or_url Attachment ID or absolute URL.
 * @param string $caption Optional caption.
 *
 * @return array|null
 */
function mb_schema_image_object($image_id_or_url, $caption = '') {
	$url = '';

	if (is_numeric($image_id_or_url)) {
		$url = wp_get_attachment_image_url((int) $image_id_or_url, 'full');
	} else {
		$url = (string) $image_id_or_url;
	}

	$url = esc_url_raw($url);
	if (!$url) return null;

	$image = [
		'@type' => 'ImageObject',
		'url' => $url,
	];

	$caption = trim((string) $caption);
	if ($caption !== '') {
		$image['caption'] = $caption;
	}

	return $image;
}

/**
 * Calculate word count for a post.
 *
 * @param int $post_id
 *
 * @return int
 */
function mb_schema_get_word_count($post_id) {
	$content = get_post_field('post_content', $post_id);
	$text = wp_strip_all_tags((string) $content);
	$text = preg_replace('/\s+/u', ' ', $text);
	$text = trim($text);

	if ($text === '') return 0;

	$words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
	return is_array($words) ? count($words) : 0;
}

/**
 * Get keywords from post tags.
 *
 * @param int $post_id
 *
 * @return array
 */
function mb_schema_get_keywords_from_tags($post_id) {
	$tags = get_the_tags($post_id);
	if (!$tags || is_wp_error($tags)) return [];

	$keywords = [];
	foreach ($tags as $tag) {
		if (!empty($tag->name)) {
			$keywords[] = (string) $tag->name;
		}
	}

	return array_values(array_unique($keywords));
}

/**
 * Safe getter for array values.
 *
 * @param array|null $array
 * @param string $key
 * @param mixed $fallback
 *
 * @return mixed
 */
function mb_schema_safe_get($array, $key, $fallback = '') {
	if (!is_array($array)) return $fallback;
	return $array[$key] ?? $fallback;
}

/**
 * Normalize awards field: array of rows with subfield 'award'.
 *
 * @param mixed $awards
 *
 * @return array
 */
function mb_schema_normalize_awards($awards) {
	if (empty($awards) || !is_array($awards)) return [];

	$out = [];
	foreach ($awards as $row) {
		if (!is_array($row)) continue;
		$val = isset($row['award']) ? trim((string) $row['award']) : '';
		if ($val !== '') $out[] = $val;
	}

	return array_values(array_unique($out));
}

/**
 * Normalize ACF founder user field to schema Person.
 *
 * @param mixed $founder_user
 *
 * @return array|null
 */
function mb_schema_founder_from_user($founder_user) {
	if (empty($founder_user)) return null;

	$user = null;

	if ($founder_user instanceof WP_User) {
		$user = $founder_user;
	} elseif (is_numeric($founder_user)) {
		$user = get_userdata((int) $founder_user);
	} elseif (is_array($founder_user) && !empty($founder_user['ID'])) {
		$user = get_userdata((int) $founder_user['ID']);
	}

	if (!$user) return null;

	$person = [
		'@type' => 'Person',
		'name' => (string) $user->display_name,
	];

	$author_url = get_author_posts_url((int) $user->ID);
	if ($author_url) {
		$person['@id'] = $author_url . '#founder';
		$person['url'] = $author_url;
	}

	$desc = get_the_author_meta('description', (int) $user->ID);
	if (!empty($desc)) {
		$person['description'] = wp_strip_all_tags((string) $desc);
	}

	$avatar_url = get_avatar_url((int) $user->ID, ['size' => 512]);
	if ($avatar_url) {
		$img_obj = mb_schema_image_object($avatar_url, (string) $user->display_name);
		if ($img_obj) $person['image'] = $img_obj;
	}

	return $person;
}

$post_data = mb_schema_get_post_data();
$post_id = $post_data ? (int) $post_data->ID : 0;

$payload = [];
$payload['@context'] = 'https://schema.org';

$home_url = home_url('/');
$site_name = get_bloginfo('name');

$org_id = $home_url . '#organization';

if (is_singular('post') && $post_id) {
	$author_data = get_userdata((int) $post_data->post_author);

	$post_url = get_permalink($post_id);
	$thumb_id = get_post_thumbnail_id($post_id);
	$thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '';

	$categories = get_the_category($post_id);
	$category = (!empty($categories[0]->name)) ? $categories[0]->name : '';

	$publisher = [
		'@type' => 'Organization',
		'@id' => $org_id,
		'name' => $site_name,
		'url' => $home_url,
	];

	$logo_id = get_field('logo', 'options');
	if ($logo_id) {
		$logo_obj = mb_schema_image_object($logo_id, $site_name);
		if ($logo_obj) $publisher['logo'] = $logo_obj;
	}

	$author = [
		'@type' => 'Person',
		'name' => $author_data ? $author_data->display_name : '',
	];

	$author_url = $author_data ? get_author_posts_url((int) $author_data->ID) : '';
	if ($author_url) {
		$author['@id'] = $author_url . '#author';
		$author['url'] = $author_url;
	}

	$payload['@type'] = 'Article';
	$payload['@id'] = $post_url . '#article';
	$payload['url'] = $post_url;
	$payload['headline'] = get_the_title($post_id);
	$payload['datePublished'] = get_the_date('c', $post_id);
	$payload['dateModified'] = get_the_modified_date('c', $post_id);
	$payload['author'] = $author;
	$payload['publisher'] = $publisher;
	$payload['articleSection'] = $category;
	$payload['wordCount'] = mb_schema_get_word_count($post_id);
	$payload['keywords'] = mb_schema_get_keywords_from_tags($post_id);

	if ($thumb_url) {
		$img_obj = mb_schema_image_object($thumb_url, get_the_title($post_id));
		if ($img_obj) $payload['image'] = $img_obj;
	}
}

if (!is_singular('post') && !is_singular('vacancy') && !is_author()) {
	$social_media = get_field('social_media', 'options');
	$same_as = [];

	foreach (['facebook', 'instagram', 'linkedin', 'twitter'] as $network) {
		$url = esc_url(mb_schema_safe_get($social_media[$network] ?? null, 'url'));
		if ($url) $same_as[] = $url;
	}
	if (!empty($social_media['tiktok'])) {
		array_push($social_media_list, $social_media['tiktok']['url']);
	}

	$contact = get_field('contact_details', 'options');

	$payload['@type'] = 'Organization';
	$payload['@id'] = $org_id;
	$payload['name'] = $site_name;
	$payload['url'] = $home_url;

	if (!empty($same_as)) {
		$payload['sameAs'] = array_values(array_unique($same_as));
	}

	$logo_id = get_field('logo', 'options');
	if ($logo_id) {
		$payload['logo'] = esc_url_raw(wp_get_attachment_image_url($logo_id, 'full'));
	}

	$payload['contactPoint'] = [[
		'@type' => 'ContactPoint',
		'contactType' => 'customer service',
		'telephone' => preg_replace('/[^0-9+\s\-().]/', '', (string) mb_schema_safe_get($contact, 'phone')),
		'email' => sanitize_email(mb_schema_safe_get($contact, 'email')),
	]];

	$payload['areaServed'] = [
		'@type' => 'Country',
		'name' => 'Netherlands',
	];

	$uploads_dir = wp_upload_dir();
	$xml_file = trailingslashit($uploads_dir['basedir']) . 'review_data.xml';

	if (file_exists($xml_file)) {
		$xml = simplexml_load_file($xml_file);
		if ($xml !== false) {
			$rating_value = isset($xml->averageRating) ? (string) $xml->averageRating : '';
			$review_count = isset($xml->totalReviewCount) ? (string) $xml->totalReviewCount : '';

			if ($rating_value !== '' && $review_count !== '') {
				$payload['aggregateRating'] = [
					'@type' => 'AggregateRating',
					'ratingValue' => $rating_value,
					'reviewCount' => $review_count,
					'bestRating' => '5',
					'worstRating' => '1',
				];
			}
		}
	}

	$founding_year = get_field('founding_year', 'options');
	$founding_year = is_numeric($founding_year) ? (int) $founding_year : 0;
	if ($founding_year > 0) {
		$payload['foundingDate'] = (string) $founding_year;
	}

	$awards = get_field('awards', 'options');
	$awards = mb_schema_normalize_awards($awards);
	if (!empty($awards)) {
		$payload['award'] = $awards;
	}

	$founder_user = get_field('founder', 'options');
	$founder_person = mb_schema_founder_from_user($founder_user);
	if (!empty($founder_person)) {
		$payload['founder'] = $founder_person;
	}
}

$payload = apply_filters('mb_schema_payload', $payload);

if (!empty($payload)) {
	echo '<script type="application/ld+json">';
	echo wp_json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	echo '</script>';
}

add_filter('wpseo_schema_webpage', 'mb_schema_modify_yoast_webpage', 11, 1);
function mb_schema_modify_yoast_webpage($data) {
	if (!is_singular('post')) {
		return $data;
	}

	$new_data = [
		'@type' => 'Article',
		'@id' => get_permalink() . '#article',
		'isPartOf' => $data,
	];

	return $new_data;
}

add_filter('wpseo_schema_person', 'mb_add_worksfor_to_person', 11, 2);
function mb_add_worksfor_to_person($data, $context) {
	if (!is_author()) {
		return $data;
	}

	$data['worksFor'] = [
		'@type' => 'Organization',
		'@id' => home_url('/') . '#organization',
		'name' => get_bloginfo('name'),
		'url' => home_url('/'),
	];

	return $data;
}
