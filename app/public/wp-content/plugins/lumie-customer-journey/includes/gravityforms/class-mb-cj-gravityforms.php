<?php defined('ABSPATH') || exit;

class MB_CJ_GravityForms {

	const META_KEY = '_mb_cj_journey';
	const POST_FIELD = 'mb_cj_journey';

	public static function format_timestamp(string $raw): string {

		if (empty($raw)) {
			return '—';
		}

		$unix = strtotime($raw);

		if ($unix === false) {
			return $raw;
		}

		$dt = new DateTime("@$unix");
		$dt->setTimezone(new DateTimeZone('Europe/Amsterdam'));
		return $dt->format('d M Y, H:i');
	}

	public static function register_hooks(): void {

		if (!class_exists('GFForms')) {
			return;
		}

		add_filter('gform_entry_meta', ['MB_CJ_GF_Meta', 'register_entry_meta'], 10, 2);
		add_action('gform_entry_created', ['MB_CJ_GF_Submission', 'save_journey_meta'], 10, 2);
		add_filter('gform_entry_detail_meta_boxes', ['MB_CJ_GF_Metabox', 'add_metabox'], 10, 3);
		add_filter('gform_entries_column_filter', ['MB_CJ_GF_Column', 'format_column_value'], 10, 5);
		add_filter('gform_custom_merge_tags', ['MB_CJ_GF_Merge_Tags', 'register_merge_tag'], 10, 4);
		add_filter('gform_notification', ['MB_CJ_GF_Merge_Tags', 'process_notification'], 10, 3);
		add_filter('gform_replace_merge_tags', ['MB_CJ_GF_Merge_Tags', 'replace_merge_tag'], 10, 7);
	}
}

// Sub-classes are loaded after MB_CJ_GravityForms so they can reference its constants.
require_once __DIR__ . '/class-mb-cj-gf-meta.php';
require_once __DIR__ . '/class-mb-cj-gf-submission.php';
require_once __DIR__ . '/class-mb-cj-gf-metabox.php';
require_once __DIR__ . '/class-mb-cj-gf-merge-tags.php';
require_once __DIR__ . '/class-mb-cj-gf-column.php';
