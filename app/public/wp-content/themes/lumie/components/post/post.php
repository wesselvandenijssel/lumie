<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$title = $title ?? '';
$image = $image ?? 0;
$categories = $categories ?? [];
$link = $link ?? [];
$author = $author ?? '';
$date = $date ?? '';
$swiper = $swiper ?? false;

if (empty($title) || empty($image)) return;

$post_attr['class'][] = 'post';

if (!empty($link['url'])) {
	$post_attr['href'] = [esc_url($link['url'])];
	$post_attr['title'] = [esc_attr($link['title'] ?? '')];
	$post_attr['target'] = [esc_attr(!empty($link['target']) ? $link['target'] : '_self')];
}

if (!empty($swiper)) {
	$post_attr['class'][] = 'swiper-slide';
	$post_attr['class'][] = 'post--swiper';
}
?>

<?php if (!empty($link['url'])) : ?>
	<a <?php attr($post_attr); ?>>
	<?php else : ?>
		<div <?php attr($post_attr); ?>>
		<?php endif; ?>
		<div class="post__image-wrapper">
			<?= wp_get_attachment_image($image, 'Post', false, ['class' => 'post__image', 'loading' => 'lazy']); ?>

			<?php if (!empty($categories)) : ?>
				<p class="post__label">
					<?= implode(', ', wp_list_pluck($categories, 'name')); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="post__content">
			<div class="post__meta">
				<?php
				// Build meta items array
				$meta_items = [];

				if (!empty($author)) {
					$meta_items[] = '<span class="post__meta-item post__meta-item--author">' . esc_html__('Door:', 'mbeffect') . ' ' . esc_html($author) . '</span>';
				}

				if (!empty($date)) {
					$meta_items[] = '<span class="post__meta-item post__meta-item--date">' . esc_html($date) . '</span>';
				}

				// Output meta items with separator
				echo implode('<span class="post__separator">|</span>', $meta_items);
				?>
			</div>

			<h3 class="post__title">
				<?= esc_html($title); ?>
			</h3>


			<span class="btn btn--read-more post__button"><?= esc_html__('Lees verder', 'mbeffect'); ?></span>
		</div>
		<?php if (empty($link['url'])) : ?>
		</div>
	<?php else: ?>
	</a>
<?php endif; ?>
