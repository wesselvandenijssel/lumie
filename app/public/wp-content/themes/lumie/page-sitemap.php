<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 *	Template Name: Sitemap
 */

get_header();
?>
<div id="primary" class="content-area content-sidebar columns-12 center">
	<?php
	while (have_posts()) : the_post();
		get_template_part('content', 'page');
	endwhile;
	?>
	<section class="sitemap pad--top-large pad--bottom-medium">
		<h1><?= esc_html__('Sitemap', 'lumie'); ?></h1>

		<div class="sitemap__sections">
			<?php foreach (lumie_sitemap_sections(get_queried_object_id()) as $section) : ?>
				<div class="sitemap__section">
					<h2 class="sitemap__title"><?= esc_html($section['title']); ?></h2>
					<?php lumie_sitemap_list($section['items']); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
</div>
<?php get_footer(); ?>
