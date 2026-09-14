<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Vacancies Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'vacancies'],
]);

if (empty($selection)) return;

$posts_per_page = 12;

$is_overview = ($selection === 'newest' || $selection === 'random') && intval($amount) === -1;

$args = [
	'post_type' => 'vacancy',
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
		$args['post__in'] = $vacancies ?? [];
		$args['orderby'] = 'post__in';
		$args['posts_per_page'] = -1;
		break;
}

if ($is_overview) {
	$args['posts_per_page'] = $posts_per_page;

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

		<div class="vacancies__grid">
			<?php if ($query->have_posts()) :
				while ($query->have_posts()) : $query->the_post();
					component('card', [
						'image' => get_post_thumbnail_id(),
						'title' => get_the_title(),
						'link' => [
							'url' => get_permalink(),
							'title' => get_the_title(),
							'target' => '_self',
						],
					]);
				endwhile;
			endif; ?>

			<?php wp_reset_postdata(); ?>
		</div>

		<?php if ($is_overview) wpex_pagination_outside_query($total, $block['id']); ?>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
