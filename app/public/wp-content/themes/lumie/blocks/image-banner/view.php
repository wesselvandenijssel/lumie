<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Afbeelding banner Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'image-banner'],
]);

if (($type ?? 'image') === 'video' ? empty($video) : empty($image)) return;

$has_card = !empty($block_title['main_title']) || !empty($content);

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="image-banner__inner">

		<div class="image-banner__media-wrapper">
			<?php if ($type === 'video') : ?>
				<div class="image-banner__media image-banner__media--video"><?= $video; ?></div>
			<?php else :
				echo wp_get_attachment_image($image, 'Image banner', false, ['class' => 'image-banner__media image-banner__media--image', 'loading' => 'lazy']);
			endif; ?>

			<?php if (!empty($text)) : ?>
				<div class="image-banner__text"><?= sanitize_title_custom($text); ?></div>
			<?php endif; ?>
		</div>

		<?php if ($has_card) : ?>
			<div class="image-banner__card">
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
		<?php endif; ?>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
