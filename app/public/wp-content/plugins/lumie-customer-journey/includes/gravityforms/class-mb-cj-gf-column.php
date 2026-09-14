<?php defined('ABSPATH') || exit;

class MB_CJ_GF_Column {

	public static function format_column_value($value, $_form_id, $column, $entry, $_query_string) {

		if ($column !== MB_CJ_GravityForms::META_KEY || empty($entry['id'])) {
			return $value;
		}

		$raw = gform_get_meta($entry['id'], MB_CJ_GravityForms::META_KEY);
		$journey = !empty($raw) ? json_decode($raw, true) : null;

		if (!is_array($journey) || empty($journey)) {
			return '<span style="color:#bbb;">—</span>';
		}

		$total = count($journey);

		// Unique paths in order of first visit.
		$seen = [];
		$unique_paths = [];

		foreach ($journey as $step) {
			$path = $step['path'] ?? '';
			if ($path !== '' && !in_array($path, $seen, true)) {
				$seen[] = $path;
				$unique_paths[] = $path;
			}
		}

		$unique_count = count($unique_paths);
		$shown = array_slice($unique_paths, 0, 3);
		$remaining = $unique_count - count($shown);

		$path_parts = [];
		foreach ($shown as $path) {
			$path_parts[] = '<span style="white-space:nowrap;">'
				. htmlspecialchars($path, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
				. '</span>';
		}

		$path_html = implode(' <span style="color:#bbb;">›</span> ', $path_parts);

		if ($remaining > 0) {
			$path_html .= sprintf(' <span style="color:#999;white-space:nowrap;">+%d more</span>', $remaining);
		}

		return sprintf(
			'<span style="display:block;font-weight:600;margin-bottom:3px;font-size:12px;">%d visit%s &middot; %d page%s</span>'
				. '<span style="display:block;font-size:11px;color:#555;line-height:1.6;">%s</span>',
			$total,
			$total !== 1 ? 's' : '',
			$unique_count,
			$unique_count !== 1 ? 's' : '',
			$path_html
		);
	}
}
