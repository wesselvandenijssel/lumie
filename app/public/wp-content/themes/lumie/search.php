<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The template for displaying Search Results pages.
 *
 * @package mbeffect
 */

get_header(); ?>

<div id="primary" class="content-area pad--top-large pad--bottom-large">

	<section class="section centered-content pad--bottom-medium">
		<div class="columns-12 center">
			<div class="centered-content__wrapper">

				<div class="titles">
					<h1 class="main-title default">
						<?= wp_kses_post(sprintf(__('Zoekresultaten voor: <span>%s</span>', 'mbeffect'), get_search_query())); ?>
					</h1>
				</div>

				<div class="content-layout">

					<?php if (have_posts()) : ?>

						<?php while (have_posts()) : the_post(); ?>

							<?php get_template_part('content', 'search'); ?>

						<?php endwhile; ?>

						<?php wpex_pagination(); ?>

					<?php else : ?>

						<?php get_template_part('content', 'none'); ?>

					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>
