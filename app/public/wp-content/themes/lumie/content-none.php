<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * None Post Content Template
 */
?>

<div class="post">
	<h2 class="post__title">
		<?= esc_html__('Niets gevonden', 'mbeffect'); ?>
	</h2>

	<p class="post__excerpt">
		<?= esc_html__('Helaas, maar er zijn geen resultaten gevonden voor uw zoekopdracht. Probeer het opnieuw met andere zoekwoorden.', 'mbeffect'); ?>
	</p>

	<div class="buttons">
		<button class="btn btn--primary" onclick="window.history.go(-1); return false;">
			<?= esc_html__('Terug naar de vorige pagina', 'mbeffect'); ?>
		</button>
	</div>
</div>
