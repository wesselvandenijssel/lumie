<?php

/**
 * Google Business Profile Reviews Cron Script
 * Saves original comments, averageRating, totalReviewCount, and numeric star ratings to review_data.xml, runs daily at 3:00 AM UTC.
 *
 * -------------------------------------------
 * CONFIGURATION GUIDE
 * -------------------------------------------
 *
 * All credentials are configured via the ACF options page under Review instellingen.
 *
 * STEP 1: GET client_id AND client_secret
 * -------------------------------------------
 * 1. Go to the Google Cloud Console: https://console.cloud.google.com/
 * 2. Navigate to APIs & Services > Credentials.
 * 3. Click "Create Credentials" > "OAuth client ID".
 * 4. Choose "Web Application" as the application type.
 * 5. Add an Authorized Redirect URI:
 *		https://developers.google.com/oauthplayground
 *		https://yourwebsite.com
 *		localhost
 * 6. Click Create and copy the Client ID and Client Secret into the ACF options page.
 *
 * STEP 2: GET refresh_token USING OAUTH PLAYGROUND
 * -------------------------------------------
 * 1. Go to OAuth Playground: https://developers.google.com/oauthplayground
 * 2. Click the Settings icon in the top-right corner.
 *		- Enable "Use your own OAuth credentials"
 *		- Enter your Client ID and Client Secret
 * 3. In Step 1, enter the scope:
 *		https://www.googleapis.com/auth/business.manage
 * 4. Click "Authorize APIs" and sign in with your Google account.
 * 5. In Step 2, click "Exchange authorization code for tokens".
 * 6. Copy the Refresh Token and enter it in the ACF options page.
 *
 * STEP 3: GET account_id AND location_id
 * -------------------------------------------
 * GET account_id:
 * 1. Use your Access Token from OAuth Playground.
 * 2. Make a GET request to:
 *		https://mybusinessaccountmanagement.googleapis.com/v1/accounts
 *		Add the access_token to the Authorization header.
 *		The MB effect default account_id is 100489270130940983275.
 * 3. Use the `name` value from the response without the 'accounts/' prefix.
 *		Example response:
 *		{
 *			"accounts": [
 *				{
 *					"name": "accounts/1234567890",
 *					"accountName": "Business Name"
 *				}
 *			]
 *		}
 *
 * GET location_id:
 * 1. Make a GET request to:
 *		https://mybusinessaccountmanagement.googleapis.com/v1/accounts/{account_id}/locations?read_mask=name,title
 *		The MB effect default location_id is 4075248256453071549.
 *		The Location ID can also be found in the Google My Business dashboard URL:
 *		https://business.google.com/manage/n/{location_id}/profile
 * 2. Use the `name` value from the response without the 'locations/' prefix.
 *		Example response:
 *		{
 *			"locations": [
 *				{
 *					"name": "locations/0987654321",
 *					"title": "Location Name"
 *				}
 *			]
 *		}
 *		To paginate, append &pageToken={next_page_token} to the request URL.
 *
 * -------------------------------------------
 * VERIFICATION
 * -------------------------------------------
 * 1. Test the API connection using the script.
 * 2. Verify the XML file (review_data.xml) is being created and updated in the uploads folder.
 * 3. Ensure the cron job runs daily at the scheduled time (3:00 AM UTC).
 *
 * -------------------------------------------
 * TROUBLESHOOTING
 * -------------------------------------------
 * - Check error logs for OAuth or API errors.
 * - Verify the credentials and API permissions.
 * - Ensure the cron job is correctly scheduled, this requires a theme switch.
 */

// Initialize reviews after WordPress and ACF are ready
add_action('init', function () {
	if (!function_exists('get_field')) return;

	// Configuration
	$reviews = get_field('review_settings', 'option');

	if (empty($reviews['enabled']) || empty($reviews['account_id']) || empty($reviews['location_id'])) return;

	$client_id = sanitize_text_field($reviews['client_id'] ?? '');
	$client_secret = sanitize_text_field($reviews['client_secret'] ?? '');
	$refresh_token = sanitize_text_field($reviews['refresh_token'] ?? '');
	$api_base_url = sanitize_text_field($reviews['api_base_url'] ?? 'https://mybusiness.googleapis.com/v4');
	$account_id = sanitize_text_field($reviews['account_id']);
	$location_id = sanitize_text_field($reviews['location_id']);

	// Path to uploads directory
	$uploads_dir = wp_upload_dir();
	$xml_file = trailingslashit($uploads_dir['basedir']) . 'review_data.xml';

	/**
	 * Fetch Access Token
	 */
	function get_google_access_token($client_id, $client_secret, $refresh_token) {
		$url = 'https://oauth2.googleapis.com/token';
		$data = [
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'refresh_token' => $refresh_token,
			'grant_type' => 'refresh_token'
		];

		$response = wp_remote_post($url, [
			'body' => $data,
			'timeout' => 30,
		]);

		if (is_wp_error($response)) {
			error_log('Google OAuth Error: ' . $response->get_error_message());
			return null;
		}

		$body = json_decode(wp_remote_retrieve_body($response), true);

		if (isset($body['error'])) {
			error_log('Google OAuth API Error: ' . $body['error'] . ' - ' . ($body['error_description'] ?? 'No description'));
			return null;
		}

		return $body['access_token'] ?? null;
	}

	/**
	 * Fetch Google Reviews
	 */
	function fetch_google_reviews($access_token, $api_base_url, $account_id, $location_id) {
		$url = "{$api_base_url}/accounts/{$account_id}/locations/{$location_id}/reviews";

		$response = wp_remote_get($url, [
			'headers' => [
				'Authorization' => "Bearer {$access_token}"
			],
			'timeout' => 30,
		]);

		if (is_wp_error($response)) {
			error_log('Google Reviews API Error: ' . $response->get_error_message());
			return [];
		}

		$body = json_decode(wp_remote_retrieve_body($response), true);

		if (isset($body['error'])) {
			$error_message = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown error';
			$error_description = isset($body['error']['details']) ? json_encode($body['error']['details']) : 'No description';
			error_log('Google Reviews API Error: ' . $error_message . ' - ' . $error_description);
			return [];
		}

		return $body;
	}

	/**
	 * Extract Original Content from Comment
	 * Removes Google-added metadata and translation markers to return clean review text.
	 *
	 * @param string $comment The raw comment text from Google Reviews API
	 * @return string The cleaned comment text
	 */
	function extract_original_comment($comment) {
		// Extract text after "(Original)" marker
		if (strpos($comment, '(Original)') !== false) {
			$parts = explode('(Original)', $comment, 2);
			$comment = trim($parts[1]);
		}

		// Remove "(Translated by Google)" if present
		$comment = preg_replace('/\(Translated by Google\)\s*/i', '', $comment);

		// Split comment into lines and take only the first paragraph (original language text)
		// Google reviews with translations have the original text first, then excessive whitespace, then translated text
		$lines = preg_split('/\n\s*\n/', $comment, 2);
		if ($lines === false) {
			return trim($comment);
		}
		$comment = trim($lines[0]);

		return $comment;
	}

	/**
	 * Convert Star Rating to Numeric
	 */
	function convert_star_rating($rating) {
		$rating_map = [
			'ONE' => '1',
			'TWO' => '2',
			'THREE' => '3',
			'FOUR' => '4',
			'FIVE' => '5',
		];

		return $rating_map[$rating] ?? '0';
	}

	/**
	 * Format Date to d.m.Y
	 */
	function format_date($date) {
		$timestamp = strtotime($date);
		return $timestamp ? date('d.m.Y', $timestamp) : '';
	}

	/**
	 * Save Reviews, Average Rating, and Total Count to XML
	 */
	function save_reviews_to_xml($response, $file_path) {
		$xml = new SimpleXMLElement('<googleReviews/>');

		// Add general data
		if (isset($response['averageRating'])) {
			$xml->addChild('averageRating', htmlspecialchars((string)$response['averageRating']));
		}
		if (isset($response['totalReviewCount'])) {
			$xml->addChild('totalReviewCount', htmlspecialchars((string)$response['totalReviewCount']));
		}

		// Add individual reviews
		// https://developers.google.com/my-business/reference/rest/v4/accounts.locations.reviews
		if (!empty($response['reviews'])) {
			$reviews_node = $xml->addChild('reviews');
			foreach ($response['reviews'] as $review) {
				$review_node = $reviews_node->addChild('review');

				$review_node->addChild('reviewer', htmlspecialchars($review['reviewer']['displayName'] ?? 'Anonymous'));
				$review_node->addChild('comment', htmlspecialchars(extract_original_comment($review['comment'] ?? '')));
				$review_node->addChild('rating', htmlspecialchars(convert_star_rating($review['starRating'] ?? '')));
				$review_node->addChild('date', htmlspecialchars(format_date($review['updateTime'] ?? '')));
				$review_node->addChild('image', htmlspecialchars($review['reviewer']['profilePhotoUrl'] ?? ''));
			}
		}

		$xml->asXML($file_path);
		error_log('Reviews saved with numeric star ratings and formatted dates to ' . $file_path);
	}

	/**
	 * Main Execution
	 */
	$should_update = !file_exists($xml_file);

	if ($should_update && !empty($client_id) && !empty($client_secret) && !empty($refresh_token) && !empty($account_id) && !empty($location_id)) {
		$access_token = get_google_access_token($client_id, $client_secret, $refresh_token);

		if ($access_token) {
			$response = fetch_google_reviews($access_token, $api_base_url, $account_id, $location_id);

			if (!empty($response)) {
				save_reviews_to_xml($response, $xml_file);
			} else {
				error_log("No reviews found or failed to fetch.");
			}
		} else {
			error_log("Failed to get access token.");
		}
	}
}, 20); // Priority 20 to ensure ACF is fully loaded


// Hook Function to the Scheduled Event
function fetch_google_reviews_cron() {
	if (!function_exists('get_field')) return;

	$reviews = get_field('review_settings', 'option');

	if (empty($reviews['enabled']) || empty($reviews['account_id']) || empty($reviews['location_id'])) return;

	$client_id = sanitize_text_field($reviews['client_id'] ?? '');
	$client_secret = sanitize_text_field($reviews['client_secret'] ?? '');
	$refresh_token = sanitize_text_field($reviews['refresh_token'] ?? '');
	$api_base_url = sanitize_text_field($reviews['api_base_url'] ?? 'https://mybusiness.googleapis.com/v4');
	$account_id = sanitize_text_field($reviews['account_id']);
	$location_id = sanitize_text_field($reviews['location_id']);

	$uploads_dir = wp_upload_dir();
	$xml_file = trailingslashit($uploads_dir['basedir']) . 'review_data.xml';

	$access_token = get_google_access_token($client_id, $client_secret, $refresh_token);

	if ($access_token) {
		$response = fetch_google_reviews($access_token, $api_base_url, $account_id, $location_id);

		if (!empty($response)) {
			save_reviews_to_xml($response, $xml_file);
		}
	}
}
add_action('fetch_google_reviews_event', 'fetch_google_reviews_cron');

/**
 * Handle Cron Scheduling on Options Page Save
 */
add_action('acf/options_page/save', function ($post_id, $menu_slug) {
	// Only run on review-settings options page
	if ($menu_slug !== 'review-settings') return;

	// Get the button value from the saved field
	$review_settings = get_field('review_settings', 'option');
	$cron_action = $review_settings['cron_button'] ?? '';

	if ($cron_action === 'schedule') {
		if (!wp_next_scheduled('fetch_google_reviews_event')) {
			wp_schedule_event(strtotime('tomorrow 03:00:00'), 'daily', 'fetch_google_reviews_event');
		}
		// Reset the button value
		$review_settings['cron_button'] = '';
		update_field('review_settings', $review_settings, 'option');
	} elseif ($cron_action === 'unschedule') {
		$timestamp = wp_next_scheduled('fetch_google_reviews_event');
		if ($timestamp) {
			wp_unschedule_event($timestamp, 'fetch_google_reviews_event');
		}
		// Reset the button value
		$review_settings['cron_button'] = '';
		update_field('review_settings', $review_settings, 'option');
	}
}, 20, 2);
