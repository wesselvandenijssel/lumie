<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Screenshot REST API endpoints
 *
 * Provides endpoints for the screenshot generation system to discover
 * which blocks are used on which pages without requiring browser crawling.
 *
 * @package mbeffect
 */

/**
 * Get or generate the screenshots API secret token.
 *
 * @return string Secret token
 */
function screenshots_get_api_key(): string {
	$key = get_option('screenshots_api_key');

	if (empty($key)) {
		$key = bin2hex(random_bytes(32));
		update_option('screenshots_api_key', $key, false);
	}

	// Write token file so the local CLI can read it without extra config
	$token_file = get_stylesheet_directory() . '/.screenshots-token';
	if (!file_exists($token_file) || trim(file_get_contents($token_file)) !== $key) {
		file_put_contents($token_file, $key);
	}

	return $key;
}

/**
 * Verify the X-Screenshots-Key header against the stored token.
 *
 * @param WP_REST_Request $request Incoming request
 * @return bool|WP_Error True when authorised, WP_Error otherwise
 */
function screenshots_permission_callback(WP_REST_Request $request): bool|WP_Error {
	$provided = $request->get_header('X-Screenshots-Key');
	$expected = screenshots_get_api_key();

	if (!hash_equals($expected, (string) $provided)) {
		return new WP_Error(
			'rest_forbidden',
			esc_html__('Invalid or missing screenshots API key.', 'mbeffect'),
			['status' => 401]
		);
	}

	return true;
}

/**
 * Register REST API endpoints for screenshot system
 */
add_action('rest_api_init', function () {
	// Ensure token file exists from the first WordPress request
	screenshots_get_api_key();

	// Endpoint: Get all page URLs
	register_rest_route('screenshots/v1', '/all-urls', [
		'methods' => 'GET',
		'callback' => 'get_all_page_urls_callback',
		'permission_callback' => 'screenshots_permission_callback',
	]);

	// Endpoint: Get pages containing each of a comma-separated list of blocks
	register_rest_route('screenshots/v1', '/blocks-batch', [
		'methods' => 'GET',
		'callback' => 'get_blocks_pages_batch_callback',
		'permission_callback' => 'screenshots_permission_callback',
		'args' => [
			'names' => [
				'required' => true,
				'validate_callback' => function ($param) {
					return is_string($param) && preg_match('/^[a-zA-Z0-9,_-]+$/', $param);
				},
			],
		],
	]);
});

/**
 * Get all published page URLs
 *
 * Returns an array of all published URLs from pages, posts, and custom post types.
 * Used by screenshot system to discover which pages to scan.
 *
 * @return array Response with total count and URLs grouped by post type
 */
function get_all_page_urls_callback(): array {
	$urls = [];
	$by_type = [];

	// Get all public post types (pages, posts, custom post types)
	$post_types = get_post_types([
		'public' => true,
	], 'names');

	// Exclude certain post types that shouldn't be screenshotted
	$exclude_types = ['attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset'];
	$post_types = array_diff($post_types, $exclude_types);

	// Exclude specific pages by URL path that should never be screenshotted
	$exclude_paths = [
		'contact/bedankt',
		'contact/disclaimer',
		'contact/privacy-statement',
		'sitemap',
	];

	foreach ($post_types as $post_type) {
		// Get all published posts of this type
		$posts = get_posts([
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		]);

		$type_urls = [];
		foreach ($posts as $post) {
			$url = get_permalink($post->ID);
			$url_path = trim(str_replace(home_url('/'), '', $url), '/');

			// Skip pages with excluded paths
			$is_excluded = false;
			foreach ($exclude_paths as $excluded_path) {
				if ($url_path === $excluded_path) {
					$is_excluded = true;
					break;
				}
			}

			if ($is_excluded) {
				continue;
			}

			$urls[] = $url;
			$type_urls[] = $url;
		}

		if (!empty($type_urls)) {
			$by_type[$post_type] = [
				'count' => count($type_urls),
				'urls' => $type_urls,
			];
		}
	}

	// Add homepage if not already included
	$home_url = home_url('/');
	if (!in_array($home_url, $urls)) {
		array_unshift($urls, $home_url);
	}

	return [
		'total' => count($urls),
		'urls' => $urls,
		'by_type' => $by_type,
	];
}

/**
 * Get all published posts eligible for block-matching (public post types,
 * excluding always-skipped paths like the thank-you/privacy pages).
 *
 * @return WP_Post[] Eligible posts
 */
function screenshots_get_eligible_posts(): array {
	$post_types = get_post_types([
		'public' => true,
	], 'names');

	$exclude_types = ['attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset'];
	$post_types = array_diff($post_types, $exclude_types);

	$posts = get_posts([
		'post_type' => $post_types,
		'post_status' => 'publish',
		'posts_per_page' => -1,
	]);

	// Exclude specific pages by URL path that should never be screenshotted
	$exclude_paths = [
		'contact/bedankt',
		'contact/disclaimer',
		'contact/privacy-statement',
		'sitemap',
	];

	return array_values(array_filter($posts, function (WP_Post $post) use ($exclude_paths) {
		$url_path = trim(str_replace(home_url('/'), '', get_permalink($post->ID)), '/');
		return !in_array($url_path, $exclude_paths, true);
	}));
}

/**
 * Get the ACF flexible content layout names used on a post, across the
 * common flexible content field names.
 *
 * @param WP_Post $post Post to inspect
 * @return string[] Layout names present on the post
 */
function screenshots_get_post_flex_layouts(WP_Post $post): array {
	if (!function_exists('have_rows')) {
		return [];
	}

	$layouts = [];
	$flex_fields = ['content_modules', 'page_content', 'flexible_content'];

	foreach ($flex_fields as $field_name) {
		while (have_rows($field_name, $post->ID)) {
			the_row();
			$layouts[] = get_row_layout();
		}
	}

	return $layouts;
}

/**
 * Check whether a post uses the given block, via Gutenberg block comment,
 * rendered block class name, or ACF flexible content layout.
 *
 * @param WP_Post $post Post to inspect
 * @param string $block_name Block name to match
 * @param string[] $flex_layouts Pre-computed flexible content layouts for the post
 * @return bool True if the post uses the block
 */
function screenshots_post_matches_block(WP_Post $post, string $block_name, array $flex_layouts): bool {
	// Check 1: Exact block name in Gutenberg block comment
	// Uses regex with a word boundary so 'acf/cards' does NOT match 'acf/cards-slider'
	if (preg_match('/<!-- wp:acf\/' . preg_quote($block_name, '/') . '[\s{\/]/', $post->post_content)) {
		return true;
	}

	// Check 2: Block class name in content using word boundaries
	// e.g., "cards" should NOT match "cards-slider" or "hero__cards"
	$content = get_the_content(null, false, $post);
	$patterns = [
		'/[\s\'"](block-' . preg_quote($block_name, '/') . ')[\s\'">/]/', // ACF block class with boundaries
		'/[\s\'"](wp-block-' . preg_quote(str_replace('-', '_', $block_name), '/') . ')[\s\'">/]/', // WP block format
		'/[\'"](acf\/' . preg_quote($block_name, '/') . ')[\'">/]/', // ACF block reference
	];

	foreach ($patterns as $pattern) {
		if (preg_match($pattern, $content)) {
			return true;
		}
	}

	// Check 3: ACF flexible content layout name
	return in_array($block_name, $flex_layouts, true)
		|| in_array(str_replace('-', '_', $block_name), $flex_layouts, true);
}

/**
 * Shape a post into the page data returned by the screenshots API.
 *
 * @param WP_Post $post Post to convert
 * @return array Page data
 */
function screenshots_post_to_page_data(WP_Post $post): array {
	return [
		'id' => $post->ID,
		'title' => get_the_title($post->ID),
		'url' => get_permalink($post->ID),
		'slug' => $post->post_name,
		'post_type' => $post->post_type,
	];
}

/**
 * Get pages that contain each of a list of blocks, in a single pass over
 * all eligible posts.
 *
 * Scanning once per post (instead of once per block) avoids repeating a
 * full-site post scan for every block that needs screenshotting.
 *
 * @param WP_REST_Request $request Request object with comma-separated block names
 * @return array Response with pages per block
 */
function get_blocks_pages_batch_callback(WP_REST_Request $request): array {
	$names_param = (string) $request->get_param('names');
	$block_names = array_values(array_unique(array_filter(
		array_map('trim', explode(',', $names_param)),
	)));

	$pages_by_block = array_fill_keys($block_names, []);

	foreach (screenshots_get_eligible_posts() as $post) {
		$flex_layouts = screenshots_get_post_flex_layouts($post);

		foreach ($block_names as $block_name) {
			if (screenshots_post_matches_block($post, $block_name, $flex_layouts)) {
				$pages_by_block[$block_name][] = screenshots_post_to_page_data($post);
			}
		}
	}

	return ['blocks' => $pages_by_block];
}
