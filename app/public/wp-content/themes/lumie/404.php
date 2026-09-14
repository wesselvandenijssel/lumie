<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package lumie
 */

get_header(); ?>

<div id="primary" class="content-area">
	<section class="centered-content error-404 pad--top-medium pad--bottom-medium">
		<div class="columns-12 center">
			<div class="centered-content__wrapper">
				<div class="content-layout">
					<?php $not_found = get_field('not_found', 'options');

					if (!empty($not_found['content'])) :
						layout("content", [
							'content' => $not_found['content'],
							'block' => [],
						]);

					else :

						$title = new BlockTitle(esc_html__('De opgevraagde pagina kan niet gevonden worden', 'lumie'));
						$title->setSubtitle(esc_html__('404 error', 'lumie'));
						$title->setType(
							'h2'
						);
						echo $title->getTitle();
					?>

						<p>
							<?= wp_kses_post(sprintf(
								__('De pagina die u zoekt is verwijderd of verplaatst. Wellicht dat u de juiste informatie kunt
							vinden via onze <a href="%s" title="%s">%s</a> of <a href="%s" title="%s">%s</a>', 'lumie'),
								esc_url(home_url('/')),
								esc_attr__('Homepagina', 'lumie'),
								esc_html__('homepagina', 'lumie'),
								esc_url(get_permalink(12)),
								esc_attr__('Contactpagina', 'lumie'),
								esc_html__('contactpagina', 'lumie'),
							)); ?>
						</p>
						<div class="buttons">
							<button class="btn btn--primary" onclick="window.history.go(-1); return false;">
								<?= esc_html__('Terug naar de vorige pagina', 'lumie'); ?>
							</button>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>
