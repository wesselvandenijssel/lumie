<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Achtergrond Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'background'],
]);

if (!empty($block['backgroundColor'])) {
	$section['class'][] = 'has-background has-' . $block['backgroundColor'] . '-background-color ';
}

if (!empty($block['style']['color'])) {
	$styles = $block['style']['color'];

	if (!empty($styles['background'])) {
		$section['style'][] = 'background-color:' . $styles['background'] . ';';
	}

	if (!empty($styles['gradient'])) {
		$section['style'][] = 'background:' . $styles['gradient'] . ';';
	}

	if (!empty($styles['text'])) {
		$section['style'][] = 'color:' . $styles['text'] . ';';
	}
}

if (!empty($block['textColor'])) {
	$section['class'][] = 'has-color has-' . $block['textColor'] . '-color ';
}

if (!empty($block['gradient'])) {
	$section['class'][] = 'has-gradient has-' . $block['gradient'] . '-gradient ';
}
?>

<section <?php attr($section); ?>>
	<?php if (is_admin()) : ?>
		<h3> <?= esc_html__("Achtergrond blok", 'mbeffect'); ?> </h3>
	<?php endif; ?>

	<InnerBlocks />

	<?php if (is_admin()) : ?>
		<h3> <?= esc_html__("Einde achtergrond block", 'mbeffect'); ?> </h3>
	<?php endif; ?>
</section>
