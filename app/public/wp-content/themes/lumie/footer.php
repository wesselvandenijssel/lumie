<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package mbeffect
 */
?>
<div class="popups">
	<?php footer_popups(); ?>
</div>
<div class="popup-background"></div>
</main>

<?php $footer = get_field('footer', 'footer') ?? [];

// Check if columns are empty
if (!empty($footer['footer_column'])) {
	$has_non_empty_subkey = false;
	foreach ($footer['footer_column'] as $column) {
		if (!empty($column)) {
			$has_non_empty_subkey = true;
		} else {
			unset($column);
		}
	}
}

$has_subfooter_menu = has_nav_menu('subfooter');

if (!empty($has_non_empty_subkey) || !empty($footer['sub_footer']) || $has_subfooter_menu) : ?>
	<footer class="footer" itemscope itemtype="http://schema.org/WPFooter">

		<?php if (!empty($has_non_empty_subkey)) : ?>
			<div class="footer__main-wrapper">
				<div class="footer__main">
					<?php foreach ($footer['footer_column'] as $column_key => $column) : ?>
						<?php if (empty($column)) continue; ?>

						<article class="footer__column <?= $column_key; ?>">
							<?php if (is_array($column)) {
								foreach ($column as $column_value) :
									component('footer-column', ['column_value' => $column_value ?? []]);
								endforeach;
							} else {
								component('footer-column', ['column_value' => $column_value ?? []]);
							}; ?>
						</article>

					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if (!empty($footer['sub_footer']) || $has_subfooter_menu) : ?>
			<div class="footer__bottom">
				<div class="footer__bottom-wrapper">

					<?php if (!empty($footer['sub_footer'])) : ?>
						<div class="footer__copyright">
							<?= wp_kses_post($footer['sub_footer']); ?>
						</div>
					<?php endif; ?>

					<?php if ($has_subfooter_menu) {
						wp_nav_menu([
							'theme_location' => 'subfooter',
							'container_class' => 'footer__subfooter-menu',
							'depth' => 1,
							'fallback_cb' => false,
						]);
					} ?>

				</div>
			</div>
		<?php endif; ?>

	</footer>
<?php endif; ?>

</div>

<?php wp_footer(); ?>

</body>

</html>
