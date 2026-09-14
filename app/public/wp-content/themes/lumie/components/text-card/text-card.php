<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$image = $image ?? 0;
$suptitle = $suptitle ?? '';
$title = $title ?? '';
$content = $content ?? '';
$link = $link ?? [];

if (empty($title) || empty($image)) return;

$card_attr['class'][] = 'text-card';

if (!empty($link['url'])) {
	$card_attr['href'] = [esc_url($link['url'])];
	$card_attr['title'] = [esc_attr($link['title'] ?? '')];
	$card_attr['target'] = [esc_attr(!empty($link['target']) ? $link['target'] : '_self')];
}
?>

<?php if (!empty($link['url'])) : ?>
	<a <?php attr($card_attr); ?>>
	<?php else : ?>
		<article <?php attr($card_attr); ?>>
		<?php endif; ?>

		<div class="text-card__image-wrapper">
			<?= wp_get_attachment_image($image, 'Text card', false, ['class' => 'text-card__image', 'loading' => 'lazy']); ?>
		</div>

		<div class="text-card__content">
			<?php if (!empty($suptitle)) : ?>
				<span class="text-card__suptitle">
					<?= esc_html($suptitle); ?>
				</span>
			<?php endif; ?>

			<h3 class="text-card__title">
				<?= esc_html($title); ?>
			</h3>

			<?php if (!empty($content)) : ?>
				<div class="text-card__text">
					<?= wp_kses_post($content); ?>
				</div>
			<?php endif; ?>

			<?php if (!empty($link['url'])) : ?>
				<span class="text-card__link"><?= esc_html__('Bekijk meer', 'lumie'); ?></span>
			<?php endif; ?>
		</div>

		<?php if (empty($link['url'])) : ?>
		</article>
	<?php else: ?>
	</a>
<?php endif; ?>
