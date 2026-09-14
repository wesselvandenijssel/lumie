<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$popup_id = $popup_id ?? '';
$title = $title ?? '';
$content = $content ?? [];

if (empty($popup_id)) return;

$is_startup = !empty(get_field('startup', $popup_id));

// Legally required under the Reclamecode voor Alcoholhoudende Dranken: no close
// button, and popup.ts keeps it shut until a date of birth of 18 or older.
$is_age_gate = $is_startup && !empty(get_field('age_gate', $popup_id));

$attr = [
	'class' => ['popup'],
	'data-popup' => (string) $popup_id,
	'role' => 'dialog',
	'aria-modal' => 'true',
];

// Only when a heading is rendered below, or the reference dangles.
if (!empty($title)) {
	$attr['aria-labelledby'] = 'popup-title-' . $popup_id;
}

if ($is_startup) {
	$attr['class'][] = 'popup--startup';
	// An age gate blocks immediately; a delay would leave the site readable.
	$attr['data-popup-delay'] = $is_age_gate ? '0' : (string) (int) (get_field('startup_delay', $popup_id) ?? 3);
}

if ($is_age_gate) {
	$attr['class'][] = 'popup--age-gate';
}

$year_max = (int) current_time('Y');
?>

<div <?php attr($attr); ?>>
	<?php if (!$is_age_gate) : ?>
		<div class="popup__close" role="button" tabindex="0" aria-label="<?= esc_attr__('Sluit popup', 'lumie'); ?>"></div>
	<?php endif; ?>

	<div class="popup__content">
		<div class="text-center">
			<?php if (!empty($title)) : ?>
				<div class="titles">
					<h3 class="h2" id="popup-title-<?= esc_attr($popup_id); ?>">
						<?= wp_kses_post($title); ?>
					</h3>
				</div>
			<?php endif; ?>

			<?php if (!empty($content)) {
				layout("content", [
					'content' => $content,
				]);
			}; ?>

			<?php if ($is_age_gate) : ?>
				<form
					class="age-gate"
					novalidate
					data-error-incomplete="<?= esc_attr__('Vul je volledige geboortedatum in.', 'lumie'); ?>"
					data-error-invalid="<?= esc_attr__('Dat is geen geldige geboortedatum.', 'lumie'); ?>"
					data-error-underage="<?= esc_attr__('Je moet 18 jaar of ouder zijn om deze site te bekijken.', 'lumie'); ?>">
					<fieldset class="age-gate__fields">
						<legend class="age-gate__legend">
							<?= esc_html__('Vul je geboortedatum in', 'lumie'); ?>
						</legend>

						<div class="age-gate__row">
							<div class="age-gate__field">
								<label for="age-gate-day-<?= esc_attr($popup_id); ?>"><?= esc_html__('Dag', 'lumie'); ?></label>
								<input
									type="text"
									inputmode="numeric"
									autocomplete="bday-day"
									maxlength="2"
									placeholder="<?= esc_attr__('DD', 'lumie'); ?>"
									id="age-gate-day-<?= esc_attr($popup_id); ?>"
									class="age-gate__input"
									data-age-gate="day">
							</div>

							<div class="age-gate__field">
								<label for="age-gate-month-<?= esc_attr($popup_id); ?>"><?= esc_html__('Maand', 'lumie'); ?></label>
								<input
									type="text"
									inputmode="numeric"
									autocomplete="bday-month"
									maxlength="2"
									placeholder="<?= esc_attr__('MM', 'lumie'); ?>"
									id="age-gate-month-<?= esc_attr($popup_id); ?>"
									class="age-gate__input"
									data-age-gate="month">
							</div>

							<div class="age-gate__field age-gate__field--year">
								<label for="age-gate-year-<?= esc_attr($popup_id); ?>"><?= esc_html__('Jaar', 'lumie'); ?></label>
								<input
									type="text"
									inputmode="numeric"
									autocomplete="bday-year"
									maxlength="4"
									placeholder="<?= esc_attr__('JJJJ', 'lumie'); ?>"
									id="age-gate-year-<?= esc_attr($popup_id); ?>"
									class="age-gate__input"
									data-age-gate="year"
									data-year-max="<?= esc_attr((string) $year_max); ?>">
							</div>
							</div>
					</fieldset>

					<p class="age-gate__error" data-age-gate="error" role="alert" hidden></p>

					<button type="submit" class="btn btn--primary age-gate__submit">
						<span><?= esc_html__('Doorgaan', 'lumie'); ?></span>
					</button>

					<p class="age-gate__note">
						<?= esc_html__('Geen 18, geen alcohol.', 'lumie'); ?>
					</p>
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>
