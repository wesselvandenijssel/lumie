<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Contact Block Template.
 */

$section = general_section($block, [
	'class' => ['section', 'contact'],
]);

if (empty($form)) return;

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">
		<div class="contact__grid contact__grid--<?= $selection; ?> contact__grid--<?= $order ? 'unflip' : 'flip'; ?>">

			<div class="contact__form">
				<?php if (!empty($block_title['main_title'])) {
					layout("title", [
						'title' => $block_title,
						'block' => $block,
					]);
				}

				if (function_exists('gravity_form')) {
					$field_values = [];

					// Check for entry ID to pre-populate form fields
					$entry_id = get_validated_entry_id();

					if ($entry_id) {
						$entry = GFAPI::get_entry($entry_id);

						if (!is_wp_error($entry)) {
							$gf_form = GFAPI::get_form($entry['form_id']);

							foreach ($gf_form['fields'] as $field) {
								if (!empty($field->inputs)) {
									foreach ($field->inputs as $input) {
										$field_values[$input['id']] = rgar($entry, $input['id']);
									}
								} else {
									$field_values[$field->id] = rgar($entry, $field->id);
								}
							}
						}
					}

					gravity_form($form, false, false, false, $field_values, true);
				}
				?>
			</div>

			<?php switch ($selection):
				case 'contact_details':
					$contact_details_cf = get_field('contact_details', 'options') ?? []; ?>

					<div class="contact__cd">
						<h3 class="contact__cd-title"><?= esc_html__('Contactgegevens', 'mbeffect'); ?></h3>

						<?php if (!empty($contact_details_cf['phone']) && !empty($contact_details_cf['phone_link']['url'])) : ?>
							<a href="<?= esc_url($contact_details_cf['phone_link']['url']); ?>"
								title="<?= esc_attr($contact_details_cf['phone_link']['title'] ?? ''); ?>" class="contact__cd-item contact__cd-item--phone">
								<?= esc_html($contact_details_cf['phone']); ?>
							</a>
						<?php endif; ?>

						<?php if (!empty($contact_details_cf['email']) && !empty($contact_details_cf['email_link']['url'])) : ?>
							<a href="<?= esc_url($contact_details_cf['email_link']['url']); ?>"
								title="<?= esc_attr($contact_details_cf['email_link']['title'] ?? ''); ?>" class="contact__cd-item contact__cd-item--email">
								<?= esc_html($contact_details_cf['email']); ?>
							</a>
						<?php endif; ?>

						<?php if (!empty($contact_details_cf['address_data']['link']['url'])) : ?>
							<a href="<?= esc_url($contact_details_cf['address_data']['link']['url']); ?>"
								title="<?= esc_attr($contact_details_cf['address_data']['link']['title'] ?? ''); ?>"
								target="_blank"
								rel="noopener noreferrer"
								class="contact__cd-item contact__cd-item--address">
								<?= esc_html($contact_details_cf['address_data']['street'] ?? ''); ?><br>
								<?= esc_html($contact_details_cf['address_data']['zip'] ?? ''); ?>
								<?= esc_html($contact_details_cf['address_data']['city'] ?? ''); ?>
							</a>
						<?php endif; ?>

						<?php component('socials'); ?>

					</div>
				<?php break;
				case 'content': ?>

					<div class="contact__content">
						<?php if (!empty($content['content'])) {
							layout("content", [
								'content' => $content['content'],
								'block' => $block,
							]);
						} ?>
					</div>

			<?php break;
			endswitch; ?>
		</div>
	</div>
</section>
<?= !is_admin() ? '[/raw]' : ''; ?>
