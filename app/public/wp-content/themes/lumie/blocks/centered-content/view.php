<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Gecentreerde content Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'centered-content'],
]);

if (empty($content)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="centered-content__wrapper">
			<?php if (!empty($block_title['main_title'])) {
				layout("title", [
					'title' => $block_title,
					'block' => $block,
				]);
			}

			layout("content", [
				'content' => $content,
				'block' => $block,
			]); ?>
		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
