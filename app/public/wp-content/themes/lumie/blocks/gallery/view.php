<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Galerij Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'gallery'],
]);

if (empty($gallery)) return;

$rows = [];

foreach ($gallery as $row) {
	switch ($row['acf_fc_layout']) {
		case 'image':
			if (empty($row['image'])) break;

			$rows[] = [
				'modifier' => 'single',
				'items' => [
					[
						'image' => $row['image'],
						'video' => $row['video'] ?? '',
						'size' => 'Gallery full',
						'modifier' => 'full',
					],
				],
			];
			break;

		case 'images':
			$large_image = $row['large_image'] ?? [];

			if (empty($large_image['image'])) break;

			$items = [
				[
					'image' => $large_image['image'],
					'video' => $large_image['video'] ?? '',
					'size' => 'Gallery XL',
					'modifier' => 'large',
				],
			];

			foreach ($row['images'] ?? [] as $small_image) {
				if (empty($small_image['image'])) continue;

				$items[] = [
					'image' => $small_image['image'],
					'video' => $small_image['video'] ?? '',
					'size' => 'Gallery',
					'modifier' => 'small',
				];
			}

			$rows[] = [
				'modifier' => !empty($row['layout']) ? $row['layout'] : 'large',
				'items' => $items,
			];
			break;
	}
}

if (empty($rows)) return;

$fancybox_group = 'gallery-' . ($block['id'] ?? '');

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="gallery__rows">
			<?php foreach ($rows as $row) : ?>

				<div class="gallery__grid gallery__grid--<?= esc_attr($row['modifier']); ?>">
					<?php foreach ($row['items'] as $item) :
						$item_attr = [];
						$item_attr['class'][] = 'gallery__item';
						$item_attr['class'][] = 'gallery__item--' . $item['modifier'];
						$item_attr['data-fancybox'] = $fancybox_group;

						if (!empty($item['video'])) {
							$item_attr = video_in_fancybox($item['video'], $item_attr);
						}

						$item_attr['href'] = !empty($item_attr['data-src'])
							? $item_attr['data-src']
							: esc_url(wp_get_attachment_image_url($item['image'], 'full'));
					?>

						<a <?php attr($item_attr); ?>>
							<?= wp_get_attachment_image($item['image'], $item['size'], false, ['class' => 'gallery__image', 'loading' => 'lazy']); ?>
						</a>
					<?php endforeach; ?>
				</div>

			<?php endforeach; ?>
		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
