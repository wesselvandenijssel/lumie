<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Premium content Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'premium-content'],
]);

if (empty($block_title['main_title']) || empty($image)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="premium-content__intro">
		<?php layout("title", [
			'title' => $block_title,
			'block' => $block,
		]); ?>

		<div class="premium-content__image-wrapper">
			<?= wp_get_attachment_image($image, 'Premium content', false, ['class' => 'premium-content__image', 'loading' => 'lazy']); ?>
		</div>
	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
