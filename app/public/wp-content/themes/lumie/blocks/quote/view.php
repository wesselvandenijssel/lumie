<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Citaat Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'quote'],
]);

if (empty($title)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">
		<div class="quote__wrapper">

			<blockquote class="quote__blockquote">
				<?= wp_kses_post($title); ?>
			</blockquote>

			<?php if (!empty($author_name) || !empty($author_role)) : ?>
				<div class="quote__author">
					<?php if (!empty($author_name)) : ?>
						<cite class="quote__author-name"><?= esc_html($author_name); ?></cite>
					<?php endif; ?>

					<?php if (!empty($author_role)) : ?>
						<span class="quote__author-role"><?= esc_html($author_role); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
