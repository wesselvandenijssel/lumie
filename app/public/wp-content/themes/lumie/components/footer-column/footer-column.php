<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$column_value = $column_value ?? [];

if (empty($column_value)) return;
?>

<?php
switch ($column_value['acf_fc_layout']):
	case 'title':

		if (empty($column_value['title'])) break;

		$column_value['title'] = sanitize_title_custom($column_value['title']);

		$block_title = new BlockTitle(
			$column_value['title'],
		);

		$block_title->setType('h4');

		if (!empty($column_value['title_type'])) {

			// Set the look (heading type) based on the provided title type
			$block_title->setLook(
				$column_value['title_type'],
			);
		}

		echo $block_title->getTitle();
		break;

	case 'image':
		if (!empty($column_value['image_group'])) {
			layout("image", [
				'image' => $column_value['image_group'],
				'class' => 'footer__image',
			]);
		}
		break;


	case 'contact_details': ?>
		<?php $contact_details_cf = get_field('contact_details', 'options') ?? []; ?>

		<?php if (is_array($column_value['contact_details'])) : ?>
			<div class="footer__cd">
				<?php foreach ($column_value['contact_details'] as $contact_detail) : ?>
					<?php
					switch ($contact_detail):
						case 'phone':
							if (empty($contact_details_cf['phone']) || empty($contact_details_cf['phone_link']))
								continue 2;
					?>
							<a href="<?= esc_url($contact_details_cf['phone_link']['url'] ?? ''); ?>"
								title="<?= esc_attr($contact_details_cf['phone_link']['title'] ?? ''); ?>" class="footer__cd-item footer__cd-item--phone">
								<?= esc_html($contact_details_cf['phone']); ?>
							</a><br>
						<?php
							break;

						case 'email':
							if (empty($contact_details_cf['email']) || empty($contact_details_cf['email_link']))
								continue 2;
						?>
							<a href="<?= esc_url($contact_details_cf['email_link']['url'] ?? ''); ?>"
								title="<?= esc_attr($contact_details_cf['email_link']['title'] ?? ''); ?>" class="footer__cd-item footer__cd-item--email">
								<?= esc_html($contact_details_cf['email']); ?>
							</a><br>
						<?php
							break;

						case 'address':
							if (empty($contact_details_cf['address_data']['link']))
								continue 2;
						?>
							<a href="<?= esc_url($contact_details_cf['address_data']['link']['url'] ?? ''); ?>"
								title="<?= esc_attr($contact_details_cf['address_data']['link']['title'] ?? ''); ?>"
								target="_blank"
								rel="noopener noreferrer"
								class="footer__cd-item footer__cd-item--address">
								<?= esc_html($contact_details_cf['address_data']['street'] ?? ''); ?><br>
								<?= esc_html($contact_details_cf['address_data']['zip'] ?? ''); ?>
								<?= esc_html($contact_details_cf['address_data']['city'] ?? ''); ?>
							</a><br>
					<?php
							break;
					endswitch;
					?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php
		break;


	case 'social_media':
		component('socials');
		break;


	case 'menu':
		if (!empty($column_value['menu']) && $column_value['menu'] != 'Geen') :

			$visible_amount = (int) ($column_value['visible_amount'] ?? 4);
			$foldable = !empty($column_value['foldable']);

			if ($foldable) {
				$menu_items = wp_get_nav_menu_items($column_value['menu']) ?: [];

				$top_level_amount = count(array_filter($menu_items, function ($menu_item) {
					return empty($menu_item->menu_item_parent);
				}));

				$foldable = $top_level_amount > $visible_amount;
			}

			wp_nav_menu(
				[
					'menu' => $column_value['menu'],
					'container_class' => 'footer__menu',
					'fallback_cb' => false,
					'walker' => ($foldable ? new Walker_Fold_Menu : ''),
					'visible_amount' => $visible_amount,
				]
			);

			if ($foldable) : ?>
				<div class="footer__menu-fold-button" role="button" tabindex="0" aria-expanded="false"><?= esc_html($column_value['foldable_text'] ?? ''); ?></div>
<?php endif;
		endif;
		break;
endswitch; ?>
