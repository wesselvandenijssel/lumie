<?php defined('ABSPATH') || exit;

class MB_CJ_GF_Meta {

	public static function register_entry_meta(array $entry_meta, $_form_id): array {

		$entry_meta[MB_CJ_GravityForms::META_KEY] = [
			'label' => __('MB | Customer Journey', 'mb-customer-journey'),
			'is_numeric' => false,
			'is_default_column' => false,
		];

		return $entry_meta;
	}
}
