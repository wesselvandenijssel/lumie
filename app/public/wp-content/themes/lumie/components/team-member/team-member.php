<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$image = $image ?? 0;
$name = $name ?? '';
$job = $job ?? '';

if (empty($name) || empty($image)) return;
?>

<article class="team-member">
	<div class="team-member__image-wrapper">
		<?= wp_get_attachment_image($image, 'Team', false, ['class' => 'team-member__image', 'loading' => 'lazy']); ?>
	</div>

	<div class="team-member__content">
		<h3 class="team-member__name">
			<?= esc_html($name); ?>
		</h3>

		<?php if (!empty($job)) : ?>
			<div class="team-member__job">
				<?= esc_html($job); ?>
			</div>
		<?php endif; ?>
	</div>
</article>
