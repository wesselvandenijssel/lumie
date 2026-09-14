<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The template used for displaying page content in author.php
 *
 * @package mbeffect
 */

?>

<div class="entry-content">
	<?php component('breadcrumb'); ?>

	<section class="section background has-background has-athens-gray-background-color">
		<section class="section content-image pad--top-large pad--bottom-large">
			<div class="columns-12 center">

				<div class="content-image__grid content-image__grid--unflip">

					<div class="content-image__content">
						<div class="titles">

							<h1 class="main-title default"><?= get_the_author(); ?></h1>

						</div>

						<div class="author__connect">

							<div class="author__socials">
								<?php
								$author_user_id = 'user_' . get_the_author_meta('ID');

								$socials = [
									'twitter' => get_field('twitter', $author_user_id),
									'linkedin' => get_field('linkedin', $author_user_id),
									'facebook' => get_field('facebook', $author_user_id),
									'instagram' => get_field('instagram', $author_user_id),
									'mail' => !empty(get_the_author_meta('user_email')) ? 'mailto:' . get_the_author_meta('user_email') : '',
									'phone' => !empty(get_field('phone', $author_user_id)) ? 'tel:' . preg_replace('/[^0-9+\s\-\(\)]/', '', get_field('phone', $author_user_id)) : '',
								];

								foreach ($socials as $key => $url) :
									if (empty($url)) continue;
								?>
									<a href="<?= esc_url($url); ?>" target="_blank" rel="noopener noreferrer" class="author__social-link author__social-link--<?= esc_attr($key); ?>">

										<span class="screen-reader-text"><?= esc_html(ucfirst($key)); ?></span>
									</a>

								<?php endforeach; ?>
							</div>
						</div>

						<?php if (!empty(get_the_author_meta('description'))) : ?>
							<div class="author__bio">
								<?= wpautop(wp_kses_post(get_the_author_meta('description'))); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php
					$author_image = get_field('image', 'user_' . get_the_author_meta('ID'));

					if (!empty($author_image)) :
					?>
						<div class="content-image__image-wrapper">
							<?= wp_get_attachment_image($author_image, 'Blog detail', false, ['class' => 'author__main-image content-image__image']); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</section>

	<?php
	$posts_per_page = 12;

	$args = [
		'post_type' => 'post',
		'post_status' => 'publish',
		'author' => get_the_author_meta('ID'),
		'posts_per_page' => $posts_per_page,
		'orderby' => 'date',
		'meta_query' => [
			[
				'key' => '_thumbnail_id',
				'compare' => 'EXISTS',
			],
		],
		'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
	];

	$query = new WP_Query($args);

	if ($query->have_posts()) :
	?>

		<section class="section blog pad--top-medium pad--bottom-medium">
			<div class="columns-12 center">

				<div class="titles">
					<h1 class="main-title default">
						<?= !empty(get_the_author_meta('user_firstname'))
							? sprintf(esc_html__('Blogs van %s', 'mbeffect'), esc_html(get_the_author_meta('user_firstname')))
							: esc_html__('Blogs', 'mbeffect'); ?>
					</h1>
				</div>

				<div class="blog__grid">

					<?php while ($query->have_posts()) : $query->the_post();

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

					wp_reset_postdata();
					?>

				</div>

				<?php wpex_pagination(); ?>
			</div>
		</section>
	<?php endif; ?>
</div>
