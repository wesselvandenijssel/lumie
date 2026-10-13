<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The Template for displaying all single archives.
 *
 * @package lumie
 */

if (!function_exists('get_field')) {
	echo 'Activate ACF for this theme to work';
	return;
}

$archive_title = is_post_type_archive() ? post_type_archive_title('', false) : single_term_title('', false);
$archive_description = is_category() || is_tax() ? term_description() : '';

get_header(); ?>

<div id="primary" class="content-area">
	<section class="section blog pad--top-medium pad--bottom-medium">
		<div class="columns-12 center">

			<div class="titles">

				<h1 class="main-title default">
					<?= esc_html($archive_title); ?>
				</h1>

				<?php if (!empty($archive_description)) : ?>
					<?= wp_kses_post($archive_description); ?>
				<?php endif; ?>

			</div>

			<div class="blog__grid">

				<?php if (have_posts()) :
					while (have_posts()) : the_post();
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
						]);
					endwhile;
				else :
					get_template_part('content', 'none');
				endif; ?>

			</div>

			<?php wpex_pagination(); ?>
		</div>
	</section>
</div>
<?php get_footer(); ?>
