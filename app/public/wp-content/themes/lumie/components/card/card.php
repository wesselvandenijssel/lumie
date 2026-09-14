<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$image = $image ?? 0;
$title = $title ?? '';
$link = $link ?? [];

if (empty($title) || empty($image)) return;

$card_attr['class'][] = 'card';

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

		<?= wp_get_attachment_image($image, 'Card', false, ['class' => 'card__image', 'loading' => 'lazy']); ?>

		<div class="card__content">
			<h3 class="card__title">
				<?= esc_html($title); ?>
			</h3>
		</div>

		<?php if (empty($link['url'])) : ?>
		</article>
	<?php else: ?>
	</a>
<?php endif; ?>
