<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Content kolommen Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'content-columns'],
]);

if (empty($content_left) && empty($content_right)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="content-columns__grid">

			<div class="content-columns__column content-columns__column--left">
				<?php layout("content", [
					'content' => $content_left,
					'block' => $block,
				]); ?>
			</div>

			<div class="content-columns__column content-columns__column--right">
				<?php layout("content", [
					'content' => $content_right,
					'block' => $block,
				]); ?>
			</div>

		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
