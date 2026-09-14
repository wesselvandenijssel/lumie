<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Method Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'method'],
]);

if (empty($steps)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="method__grid">
			<div class="method__content">
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

			<div class="method__steps">
				<?php foreach ($steps as $step) {
					component('step', [
						'image' => $step['image'] ?? 0,
						'suptitle' => $step['suptitle'] ?? '',
						'title' => $step['title'] ?? '',
						'description' => $step['description'] ?? '',
					]);
				} ?>

				<?php if (!empty($buttons_group)) {
					$buttons = new BlockButtons($buttons_group);
					echo $buttons->get_buttons();
				} ?>
			</div>

			<?php if (!empty($content)) : ?>
				<div class="method__mobile-content">
					<?php layout("content", [
						'content' => $content,
						'block' => $block,
					]); ?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
