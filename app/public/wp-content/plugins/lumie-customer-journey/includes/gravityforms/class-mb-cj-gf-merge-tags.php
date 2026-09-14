<?php defined('ABSPATH') || exit;

class MB_CJ_GF_Merge_Tags {

	public static function register_merge_tag(array $merge_tags, $_form_id, $_fields, $_element_id): array {

		$merge_tags[] = [
			'label' => __('MB | Customer Journey (all)', 'mb-customer-journey'),
			'tag' => '{mb_customer_journey}',
		];

		$merge_tags[] = [
			'label' => __('MB | Customer Journey (last 3)', 'mb-customer-journey'),
			'tag' => '{mb_customer_journey:3}',
		];

		return $merge_tags;
	}

	public static function process_notification(array $notification, array $_form, array $entry): array {

		$body = $notification['message'] ?? '';

		if (strpos($body, '{all_fields}') !== false && strpos($body, '{mb_customer_journey') === false) {
			$notification['message'] .= "\n\n{mb_customer_journey:3}";
			$body = $notification['message'];
		}

		if (empty($entry['id']) || strpos($body, '{mb_customer_journey') === false) {
			return $notification;
		}

		$raw = gform_get_meta($entry['id'], MB_CJ_GravityForms::META_KEY);
		$journey = !empty($raw) ? json_decode($raw, true) : null;

		$empty_html = '<p style="color:#999;font-size:12px;">' . esc_html__('No journey data recorded for this entry.', 'mb-customer-journey') . '</p>';

		$notification['message'] = preg_replace_callback(
			'/\{mb_customer_journey(?::(\d+))?\}/',
			function ($matches) use ($journey, $empty_html) {
				if (!is_array($journey) || empty($journey)) {
					return $empty_html;
				}
				$limit = isset($matches[1]) && $matches[1] !== '' ? (int) $matches[1] : 0;
				return self::render_email_html($journey, $limit);
			},
			$body
		);

		return $notification;
	}

	public static function replace_merge_tag($text, $_form, $entry, $_url_encode, $_esc_html, $_nl2br, $_format) {

		if (empty($entry['id']) || strpos($text, '{mb_customer_journey') === false) {
			return $text;
		}

		$raw = gform_get_meta($entry['id'], MB_CJ_GravityForms::META_KEY);
		$journey = !empty($raw) ? json_decode($raw, true) : null;

		$empty_html = '<p style="color:#999;font-size:12px;">' . esc_html__('No journey data recorded for this entry.', 'mb-customer-journey') . '</p>';

		return preg_replace_callback(
			'/\{mb_customer_journey(?::(\d+))?\}/',
			function ($matches) use ($journey, $empty_html) {
				if (!is_array($journey) || empty($journey)) {
					return $empty_html;
				}

				$limit = isset($matches[1]) && $matches[1] !== '' ? (int) $matches[1] : 0;

				return self::render_email_html($journey, $limit);
			},
			$text
		);
	}

	private static function render_email_html(array $journey, int $limit = 0): string {

		$font = 'font-family:sans-serif;font-size:12px;';
		$total = count($journey);

		$shown = $limit > 0 ? array_slice($journey, -$limit) : $journey;
		$shown_count = count($shown);
		$start_num = $limit > 0 ? $total - $shown_count + 1 : 1;
		$rows = '';

		foreach ($shown as $i => $step) {
			$bg = $i % 2 === 0 ? '#EAF2FA' : '#FFFFFF';
			$url = esc_url($step['url'] ?? '');
			$path = htmlspecialchars($step['path'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
			$title = htmlspecialchars($step['title'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
			$time = self::format_timestamp($step['timestamp'] ?? '');
			$page_label = $title ?: $path;
			$page_sub = $title
				? '<br><font style="' . $font . 'color:#999;">' . $path . '</font>'
				: '';

			$rows .= '<tr bgcolor="' . $bg . '">'
				. '<td width="20">&nbsp;</td>'
				. '<td width="30" valign="top"><font style="' . $font . 'color:#bbb;">' . $start_num + $i . '.</font></td>'
				. '<td valign="top"><font style="' . $font . '">'
				. '<a href="' . $url . '" style="color:#1a73e8;text-decoration:none;">' . $page_label . '</a>'
				. $page_sub
				. '</font></td>'
				. '<td valign="top" nowrap><font style="' . $font . 'color:#555;">' . $time . '</font></td>'
				. '</tr>';
		}

		$footer = ($limit > 0 && $shown_count < $total)
			? sprintf(
				_n('Showing last %1$d of %2$d page visit.', 'Showing last %1$d of %2$d page visits.', $total, 'mb-customer-journey'),
				$shown_count,
				$total
			)
			: sprintf(
				_n('%d page visit recorded.', '%d page visits recorded.', $total, 'mb-customer-journey'),
				$total
			);

		return '<table width="99%" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA">'
			. '<tr><td>'
			. '<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">'

			. '<tr bgcolor="#EAF2FA">'
			. '<td colspan="4"><font style="' . $font . '"><strong>' . esc_html__('Customer Journey', 'mb-customer-journey') . '</strong></font></td>'
			. '</tr>'

			. '<tr bgcolor="#FFFFFF">'
			. '<td width="20">&nbsp;</td>'
			. '<td width="30"><font style="font-family:sans-serif;font-size:11px;color:#999;">#</font></td>'
			. '<td><font style="font-family:sans-serif;font-size:11px;color:#999;">' . esc_html__('Page', 'mb-customer-journey') . '</font></td>'
			. '<td nowrap><font style="font-family:sans-serif;font-size:11px;color:#999;">' . esc_html__('Time', 'mb-customer-journey') . '</font></td>'
			. '</tr>'

			. $rows

			. '<tr bgcolor="#FFFFFF">'
			. '<td width="20">&nbsp;</td>'
			. '<td colspan="3"><font style="font-family:sans-serif;font-size:11px;color:#999;">' . $footer . '</font></td>'
			. '</tr>'

			. '</table>'
			. '</td></tr>'
			. '</table>';
	}

	private static function format_timestamp(string $raw): string {
		return MB_CJ_GravityForms::format_timestamp($raw);
	}
}
