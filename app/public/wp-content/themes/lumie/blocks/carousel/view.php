<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Carrousel Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'carousel'],
]);

// Drop slides that miss the media for their chosen type
$slides = array_filter($slides ?? [], function ($slide) {
	return ($slide['type'] ?? 'image') === 'video' ? !empty($slide['video']) : !empty($slide['image']);
});

if (empty($slides)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">
		<?php if (!empty($block_title['main_title'])) {
			layout("title", [
				'title' => $block_title,
				'block' => $block,
			]);
		} ?>

		<div class="carousel__slider swiper">
			<div class="swiper-wrapper">
				<?php foreach ($slides as $slide) : ?>
					<div class="carousel__slide swiper-slide">
						<?php if (($slide['type'] ?? 'image') === 'video') : ?>
							<div class="carousel__media carousel__media--video"><?= $slide['video']; ?></div>
						<?php else :
							echo wp_get_attachment_image($slide['image'], 'Carousel', false, ['class' => 'carousel__media carousel__media--image', 'loading' => 'lazy']);
						endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if (count($slides) > 1) : ?>
			<div class="carousel__pagination swiper-pagination"></div>
		<?php endif; ?>
	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
