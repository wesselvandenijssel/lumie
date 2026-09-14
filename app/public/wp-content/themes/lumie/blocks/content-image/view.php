<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Content met afbeelding Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'content-image'],
]);

if (empty($image_group['image'])) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="content-image__grid content-image__grid--<?= $order ? 'unflip' : 'flip'; ?>">

			<div class="content-image__content">
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

			<?php
			$wrapper_attrs = [];
			$wrapper_attrs['class'][] = 'content-image__image-wrapper';

			if (!empty($video)) {
				$wrapper_attrs['data-fancybox'] = $block['id'] . '-gallery';
				$wrapper_attrs = video_in_fancybox($video, $wrapper_attrs);
			}
			?>

			<div <?php attr($wrapper_attrs); ?>>
				<?php layout("image", [
					'image' => $image_group,
					'class' => 'content-image__image',
				]); ?>
			</div>

		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
