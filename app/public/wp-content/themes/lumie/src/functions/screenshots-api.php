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
		file_put_contents($token_file, $key, LOCK_EX);
		@chmod($token_file, 0600);
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

	// Endpoint: Get pages containing specific block
	register_rest_route('screenshots/v1', '/blocks/(?P<name>[a-zA-Z0-9-_]+)', [
		'methods' => 'GET',
		'callback' => 'get_block_pages_callback',
		'permission_callback' => 'screenshots_permission_callback',
		'args' => [
			'name' => [
				'required' => true,
				'validate_callback' => function ($param) {
					return is_string($param) && preg_match('/^[a-zA-Z0-9-_]+$/', $param);
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
 * Get pages that contain a specific block
 *
 * Searches through all published pages, posts, and custom post types to find
 * which ones contain the specified block. Searches both Gutenberg block content
 * and checks for block class names in rendered content.
 *
 * @param WP_REST_Request $request Request object with block name
 * @return array Response with pages containing the block
 */
function get_block_pages_callback(WP_REST_Request $request): array {
	$block_name = $request['name'];
	$pages_with_block = [];

	// Get all public post types
	$post_types = get_post_types([
		'public' => true,
	], 'names');

	// Exclude certain post types
	$exclude_types = ['attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset'];
	$post_types = array_diff($post_types, $exclude_types);

	// Exclude specific pages by URL path that should never be screenshotted
	$exclude_paths = [
		'contact/bedankt',
		'contact/disclaimer',
		'contact/privacy-statement',
		'sitemap',
	];

	// Get all published posts from all post types
	$posts = get_posts([
		'post_type' => $post_types,
		'post_status' => 'publish',
		'posts_per_page' => -1,
	]);

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

		$has_block = false;

		// Check 1: Exact block name in Gutenberg block comment
		// Uses regex with a word boundary so 'acf/cards' does NOT match 'acf/cards-slider'
		if (preg_match('/<!-- wp:acf\/' . preg_quote($block_name, '/') . '[\s{\/]/', $post->post_content)) {
			$has_block = true;
		}

		// Check 2: Block class name in content using word boundaries
		if (!$has_block) {
			$content = get_the_content(null, false, $post);
			// Use regex with word boundaries to avoid substring matches
			// e.g., "cards" should NOT match "cards-slider" or "hero__cards"
			// Only match "block-cards" with word boundary after it
			$patterns = [
				'/[\s\'"](block-' . preg_quote($block_name, '/') . ')[\s\'">/]/', // ACF block class with boundaries
				'/[\s\'"](wp-block-' . preg_quote(str_replace('-', '_', $block_name), '/') . ')[\s\'">/]/', // WP block format
				'/[\'"](acf\/' . preg_quote($block_name, '/') . ')[\'">/]/', // ACF block reference
			];

			foreach ($patterns as $pattern) {
				if (preg_match($pattern, $content)) {
					$has_block = true;
					break;
				}
			}
		}

		// Check 3: ACF flexible content fields
		if (!$has_block && function_exists('have_rows')) {
			// Check common flexible content field names
			$flex_fields = ['content_modules', 'page_content', 'flexible_content'];

			foreach ($flex_fields as $field_name) {
				if (have_rows($field_name, $post->ID)) {
					while (have_rows($field_name, $post->ID)) {
						the_row();
						$layout = get_row_layout();

						// Check if layout name matches block name
						if ($layout === $block_name || $layout === str_replace('-', '_', $block_name)) {
							$has_block = true;
							break 2;
						}
					}
				}
			}
		}

		if ($has_block) {
			$pages_with_block[] = [
				'id' => $post->ID,
				'title' => get_the_title($post->ID),
				'url' => get_permalink($post->ID),
				'slug' => $post->post_name,
				'post_type' => $post->post_type,
			];
		}
	}

	return [
		'block' => $block_name,
		'total' => count($pages_with_block),
		'pages' => $pages_with_block,
	];
}
