<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Logo banner Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'logo-banner'],
]);

if (empty($logos)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">
		<?php
		component('logo-wrapper', [
			'logos' => $logos,
			'text' => esc_html($text),
		]); ?>
	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
