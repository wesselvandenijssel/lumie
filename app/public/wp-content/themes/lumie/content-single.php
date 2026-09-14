<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Single Post Content Template
 */

// Extract author data to avoid repeated function calls
$author_id = get_the_author_meta('ID');
$author_name = get_the_author();
$post_url = get_permalink();
?>

<div class="entry-content">
	<?php component('breadcrumb'); ?>

	<section class="section centered-content pad--top-large">
		<div class="columns-12 center">

			<div class="centered-content__wrapper">

				<div class="titles">
					<h1 class="main-title default"><?= esc_html(get_the_title()); ?></h1>
				</div>

				<div class="content-layout">
					<div class="single__meta">
						<div class="single__date"><?= wp_kses_post(sprintf(__('Datum: <span>%s</span>', 'mbeffect'), get_the_date('d M Y'))); ?></div>
						<span class="single__separator"></span>
						<a class="single__author" href="<?= esc_url(get_author_posts_url($author_id)); ?>"><?= wp_kses_post(sprintf(__('Door: <span>%s</span>', 'mbeffect'), esc_html($author_name))); ?></a>
						<span class="single__separator"></span>
						<div class="single__reading-time"><?= wp_kses_post(sprintf(__('Leestijd: <span>%s</span>', 'mbeffect'), reading_time(get_the_ID()))); ?></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div id="read-more" class="single__content">

		<?php the_content(); ?>

	</div>

	<section class="section centered-content pad--bottom-medium single__share">
		<div class="columns-12 center">
			<div class="centered-content__wrapper">
				<div class="content-layout">
					<div class="single__footer">

						<div class="single__return">
							<button class="btn btn--read-more" onclick="window.history.go(-1); return false;">
								<?= esc_html__('Terug naar de vorige pagina', 'mbeffect'); ?>
							</button>
						</div>

						<div class="single__share">
							<p><?= esc_html__('Deel op', 'mbeffect'); ?></p>

							<a class="single__share-link single__share-link--linkedin" href="<?= esc_url('https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($post_url)); ?>" target="_blank"
								rel="noopener noreferrer"
								onclick="window.open(this.href, 'linkedin-share', 'width=600,height=400');return false;"
								aria-label="<?= esc_attr(__('Deel op LinkedIn', 'mbeffect')); ?>"></a>

							<a class="single__share-link single__share-link--facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($post_url); ?>"
								target="_blank"
								rel="noopener noreferrer"
								onclick="window.open(this.href, 'facebook-share', 'width=600,height=400');return false;"
								aria-label="<?= esc_attr(__('Deel op Facebook', 'mbeffect')); ?>"></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if (!empty($author_name)) : ?>
		<section class="section single__author-info pad--top-small pad--bottom-medium">
			<div class="columns-12 center">
				<div class="single__author-info-wrapper">
					<h4 class="single__author-info-title"><?= esc_html__('Over de auteur', 'mbeffect'); ?></h4>
					<a class="single__author-info-wrapper-inner" href="<?= esc_url(get_author_posts_url($author_id)); ?>">
						<?php
						$author_image = get_field('image', 'user_' . $author_id);

						if (!empty($author_image)) {
							echo wp_get_attachment_image($author_image, 'Author thumb', false, ['class' => 'single__author-info-image', 'loading' => 'lazy']);
						} ?>

						<div class="single__author-info-content">
							<p class="single__author-info-name"><strong><?= esc_html($author_name); ?></strong></p>
							<p class="single__author-info-bio"><?= esc_html(get_field('excerpt', 'user_' . $author_id)); ?></p>
						</div>
					</a>

					<hr>

					<div class="single__author-connect">
						<a class="single__author-link" href="<?= esc_url(get_author_posts_url($author_id)); ?>"><?= esc_html__('Meer over', 'mbeffect') . ' ' . esc_html($author_name); ?></a>

						<div class="single__author-socials">
							<?php
							$socials = [
								'twitter' => esc_url(get_field('twitter', 'user_' . $author_id)),
								'linkedin' => esc_url(get_field('linkedin', 'user_' . $author_id)),
								'facebook' => esc_url(get_field('facebook', 'user_' . $author_id)),
								'instagram' => esc_url(get_field('instagram', 'user_' . $author_id)),
								'mail' => esc_attr(!empty(get_the_author_meta('user_email')) ? 'mailto:' . get_the_author_meta('user_email') : ''),
								'phone' => esc_attr(!empty(get_the_author_meta('user_phone')) ? 'tel:' . get_the_author_meta('user_phone') : ''),
							];

							foreach ($socials as $key => $url) :
								if (empty($url)) continue; ?>

								<a href="<?= esc_url($url); ?>"
									target="_blank"
									rel="noopener noreferrer"
									class="single__author-social-link single__author-social-link--<?= esc_attr($key); ?>">
									<span class="screen-reader-text"><?= esc_html(ucfirst($key)); ?></span>
								</a>

							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$args = [
		'post_type' => 'post',
		'posts_per_page' => 3,
		'post__not_in' => [get_the_ID()],
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_query' => [
			[
				'key' => '_thumbnail_id',
				'compare' => 'EXISTS',
			],
		],
	];
	$related_posts = new WP_Query($args);

	if ($related_posts->have_posts() && $related_posts->found_posts >= 3) : ?>
		<section class="section blog pad--bottom-medium">
			<div class="columns-12 center">
				<div class="titles">
					<h2 class="main-title default"><?= esc_html__('Gerelateerde artikelen', 'mbeffect'); ?></h2>
				</div>
				<div class="blog__grid blog__grid--related">
					<?php while ($related_posts->have_posts()) : $related_posts->the_post();
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
					endwhile; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</div>
