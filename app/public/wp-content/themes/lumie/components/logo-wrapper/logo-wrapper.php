<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$logos = $logos ?? [];
$text = $text ?? '';
$shuffle = $shuffle ?? false;
$max_amount = $max_amount ?? false;
$swiper = $swiper ?? false;

$logo_counter = 0;

if (empty($logos)) return;

if ($shuffle)
	shuffle($logos);

$logo_wrapper['class'][] = 'logo-wrapper';
$logo_wrapper_classes['class'][] = 'logo-wrapper__logos';

if (!empty($swiper)) {
	$logo_wrapper['class'][] = 'swiper';
	$logo_wrapper_classes['class'][] = 'swiper-wrapper';
}
?>

<div <?php attr($logo_wrapper); ?>>
	<?php if (!empty($text)) : ?>
		<div class="logo-wrapper__item logo-wrapper__item--text">
			<?= $text; ?>
		</div>
	<?php endif; ?>

	<div <?php attr($logo_wrapper_classes); ?>>

		<?php foreach ($logos as $logo) : ?>
			<?php if (empty($logo['logo'])) continue;

			if (!empty($max_amount) && $logo_counter >= $max_amount) continue;

			$logo_classes = [];
			$logo_classes['class'][] = 'logo-wrapper__item';
			$logo_classes['class'][] = 'logo-wrapper__item--logo';

			if (!empty($swiper)) {
				$logo_classes['class'][] = 'swiper-slide';
			}

			$logo_counter++;
			?>

			<?php if (!empty($logo['link']['url'])) :
				$logo_classes['href'][] = esc_url($logo['link']['url']);
				$logo_classes['title'][] = esc_attr($logo['link']['title']);
				$logo_classes['target'][] = esc_attr(!empty($logo['link']['target']) ? $logo['link']['target'] : '_self'); ?>

				<a <?php attr($logo_classes); ?>>
				<?php else : ?>
					<div <?php attr($logo_classes); ?>>
					<?php endif; ?>

					<?= wp_get_attachment_image($logo['logo'], 'full', true); ?>

					<?php if (empty($logo['link']['url'])) : ?>
					</div>
				<?php else : ?>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>

	</div>
</div>
