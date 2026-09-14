<?php defined('ABSPATH') || exit;

class MB_CJ_GF_Submission {

	private const MAX_RAW_BYTES = 20000;
	private const MAX_STEPS = 50;
	private const MAX_TEXT_LENGTH = 255;
	private const MAX_URL_LENGTH = 2048;

	public static function save_journey_meta(array $entry, array $form): void {

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$raw = isset($_POST[MB_CJ_GravityForms::POST_FIELD]) ? wp_unslash($_POST[MB_CJ_GravityForms::POST_FIELD]) : '';

		if (!empty($raw) && strlen($raw) <= self::MAX_RAW_BYTES) {
			$decoded = json_decode($raw, true);

			if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
				$decoded = array_slice($decoded, -self::MAX_STEPS);
				$sanitized = array_values(array_filter(array_map([__CLASS__, 'sanitize_step'], $decoded)));

				if (!empty($sanitized)) {
					gform_update_meta($entry['id'], MB_CJ_GravityForms::META_KEY, wp_json_encode($sanitized), $form['id']);
				}
			}
		}

		// Signal the browser to reset the journey on the next page load.
		// For AJAX submissions the JS gform_confirmation_loaded event handles the
		// reset directly; this cookie is the fallback for non-AJAX submissions.
		self::set_reset_cookie();
	}

	private static function set_reset_cookie(): void {
		setcookie('mb_cj_clear_journey', '1', [
			'expires' => time() + 300,
			'path' => defined('COOKIEPATH') ? COOKIEPATH : '/',
			'domain' => defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '',
			'secure' => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		]);
	}

	private static function sanitize_step($step): array {

		if (!is_array($step)) {
			return [];
		}

		return [
			'path' => self::truncate_text(sanitize_text_field($step['path'] ?? ''), self::MAX_TEXT_LENGTH),
			'title' => self::truncate_text(sanitize_text_field($step['title'] ?? ''), self::MAX_TEXT_LENGTH),
			'url' => esc_url_raw(self::truncate_text((string) ($step['url'] ?? ''), self::MAX_URL_LENGTH)),
			'referrer' => esc_url_raw(self::truncate_text((string) ($step['referrer'] ?? ''), self::MAX_URL_LENGTH)),
			'timestamp' => self::truncate_text(sanitize_text_field($step['timestamp'] ?? ''), self::MAX_TEXT_LENGTH),
		];
	}

	private static function truncate_text(string $value, int $max_length): string {
		if (strlen($value) <= $max_length) {
			return $value;
		}

		return substr($value, 0, $max_length);
	}
}
