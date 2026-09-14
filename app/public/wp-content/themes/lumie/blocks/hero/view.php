<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Hero Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'hero'],
]);

if (empty($size)) return;

$section['class'][] = 'hero--' . $size;

switch ($type) {
	case 'image':
		if (empty($image))
			return;
		break;

	case 'video':
		if (empty($video))
			return;
		break;

	default:
		return;
		break;
}

$thumbnail = match ($size) {
	900 => 'Hero 900',
	default => 'Hero 900',
};

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="hero__media-wrapper">
		<?php
		switch ($type):
			case 'image':
				echo wp_get_attachment_image($image, $thumbnail, false, ['class' => 'hero__media hero__media--image hero__media--desktop', 'fetchpriority' => 'high']);
				echo wp_get_attachment_image($image, 'Hero mobile', false, ['class' => 'hero__media hero__media--image hero__media--mobile', 'fetchpriority' => 'high']);
				break;

			case 'video': ?>
				<div class="hero__media hero__media--video"><?= $video; ?></div>
		<?php
				break;

		endswitch;
		?>
	</div>

	<div class="hero__content columns-12 center">
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
</section>


<?= !is_admin() ? '[/raw]' : ''; ?>
