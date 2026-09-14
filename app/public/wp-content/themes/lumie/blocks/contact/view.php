<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Contact Block Template.
 */

$section = general_section($block ?? [], [
	'class' => ['section', 'contact'],
]);

if (empty($forms)) return;

$forms = array_values(array_filter($forms ?? [], function ($form_row) {
	return !empty($form_row['name']) && !empty($form_row['form']);
}));

if (empty($forms)) return;

$has_tabs = count($forms) > 1;

$form_names = array_column($forms, 'name');
$requested_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : '';
$active_tab = in_array($requested_tab, $form_names, true) ? $requested_tab : $form_names[0];

$tabs_id = wp_unique_id('contact-tabs-');

echo !is_admin() ? '[raw]' : '';
?>

<section <?php attr($section); ?>>
	<div class="columns-12 center">
		<div class="contact__grid contact__grid--<?= $selection ?? 'none'; ?> contact__grid--<?= !empty($order) ? 'unflip' : 'flip'; ?>">

			<div class="contact__form">
				<?php if ($has_tabs) : ?>
					<div class="contact__tab-buttons" role="tablist" aria-label="<?= esc_attr__('Formulieren', 'mbeffect'); ?>">
						<?php foreach ($forms as $index => $form_row) : ?>
							<button class="contact__tab-button<?= $form_row['name'] === $active_tab ? ' contact__tab-button--active' : ''; ?>"
								type="button"
								role="tab"
								id="<?= esc_attr($tabs_id . '-tab-' . $index); ?>"
								aria-controls="<?= esc_attr($tabs_id . '-panel-' . $index); ?>"
								aria-selected="<?= $form_row['name'] === $active_tab ? 'true' : 'false'; ?>"
								data-tab="<?= esc_attr($form_row['name']); ?>">
								<?= esc_html($form_row['name']); ?>
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php foreach ($forms as $index => $form_row) : ?>
					<?php if ($has_tabs) : ?>
						<div class="contact__form-tab<?= $form_row['name'] === $active_tab ? ' contact__form-tab--active' : ''; ?>"
							role="tabpanel"
							id="<?= esc_attr($tabs_id . '-panel-' . $index); ?>"
							aria-labelledby="<?= esc_attr($tabs_id . '-tab-' . $index); ?>"
							data-tab="<?= esc_attr($form_row['name']); ?>">
						<?php endif; ?>

						<h3 class="contact__form-title">
							<?= esc_html($form_row['name']); ?>
						</h3>

						<?php if (function_exists('gravity_form')) {
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

							gravity_form($form_row['form'], false, false, false, $field_values, true);
						} ?>

						<?php if ($has_tabs) : ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<?php switch ($selection ?? 'none'):
				case 'contact_details':
					$contact_details_cf = get_field('contact_details', 'options') ?? []; ?>
					<div class="contact__content">
						<?php if (!empty($block_title['main_title'])) {
							layout("title", [
								'title' => $block_title,
								'block' => $block,
							]);
						} ?>

						<?php if (!empty($content['content'])) {
							layout("content", [
								'content' => $content['content'],
								'block' => $block,
							]);
						} ?>

						<div class="contact__cd">
							<h3 class="contact__cd-title"><?= esc_html__('Bezoekadres', 'mbeffect'); ?></h3>

							<div class="contact__cd-grid">
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
							</div>
						</div>
					</div>
				<?php break;
				case 'content': ?>
					<div class="contact__content">
						<?php if (!empty($block_title['main_title'])) {
							layout("title", [
								'title' => $block_title,
								'block' => $block,
							]);
						} ?>

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
