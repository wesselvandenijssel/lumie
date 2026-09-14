<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Citaat slider Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'quote-slider'],
]);

if (empty($quotes)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">
		<div class="quote-slider__slider swiper">
			<div class="swiper-wrapper">
				<?php foreach ($quotes as $item) :
					$team_member_id = $item['team_member'] ?? 0;
					$quote = $item['quote'] ?? '';

					if (empty($team_member_id) || empty($quote)) continue;

					$portrait = get_post_thumbnail_id($team_member_id);
					$name = get_the_title($team_member_id);
					$role = get_the_excerpt($team_member_id);

					$project_id = $item['project'] ?? 0;
					$project_image = !empty($project_id) ? get_post_thumbnail_id($project_id) : 0;
				?>

					<div class="quote-slider__slide swiper-slide">
						<?php if (!empty($portrait)) : ?>
							<figure class="quote-slider__portrait">
								<?= wp_get_attachment_image($portrait, 'Quote slider', false, ['class' => 'quote-slider__portrait-image', 'loading' => 'lazy']); ?>
							</figure>
						<?php endif; ?>

						<div class="quote-slider__content">
							<div class="quote-slider__details">
								<blockquote class="quote-slider__quote">
									<?= wp_kses_post($quote); ?>
								</blockquote>

								<div class="quote-slider__author">
									<?php if (!empty($name)): ?>
										<cite class="quote-slider__author-name"><?= esc_html($name); ?></cite>
									<?php endif; ?>

									<?php if (!empty($role)) : ?>
										<span class="quote-slider__author-role"><?= esc_html($role); ?></span>
									<?php endif; ?>
								</div>
							</div>

							<?php if (!empty($project_image)) : ?>
								<div class="quote-slider__favorite">
									<a class="quote-slider__favorite-label" href="<?= esc_url(get_permalink($project_id)); ?>" aria-label="<?= esc_attr__('Bekijk dit project', 'mbeffect'); ?>" title="<?= esc_attr(get_the_title($project_id)); ?>">
										<?= esc_html__('Favoriete project', 'mbeffect'); ?>
									</a>

									<a href="<?= esc_url(get_permalink($project_id)); ?>" class="quote-slider__favorite-link" title="<?= esc_attr(get_the_title($project_id)); ?>" aria-label="<?= esc_attr__('Bekijk dit project', 'mbeffect'); ?>">
										<span class="quote-slider__favorite-title"><?= esc_html(get_the_title($project_id)); ?></span>
										<?= wp_get_attachment_image($project_image, 'Project Portrait', false, ['class' => 'quote-slider__favorite-image', 'loading' => 'lazy']); ?>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="quote-slider__pagination swiper-pagination"></div>
		</div>
	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
