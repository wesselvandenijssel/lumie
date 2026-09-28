<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Electrolytes Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'electrolytes'],
]);

$items = $items ?? [];

if (empty($block_title['main_title']) && empty($content) && empty($items)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="electrolytes__grid">
			<div class="electrolytes__content">
				<?php if (!empty($block_title['main_title'])) {
					layout("title", [
						'title' => $block_title,
						'block' => $block,
					]);
				}

				if (!empty($content)) {
					layout("content", [
						'content' => $content,
						'block' => $block,
					]);
				} ?>
			</div>

			<?php if (!empty($items)) : ?>
				<div class="electrolytes__slider-wrapper">
					<div class="electrolytes__slider swiper">
						<ul class="electrolytes__items swiper-wrapper">
							<?php foreach ($items as $item) : ?>
								<?php if (empty($item['title'])) continue; ?>

								<li class="electrolytes__item swiper-slide">
									<?php if (!empty($item['symbol'])) : ?>
										<span class="electrolytes__symbol" aria-hidden="true"><?= esc_html($item['symbol']); ?></span>
									<?php endif; ?>

									<h3 class="electrolytes__item-title"><?= esc_html($item['title']); ?></h3>

									<?php if (!empty($item['description'])) : ?>
										<p class="electrolytes__item-description"><?= esc_html($item['description']); ?></p>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<div class="electrolytes__pagination swiper-pagination"></div>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
