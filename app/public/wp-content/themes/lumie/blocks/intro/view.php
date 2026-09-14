<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Intro Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'intro'],
]);

if (empty($content) && empty($specifications) && empty($buttons_group)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">

		<div class="intro__grid">

			<?php if (!empty($content)) : ?>
				<div class="intro__content"><?= wp_kses_post($content); ?></div>
			<?php endif; ?>

			<?php if (!empty($specifications) || !empty($buttons_group)) : ?>
				<div class="intro__aside">

					<?php if (!empty($specifications)) : ?>
						<dl class="intro__specs">
							<?php foreach ($specifications as $specification) : ?>
								<div class="intro__spec">
									<dt class="intro__spec-label"><?= esc_html($specification['label'] ?? ''); ?></dt>
									<dd class="intro__spec-value"><?= esc_html($specification['value'] ?? ''); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
					<?php endif; ?>

					<?php if (!empty($buttons_group)) {
						$buttons = new BlockButtons($buttons_group);
						echo $buttons->get_buttons();
					} ?>

				</div>
			<?php endif; ?>

		</div>

	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
