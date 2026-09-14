<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Enable shortcodes in text widgets
 */
add_filter('widget_text', 'do_shortcode');

/**
 * Remove paragraph tags from around images
 *
 * @param string $content The content to filter
 * @return string Content with paragraph tags removed from images
 */
function mbeffect_filter_ptags_on_images($content) {
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

/**
 * Customize excerpt more string
 *
 * @param string $more The default more string
 * @return string Custom more string
 */
function mbeffect_excerpt_more($more) {
	global $post;
	return '...';
}

/**
 * Set custom excerpt length
 *
 * @param int $length Default excerpt length
 * @return int Custom excerpt length
 */
function custom_excerpt_length($length) {
	return 10;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

/**
 * Custom formatter to handle [raw]...[/raw] shortcodes
 *
 * @param string $content The content to format
 * @return string Formatted content
 */
function my_formatter($content) {
	$new_content = '';
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	foreach ($pieces as $piece) {
		if (preg_match($pattern_contents, $piece, $matches)) {
			$new_content .= $matches[1];
		} else {
			$new_content .= wptexturize(wpautop($piece));
		}
	}

	return $new_content;
}

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');
add_filter('the_content', 'my_formatter', 99);

/**
 * Fix email return path to avoid emails being marked as spam
 */
class Email_Return_Path {
	/**
	 * Constructor to set up the phpmailer_init action
	 */
	function __construct() {
		add_action('phpmailer_init', [$this, 'fix']);
	}

	/**
	 * Set the Sender property to match the From address
	 *
	 * @param PHPMailer $phpmailer The PHPMailer instance
	 */
	function fix($phpmailer) {
		$phpmailer->Sender = $phpmailer->From;
	}
}
new Email_Return_Path();

/**
 * Remove automatic paragraph tags from non-singular pages
 */
function get_rid_of_wpautop() {
	if (!is_singular()) {
		remove_filter('the_content', 'wpautop');
		remove_filter('the_excerpt', 'wpautop');
	}
}
add_action('template_redirect', 'get_rid_of_wpautop');

/**
 * Remove type attribute from script and style tags for HTML5 compliance
 *
 * @param string $tag		The HTML tag
 * @param string $handle	The script/style handle
 *
 * @return string The modified tag without type attribute
 */
function codeless_remove_type_attr($tag, $handle) {
	return preg_replace("/type=['\"]text\/(javascript|css)['\"]/", '', $tag);
}
add_filter('style_loader_tag', 'codeless_remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'codeless_remove_type_attr', 10, 2);

/**
 * Removes weak password confirmation check to enforce strong passwords
 */
function remove_weak_password_check_script() {
?>
	<script>
		document.addEventListener("DOMContentLoaded", function(event) {
			var elements = document.getElementsByClassName('pw-weak');
			var requiredElement = elements[0];
			if (requiredElement) {
				requiredElement.remove();
			}
		});
	</script>
<?php
}
add_action('admin_head', 'remove_weak_password_check_script');
add_action('login_enqueue_scripts', 'remove_weak_password_check_script');

/**
 * Hide update counters for non-admin users
 */
function custom_admin_css() {
	$current_user = wp_get_current_user();
	if ($current_user->ID != 1) {
		echo '<style>.update-plugins, .update-count {display: none!important; }</style>';
	}
}
add_action('admin_head', 'custom_admin_css');


/**
 * Enable SVG file uploads in WordPress media library
 *
 * @param array $mimes Array of allowed mime types
 * @return array Modified array with SVG support
 */
function cc_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


/**
 * Get the review stars based on a score
 *
 * @param float $score The score on a scale from 1 to 10
 * @return string HTML string with review star markup, empty string if score is invalid
 */
function get_review_stars($score): string {
	$score = floatval($score);

	if ($score < 0 || $score > 10) return '';

	$half_units = (int) round($score);
	$full_stars = intdiv($half_units, 2);
	$has_half = ($half_units % 2) === 1;
	$empty_stars = 5 - $full_stars - ($has_half ? 1 : 0);

	$stars = '';

	for ($i = 0; $i < $full_stars; $i++) {
		$stars .= '<span class="review-stars__star review-stars__star--full"></span>';
	}
	if ($has_half) {
		$stars .= '<span class="review-stars__star review-stars__star--half"></span>';
	}
	for ($i = 0; $i < $empty_stars; $i++) {
		$stars .= '<span class="review-stars__star review-stars__star--empty"></span>';
	}

	$rating = $half_units / 2;
	$rating_label = str_replace('.', ',', (string) $rating);
	$label = esc_html(sprintf(__('Beoordeling: %s van de 5 sterren', 'mbeffect'), $rating_label));

	return '<span class="review-stars" role="img" aria-label="' . esc_attr($label) . '">' . $stars . '</span>';
}


/**
 * Shortcode to display current year
 *
 * Usage: [get_year]
 *
 * @return string Current year
 */
function get_year() {
	return date('Y');
}
add_shortcode('get_year', 'get_year');

/**
 * Shortcode to display a soft hyphen
 *
 * Usage: [shy]
 *
 * @return string Soft hyphen character
 */
function shy_shortcode() {
	return '&shy;';
}
add_shortcode('shy', 'shy_shortcode');

/**
 * Exclude noindex'd pages from site search
 *
 * @link https://www.billerickson.net/code/exclude-no-index-content-from-wordpress-search/
 * @param WP_Query $query The WordPress query object
 */
function be_exclude_noindex_from_search($query) {
	if ($query->is_main_query() && $query->is_search() && !is_admin()) {
		$meta_query = !empty($query->meta_query) ? $query->meta_query : [];
		$meta_query['noindex'] = [
			'key' => '_yoast_wpseo_meta-robots-noindex',
			'value' => 1,
			'compare' => 'NOT EXISTS',
		];

		$query->set('meta_query', $meta_query);
	}
}
add_action('pre_get_posts', 'be_exclude_noindex_from_search');

/**
 * Exclude noindex'd pages from SearchWP search
 *
 * @param array $ids Array of excluded post IDs
 * @return array Modified array with noindex'd posts excluded
 */
function be_exclude_noindex_searchwp($ids) {
	$loop = new WP_Query([
		'post_type' => ['post', 'page'],
		'posts_per_page' => 999,
		'fields' => 'ids',
		'meta_query' => [
			[
				'key' => '_yoast_wpseo_meta-robots-noindex',
				'value' => 1,
			]
		]
	]);

	if ($loop->posts)
		$ids = array_merge($ids, $loop->posts);

	return $ids;
}
add_filter('searchwp_exclude', 'be_exclude_noindex_searchwp');

/**
 * Encrypt or decrypt a string using AES-256-CBC
 *
 * @param string $action	'encrypt' or 'decrypt'
 * @param string $string	The string to encrypt or decrypt
 *
 * @return string|false	The encrypted/decrypted string on success, or false on failure.
 *
 * Returns false if:
 * - The $action parameter is not 'encrypt' or 'decrypt'.
 * - The underlying OpenSSL encryption or decryption operation fails (e.g., due to invalid input or configuration).
 */
function encrypt_decrypt($action, $string): string|false {
	$output = false;
	$encrypt_method = "AES-256-CBC";
	$secret_key = 'U8CUZRKkzg5qfitrRUq3kKOfCdcrgpHpjDzjpWt94o3BWzyhf03xeL+x7wUsxlNP'; // 32 byte key, randomly generated with https://generate-random.org/encryption-key-generator
	$secret_iv = 'hvbIh8YQjKr30aQXQ02rxgmenRAjpaC+pekPeSR8GUw='; // 16 byte iv, randomly generated with https://generate-random.org/encryption-key-generator

	// hash
	$key = hash('sha256', $secret_key);
	// iv - encrypt method AES-256-CBC expects 16 bytes
	$iv = substr(hash('sha256', $secret_iv), 0, 16);

	if ($action == 'encrypt') {
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	} else if ($action == 'decrypt') {
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}
	return $output;
}

function custom_mce_color_options($init) {
	// Define custom colors with their corresponding HEX codes and names.
	$custom_colors = '
        "332B28", "' . __('Donkerbruin', 'mbeffect') . '",
        "332B2899", "' . __('Donkerbruin 60%', 'mbeffect') . '",
    ';

	// Build the color grid palette using custom colors
	$init['textcolor_map'] = '[' . $custom_colors . ']';

	// Change the number of rows in the color grid
	$init['textcolor_rows'] = 1;

	return $init;
}
add_filter('tiny_mce_before_init', 'custom_mce_color_options');

/**
 * Configure ACF WYSIWYG toolbar options
 *
 * @param array $toolbars Available toolbars
 * @return array Modified toolbars
 */
add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {

	// Register a basic toolbar with a single row of options
	// Available options: https://www.tiny.cloud/docs/tinymce/6/available-toolbar-buttons/
	$toolbars['title'][1] = ['link', 'unlink', 'italic', 'forecolor'];

	return $toolbars;
});

/**
 * Sanitize title while preserving specific HTML tags
 *
 * @param string $title The title to sanitize
 * @return string Sanitized title with allowed HTML tags preserved
 */
function sanitize_title_custom($title) {
	// Define allowed HTML tags.
	$allowed_tags = ['i', 'em', 'b', 'strong', 'u', 'a', 'br', 'span'];

	// Strip tags except the allowed ones
	$sanitized_title = strip_tags($title, '<' . implode('><', $allowed_tags) . '>');

	return $sanitized_title;
}

/**
 * Calculate reading time based on post content (both ACF blocks and regular content)
 *
 * @param int $post_ID The post ID to calculate reading time for
 * @return string Formatted reading time string with translation
 */
function reading_time($post_ID) {
	$content = '';

	// Retrieve the full post content
	$post_content = get_post_field('post_content', $post_ID);

	// Parse blocks to extract ACF data
	$blocks = parse_blocks($post_content);

	// If blocks exist and are an array, extract ACF block data
	if (!empty($blocks) && is_array($blocks)) {
		foreach ($blocks as $block) {
			// Extract text from ACF blocks
			if (!isset($block['blockName']) || strpos($block['blockName'], 'acf/') === false || !isset($block['attrs']['data'])) {
				continue;
			}

			foreach ($block['attrs']['data'] as $value) {
				if (!is_string($value) || empty($value) || empty(trim($value))) {
					continue;
				}

				$content .= ' ' . $value;
			}
		}
	}

	// Add regular post content (for classic editor or non-ACF content)
	$content .= ' ' . $post_content;

	// Remove HTML tags and normalize whitespace
	$content = strip_tags($content);
	$content = preg_replace(["/\r\n/", '/\s+/'], ' ', $content);

	// Calculate reading time (200 words per minute average)
	$word_count = str_word_count($content);
	$reading_time = max(1, ceil($word_count / 200));

	return sprintf(
		esc_html__('%s min', 'mbeffect'),
		$reading_time
	);
}

// Disable support for comments and trackbacks in post types
function disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'disable_comments_post_types_support');

// Close comments on the front-end
function disable_comments_status() {
	return false;
}
add_filter('comments_open', 'disable_comments_status', 20, 2);
add_filter('pings_open', 'disable_comments_status', 20, 2);

// Hide existing comments
function disable_comments_hide_existing_comments($comments) {
	return [];
}
add_filter('comments_array', 'disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in admin
function disable_comments_admin_menu() {
	global $menu;

	remove_menu_page('edit-comments.php');

	// Remove any separator related to comments
	foreach ($menu as $index => $item) {
		if (isset($item[2]) && strpos($item[2], 'separator') !== false) {
			unset($menu[$index]);
		}
	}
}
add_action('admin_menu', 'disable_comments_admin_menu', 999);

// Redirect comments page
function disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_safe_redirect(admin_url());
		exit;
	}
}
add_action('admin_init', 'disable_comments_admin_menu_redirect');

// Remove comments from admin bar
function disable_comments_admin_bar() {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'disable_comments_admin_bar');

// Disable REST API endpoints for comments
function disable_comments_rest_api($endpoints) {
	if (isset($endpoints['/wp/v2/comments'])) {
		unset($endpoints['/wp/v2/comments']);
	}
	if (isset($endpoints['/wp/v2/comments/(?P<id>[\d]+)'])) {
		unset($endpoints['/wp/v2/comments/(?P<id>[\d]+)']);
	}
	return $endpoints;
}
add_filter('rest_endpoints', 'disable_comments_rest_api');

// Disable comments via XML-RPC
function disable_comments_xmlrpc($methods) {
	$comment_methods = [
		'wp.newComment' => true,
		'wp.getComments' => true,
		'wp.getComment' => true,
		'wp.editComment' => true,
		'wp.deleteComment' => true,
	];
	return array_diff_key($methods, $comment_methods);
}
add_filter('xmlrpc_methods', 'disable_comments_xmlrpc');
?>
