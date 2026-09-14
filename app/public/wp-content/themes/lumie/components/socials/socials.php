<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$social_media = get_field('social_media', 'options') ?? [];

if (empty($social_media)) return;
?>
<div class="socials">
	<?php foreach ($social_media as $social_key => $social_value) : ?>
		<?php if (empty($social_value['url'])) continue; ?>

		<a href="<?= esc_url($social_value['url']); ?>"
			title="<?= esc_attr($social_value['title'] ?? ''); ?>"
			target="_blank"
			rel="noopener noreferrer nofollow"
			class="socials__item socials__item--<?= esc_attr($social_key); ?>"
			aria-label="<?= esc_attr($social_value['title'] ?? ucfirst($social_key)); ?>">
		</a>
	<?php endforeach; ?>
</div>
