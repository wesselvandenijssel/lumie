<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$popup_id = $popup_id ?? '';
$title = $title ?? '';
$content = $content ?? [];

if (empty($popup_id)) return;
?>

<div class="popup" data-popup="<?= esc_attr($popup_id); ?>" role="dialog" aria-modal="true" aria-labelledby="popup-title-<?= esc_attr($popup_id); ?>">
	<div class="popup__close" role="button" tabindex="0" aria-label="<?= esc_attr__('Sluit popup', 'lumie'); ?>"></div>
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
		</div>
	</div>
</div>
