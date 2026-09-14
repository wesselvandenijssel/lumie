<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Projects Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'projects'],
]);

if (empty($selection)) return;

$posts_per_page = 12;

$is_overview = $selection !== 'random' && intval($amount ?? 0) === -1;

$args = [
	'post_type' => 'project',
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
		$args['post__in'] = $projects ?? [];
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
		<div class="projects__content">
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
	</div>

	<div class="projects__grid">
		<?php if ($query->have_posts()) :
			$project_counter = 0;
			while ($query->have_posts()) : $query->the_post();
				$project_counter++;

				$thumbnail = $project_counter === 1 ? 'Project XL' : match ($project_counter % 3) {
					0 => 'Project Portrait',
					default => 'Project Landscape',
				};

				component('project', [
					'image' => get_post_thumbnail_id(),
					'title' => get_the_title(),
					'suptitle' => get_the_excerpt(get_the_ID()),
					'thumbnail' => $thumbnail,
					'link' => [
						'url' => get_permalink(),
						'title' => get_the_title(),
						'target' => '_self',
					],
				]);
			endwhile;
		endif; ?>
	</div>

	<?php wp_reset_postdata(); ?>

	<?php if ($is_overview) wpex_pagination_outside_query($total, $block['id'] ?? ''); ?>

	<?php if (!empty($buttons_group)) : ?>
		<div class="columns-12 center">
			<?php
			$buttons = new BlockButtons($buttons_group);
			echo $buttons->get_buttons();
			?>
		</div>
	<?php endif; ?>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
