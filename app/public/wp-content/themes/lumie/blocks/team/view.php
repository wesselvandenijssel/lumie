<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Team Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'team'],
]);

$selection = $selection ?? 'all';

$args = [
	'post_type' => 'team_member',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => [
		'menu_order' => 'ASC',
		'date' => 'DESC',
	],
	'meta_query' => [
		[
			'key' => '_thumbnail_id',
			'compare' => 'EXISTS',
		],
	],
];

if ($selection === 'specific') {
	if (empty($team_members)) return;

	$args['post__in'] = $team_members;
	$args['orderby'] = 'post__in';
}

$query = new WP_Query($args);

if (!$query->have_posts()) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="team__content">
			<?php if (!empty($block_title['main_title'])) {
				layout("title", [
					'title' => $block_title,
					'block' => $block,
				]);
			} ?>

			<?php if (!empty($content)) : ?>
				<div class="team__text content-layout">
					<?= wp_kses_post($content); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="team__grid">
			<?php while ($query->have_posts()) : $query->the_post();
				component('team-member', [
					'image' => get_post_thumbnail_id(),
					'name' => get_the_title(),
					'job' => get_the_excerpt(),
				]);
			endwhile;

			wp_reset_postdata(); ?>
		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
