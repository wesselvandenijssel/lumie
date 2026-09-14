<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Blog Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'blog'],
]);

if (empty($selection)) return;

$posts_per_page = 12;

$is_overview = ($selection === 'newest' || $selection === 'random') && intval($amount) === -1;

$args = [
	'post_type' => 'post',
	'post_status' => 'publish',
	'orderby' => 'date',
	'post__not_in' => [get_the_ID()],
	'posts_per_page' => $amount ?? $posts_per_page,
	'meta_query' => [
		[
			'key' => '_thumbnail_id',
			'compare' => 'EXISTS',
		],
	],
];

switch ($selection) {
	case 'random':
		$args['orderby'] = 'rand';
		break;

	case 'specific':
		$args['post__in'] = $posts ?? [];
		$args['orderby'] = 'post__in';
		$args['posts_per_page'] = -1;
		break;

	case 'category':
		if (isset($_GET['category']) && $_GET['category'] !== '*') break;

		$args['tax_query'] = [
			[
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms'	=> $category ?? 0,
				'operator' => 'IN',
			],
		];
		break;
}

if ($is_overview) {
	$args['posts_per_page'] = $posts_per_page;

	if (isset($_GET['category']) && $_GET['category'] !== '*') {
		$args['tax_query'] = [
			[
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => sanitize_text_field($_GET['category']),
				'operator' => 'IN',
			],
		];
	}

	if (isset($_GET['pagina'])) {
		$pagina = absint($_GET['pagina']);
		if ($pagina > 0) {
			$args['offset'] = $pagina * $posts_per_page - $posts_per_page;
		}
	}
}

$query = new WP_Query($args);

$total = $query->max_num_pages;
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

		<?php if ($is_overview) : ?>
			<form class="blog__filter" action="<?= !empty($block['id']) ? '#' . esc_attr($block['id']) : get_the_permalink(); ?>">
				<?php
				$terms = get_terms([
					'taxonomy' => 'category',
				]);

				if (!empty($terms)) : ?>
					<button type="submit" name="category" value="*" class="btn__filter<?= !isset($_GET['category']) || sanitize_text_field($_GET['category']) === '*' ? ' btn__filter--active' : ''; ?>">
						<?= esc_html__('Toon alle artikelen', 'lumie'); ?>
					</button>

					<?php foreach ($terms as $term) : ?>
						<button type="submit" name="category" value="<?= esc_attr($term->slug); ?>" class="btn__filter<?= isset($_GET['category']) && sanitize_text_field($_GET['category']) === esc_attr($term->slug) ? ' btn__filter--active' : ''; ?>">
							<?= esc_html($term->name); ?>
						</button>
					<?php endforeach; ?>
				<?php endif; ?>
			</form>

			<form class="blog__filter--mobile" method="GET" action="<?= !empty($block['id']) ? '#' . esc_attr($block['id']) : get_the_permalink(); ?>">
				<select class="blog__filter-select" name="category" aria-label="<?= esc_attr__('Filter', 'lumie'); ?>">
					<option value="*" <?= !isset($_GET['category']) ? 'selected' : ''; ?>>
						<?= esc_html__('Alle type blogs', 'lumie'); ?>
					</option>

					<?php if (!empty($terms)) : ?>
						<?php foreach ($terms as $term) : ?>
							<option value="<?= esc_attr($term->slug); ?>"
								<?= isset($_GET['category']) && esc_attr(sanitize_text_field($_GET['category'])) === esc_attr($term->slug) ? 'selected' : '' ?>>
								<?= esc_html($term->name); ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</form>
		<?php endif; ?>

		<?php
		$blog_grid_attr = [];
		$blog_grid_attr['class'][] = 'blog__grid';

		if (!$is_overview) {
			$blog_grid_attr['class'][] = 'blog__grid--swiper';
			$blog_grid_attr['class'][] = 'swiper';
		} else {
			$blog_grid_attr['class'][] = 'blog__grid--overview';
		}

		if (!empty($block['id'])) {
			$blog_grid_attr['id'] = $block['id'];
		}
		?>

		<div <?php attr($blog_grid_attr); ?>>
			<?php if (!$is_overview) : ?>
				<div class="swiper-wrapper">
				<?php endif; ?>

				<?php if ($query->have_posts()) :
					while ($query->have_posts()) : $query->the_post();
						component('post', [
							'title' => get_the_title(),
							'image' => get_post_thumbnail_id(),
							'categories' => get_the_terms(get_the_ID(), 'category') ?: [],
							'link' => [
								'url' => get_permalink(),
								'title' => get_the_title(),
								'target' => '_self',
							],
							'author' => get_the_author(),
							'date' => get_the_date('d M Y'),
							'swiper' => !$is_overview,
						]);
					endwhile;
				endif; ?>
				<?php if (!$is_overview) : ?>
				</div>

				<div class="blog__swiper-buttons swiper-buttons">
					<div
						class="blog__swiper-button blog__swiper-button--prev swiper-button-prev">
					</div>
					<div
						class="blog__swiper-button blog__swiper-button--next swiper-button-next">
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php wp_reset_postdata(); ?>

		<?php if ($is_overview) wpex_pagination_outside_query($total, $block['id']); ?>
	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
