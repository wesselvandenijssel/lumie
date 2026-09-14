<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$image = $image ?? 0;
$suptitle = $suptitle ?? '';
$title = $title ?? '';
$description = $description ?? '';

if (empty($title) || empty($image)) return;
?>

<article class="step">
	<div class="step__image-wrapper">
		<?= wp_get_attachment_image($image, 'Method', false, ['class' => 'step__image', 'loading' => 'lazy']); ?>
	</div>

	<div class="step__content">
		<?php if (!empty($suptitle)) : ?>
			<div class="step__suptitle">
				<?= esc_html($suptitle); ?>
			</div>
		<?php endif; ?>

		<h3 class="step__title">
			<?= esc_html($title); ?>
		</h3>

		<?php if (!empty($description)) : ?>
			<div class="step__description content-layout">
				<?= wp_kses_post($description); ?>
			</div>
		<?php endif; ?>
	</div>
</article>
