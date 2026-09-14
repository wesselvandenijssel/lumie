<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * CTA banner Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'cta-banner'],
]);

if (empty($block_title['main_title']) || empty($image)) return;

$type = $type ?? 'none';

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="cta-banner__grid">
			<div class="cta-banner__content">
				<?php layout("title", [
					'title' => $block_title,
					'block' => $block,
				]);

				if (!empty($content)) {
					layout("content", [
						'content' => $content,
						'block' => $block,
					]);
				} ?>
			</div>

			<div class="cta-banner__image-wrapper cta-banner__image-wrapper--<?= esc_attr($type); ?>">
				<?= wp_get_attachment_image($image, 'CTA banner', false, ['class' => 'cta-banner__image', 'loading' => 'lazy']); ?>

				<?php switch ($type):
					case 'brochure':
						if (!empty($brochure_image)) {
							echo wp_get_attachment_image($brochure_image, 'full', false, ['class' => 'cta-banner__brochure-image', 'loading' => 'lazy']);
						}
						break;

					case 'person':
						if (!empty($person)) :
							component('contact-person', [
								'team_member_ID' => $person,
							]);
						endif;
						break;
				endswitch; ?>
			</div>
		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
