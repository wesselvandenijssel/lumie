<?php defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

// Image settings
add_action('acf/init', 'hook_focus_point_methods', 20);
/**
 * Hook methods for managing focus points.
 */
function hook_focus_point_methods(): void {
	$image_settings = get_field('image_settings_group', 'utilities');

	if (!empty($image_settings) && !empty($image_settings['focuspoint'])) {
		add_action('admin_enqueue_scripts', 'enqueue_scripts');
		add_filter('attachment_fields_to_edit', 'edit_fields', 10, 2);
		add_filter('edit_attachment', 'save_fields');
		add_filter('wp_get_attachment_image_attributes', 'attachment_image_attributes', PHP_INT_MAX, 10);
	}

	if (!empty($image_settings) && !empty($image_settings['webp'])) {

		/**
		 * Compress and convert to WebP for uploading images
		 */
		function compress_and_convert_images_to_webp($file) {
			// Check if file type is supported
			$supported_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
			if (!in_array($file['type'], $supported_types)) {
				return $file;
			}

			// Get the path to the upload directory
			$wp_upload_dir = wp_upload_dir();

			// Set up the file paths
			$old_file_path = $file['file'];
			$file_name = basename($file['file']);
			$webp_file_path = $wp_upload_dir['path'] . '/' . pathinfo($file_name, PATHINFO_FILENAME) . '.webp';

			// Check if file is already a WebP image
			if (pathinfo($old_file_path, PATHINFO_EXTENSION) === 'webp') {
				return $file;
			}

			// Load the image using Imagick
			$image = new Imagick($old_file_path);

			// Compress the image
			$quality = 75; // Adjust this value to control the compression level
			$image->setImageCompressionQuality($quality);
			$image->stripImage(); // Remove all profiles and comments to reduce file size

			// Convert the image to WebP
			$image->setImageFormat('webp');
			$image->setOption('webp:lossless', 'false');
			$image->setOption('webp:method', '6'); // Adjust this value to control the compression level for WebP
			$image->writeImage($webp_file_path);

			// Delete the old image file
			unlink($old_file_path);

			// Return the updated file information
			return [
				'file' => $webp_file_path,
				'url' => $wp_upload_dir['url'] . '/' . basename($webp_file_path),
				'type' => 'image/webp',
			];
		}
		add_filter('wp_handle_upload', 'compress_and_convert_images_to_webp');
	}
}

/**
 * Enqueue scripts for managing focus points.
 */
function enqueue_scripts(): void {
	wp_enqueue_script('lumie-focuspoint', get_stylesheet_directory_uri() . '/src/scripts/files/admin/focuspoint.js', ['jquery', 'media-editor'], '', true);
}

/**
 * Edit fields for managing focus points.
 *
 * @param array		$fields	Fields.
 * @param \WP_Post	$post	WordPress post object.
 *
 * @return array Updated fields.
 */
function edit_fields(array $fields, \WP_Post $post): array {
	return array_merge($fields, [
		'posX' => ['value' => get_post_meta($post->ID, 'posX', true) ?: '', 'label' => 'posX'],
		'posY' => ['value' => get_post_meta($post->ID, 'posY', true) ?: '', 'label' => 'posY'],
	]);
}

/**
 * Save fields for managing focus points.
 *
 * @param int $id Attachment ID.
 */
function save_fields($id): void {
	// Verify nonce for security.
	$nonce = $_REQUEST['nonce'] ?? $_REQUEST['_wpnonce'] ?? '';
	if (!wp_verify_nonce($nonce, 'update-post_' . $id)) {
		return;
	}

	// Sanitize and validate input
	$posX = isset($_REQUEST['attachments'][$id]['posX'])
		? sanitize_text_field($_REQUEST['attachments'][$id]['posX'])
		: '';
	$posY = isset($_REQUEST['attachments'][$id]['posY'])
		? sanitize_text_field($_REQUEST['attachments'][$id]['posY'])
		: '';

	// Validate that values are numeric
	if ($posX !== '' && $posY !== '' && is_numeric($posX) && is_numeric($posY)) {
		$floatPosX = floatval($posX);
		$floatPosY = floatval($posY);
		if ($floatPosX >= 0 && $floatPosX <= 100 && $floatPosY >= 0 && $floatPosY <= 100) {
			update_post_meta($id, 'posX', $floatPosX);
			update_post_meta($id, 'posY', $floatPosY);
		}
	}
}

/**
 * Modify image attributes for managing focus points.
 *
 * @param array $attr Image attributes.
 *
 * @param WP_Post $attachment Image attachment post.
 *
 * @return array Updated image attributes.
 */
function attachment_image_attributes(array $attr, $attachment): array {
	$id = $attachment->ID;
	if ($id !== 0) {

		$x = get_post_meta($id, 'posX', true);
		$y = get_post_meta($id, 'posY', true);

		if (!empty($x) && !empty($y)) {
			$attr['style'] = "object-position: {$x}% {$y}%; object-fit: cover;";
		}
	}
	return $attr;
}
