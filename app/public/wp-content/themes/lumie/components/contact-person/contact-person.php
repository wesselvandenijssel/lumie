<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$team_member_ID = $team_member_ID ?? 0;

if (empty($team_member_ID)) return;

$image = get_post_thumbnail_id($team_member_ID);
$name = get_the_title($team_member_ID);
$job = get_the_excerpt($team_member_ID);
$contact_details_cf = get_field('contact_details', 'options') ?? [];

if (empty($image) || empty($name)) return;
?>

<div class="contact-person">
	<?= wp_get_attachment_image($image, 'Team mini', false, ['class' => 'contact-person__image', 'loading' => 'lazy']); ?>

	<div class="contact-person__content">
		<h4 class="contact-person__name">
			<?= esc_html($name); ?>
		</h4>

		<?php if (!empty($job)) : ?>
			<div class="contact-person__job">
				<?= esc_html($job); ?>
			</div>
		<?php endif; ?>

		<?php if (!empty($contact_details_cf['phone']) && !empty($contact_details_cf['phone_link']['url'])) : ?>
			<a href="<?= esc_url($contact_details_cf['phone_link']['url']); ?>" title="<?= esc_attr($contact_details_cf['phone_link']['title'] ?? ''); ?>" class="contact-person__cd-item contact-person__cd-item--phone">
				<?= esc_html($contact_details_cf['phone']); ?>
			</a>
		<?php endif; ?>
	</div>
</div>
