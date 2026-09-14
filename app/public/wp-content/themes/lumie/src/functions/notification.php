<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

function notification() {
	$notification_cf = get_field('notification', 'option');

	if (empty($notification_cf['text'])) return;

	$now = date('d-m-Y H:i:s');
	$now_timestamp = strtotime($now);

	// From
	if (!empty($notification_cf['show_from'])) {
		$start_datetime = date($notification_cf['show_from']);
		$start_datetime_timestamp = strtotime($start_datetime);

		if ($now_timestamp < $start_datetime_timestamp) return;
	}

	// Until
	if (!empty($notification_cf['show_until'])) {
		$end_datetime = date($notification_cf['show_until']);
		$end_datetime_timestamp = strtotime($end_datetime);

		if ($now_timestamp > $end_datetime_timestamp) return;
	}

?>
	<div class="notification">
		<div class="columns-12 center notification__grid">
			<div class="notification__text">
				<?= $notification_cf['text']; ?>
			</div>
			<div class="notification__close">
				<?= esc_html__('sluiten', 'lumie'); ?>
				<div class="notification__close-icon"></div>
			</div>
		</div>
	</div>
<?php
}
