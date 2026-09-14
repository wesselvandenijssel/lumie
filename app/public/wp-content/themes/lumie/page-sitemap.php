<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 *	Template Name: Sitemap
 */

get_header();
?>
<div id="primary" class="content-area content-sidebar columns-12 center">
	<section class="pad--top-medium pad--bottom-medium">
		<?php
		while (have_posts()) : the_post();
			get_template_part('content', 'page');
		endwhile;
		?>
		<h1><?= esc_html__('Sitemap', 'mbeffect'); ?></h1>
		<?php
		wp_nav_menu([
			'theme_location' => 'sitemap',
			'fallback_cb' => false,
		]);
		?>
	</section>
</div>
<?php get_footer(); ?>
