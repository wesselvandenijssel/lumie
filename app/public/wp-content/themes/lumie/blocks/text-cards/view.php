<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Text cards Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'text-cards'],
]);

if (empty($cards)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="text-cards__content">
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

		<div class="text-cards__grid">
			<?php foreach ($cards as $card) {
				component('text-card', [
					'image' => $card['image'] ?? 0,
					'suptitle' => $card['suptitle'] ?? '',
					'title' => $card['title'] ?? '',
					'content' => $card['content'] ?? '',
					'link' => [
						'url' => $card['link']['url'] ?? '',
						'title' => $card['link']['title'] ?? '',
						'target' => !empty($card['link']['target']) ? $card['link']['target'] : '_self',
					],
				]);
			} ?>
		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
