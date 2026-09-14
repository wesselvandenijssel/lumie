<?php

/**
 * Plugin Name: Lumie widgets
 * Plugin URI: https://www.lumiedrinkz.nl/
 * Description: The widget plugin developed by Lumie.
 * Version: 1.3.2
 * Author: Lumie
 * Author URI: https://www.lumiedrinkz.nl/
 * Requires Plugins: advanced-custom-fields-pro
 **/

defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

// Check if ACF PRO is active
if (!is_plugin_active('advanced-custom-fields-pro/acf.php')) return;

define('MBWIDGETS__PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once MBWIDGETS__PLUGIN_DIR . 'cache-functions.php';
require_once MBWIDGETS__PLUGIN_DIR . 'post-types.php';
require_once MBWIDGETS__PLUGIN_DIR . 'libraries/plugin-update-checker/plugin-update-checker.php';

/**
 * Check for updates
 */
$mbw_update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
	'https://plugins.mbeffect.nl/mb-widgets/metadata.php',
	__FILE__,
	'mb-widgets'
);

$mbw_update_checker->addQueryArgFilter(function (array $args) {
	$license = mbw_get_license_key();

	if (empty($license)) return $args;

	$site = home_url();
	$ts = time();

	$nonce = function_exists('wp_generate_uuid4') ? wp_generate_uuid4() : bin2hex(random_bytes(16));
	$sig = hash_hmac('sha256', $site . '|' . $ts . '|' . $nonce, $license);

	$args['license'] = $license;
	$args['site'] = $site;
	$args['ts'] = $ts;
	$args['nonce'] = $nonce;
	$args['sig'] = $sig;

	return $args;
});

function mbw_get_license_key() {
	$key = get_field('license', 'mbwidget');

	return is_string($key) ? $key : '';
}

/**
 * Plugin activation hook - Clear cache
 */
function mbw_plugin_activation() {
	mbw_clear_all_caches();
}

/**
 * Plugin deactivation hook - Clear cache
 */
function mbw_plugin_deactivation() {
	mbw_clear_all_caches();
}

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'mbw_plugin_activation');
register_deactivation_hook(__FILE__, 'mbw_plugin_deactivation');

if (!is_admin()) {
	$plugin_url = plugin_dir_url(__FILE__);

	wp_register_style('mbw-stylesheet', $plugin_url . "public/css/mb-widgets.css", [], filemtime(MBWIDGETS__PLUGIN_DIR . "public/css/mb-widgets.css"), 'all');
	wp_register_script('mbw-js', $plugin_url . "public/js/mb-widgets.js", [], filemtime(MBWIDGETS__PLUGIN_DIR . "public/js/mb-widgets.js"), true);

	wp_enqueue_style('mbw-stylesheet');
	wp_enqueue_script('mbw-js');
}

function mbw_render_button(array $btn, string $styling, array &$content_frames, array &$buttons_frames, int &$frame_id_counter): void {
	switch ($btn['action']) {
		case 'link':
			if (empty($btn['link']['url'])) return;
?>
			<a <?= $btn['id'] ? 'id="' . esc_attr($btn['id']) . '"' : ''; ?> href="<?= esc_url($btn['link']['url']); ?>" title="<?= esc_attr($btn['link']['title'] ?? ''); ?>" target="<?= esc_attr($btn['link']['target'] ?: '_self'); ?>" class="mbw-button mbw-button--<?= esc_attr($btn['type']); ?> mbw-button--<?= esc_attr($btn['action']); ?>" style="<?= esc_attr($styling); ?>">
				<?= wp_kses_post($btn['text']); ?>
			</a>
		<?php
			break;

		case 'content':
			if (empty($btn['content'])) return;
			$content_frames[] = $btn['content'];
		?>
			<button <?= $btn['id'] ? 'id="' . esc_attr($btn['id']) . '"' : ''; ?> class="mbw-button mbw-button--<?= esc_attr($btn['type']); ?> mbw-button--<?= esc_attr($btn['action']); ?>" style="<?= esc_attr($styling); ?>" data-frame="<?= wp_create_nonce($btn['content']); ?>">
				<?= wp_kses_post($btn['text']); ?>
			</button>
		<?php
			break;

		case 'click-event':
		?>
			<button <?= $btn['id'] ? 'id="' . esc_attr($btn['id']) . '"' : ''; ?> class="mbw-button mbw-button--<?= esc_attr($btn['type']); ?> mbw-button--<?= esc_attr($btn['action']); ?>" style="<?= esc_attr($styling); ?>">
				<?= wp_kses_post($btn['text']); ?>
			</button>
		<?php
			break;

		case 'buttons':
			$nested_content = $btn['recursive']['content'] ?? '';
			$nested_buttons = $btn['recursive']['buttons'] ?? [];
			if (empty($nested_content) && empty($nested_buttons)) return;
			$frame_id = 'mbw-buttons-' . (++$frame_id_counter);
			$buttons_frames[] = ['id' => $frame_id, 'content' => $nested_content, 'buttons' => $nested_buttons];
		?>
			<button <?= $btn['id'] ? 'id="' . esc_attr($btn['id']) . '"' : ''; ?> class="mbw-button mbw-button--<?= esc_attr($btn['type']); ?> mbw-button--<?= esc_attr($btn['action']); ?>" style="<?= esc_attr($styling); ?>" data-frame="<?= esc_attr($frame_id); ?>">
				<?= wp_kses_post($btn['text']); ?>
			</button>
		<?php
			break;
	}
}

function mbw_show_widget() {
	$current_id = get_the_ID();
	$current_type = get_post_type();

	$widgets = get_posts([
		'post_type' => 'mbwidget',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_key' => 'welcome_text',
	]);

	$matched_widget = null;

	foreach ($widgets as $widget) {
		$widget_id = $widget->ID;
		$display = get_field('display', $widget_id);
		$display_post_types = $display['post_types'] ?? [];

		foreach ($display_post_types as $post_type) {
			// Skip if the current post type is NOT selected
			if ($current_type != $post_type) continue;

			switch ($display[$post_type]['display']) {
				case 'show_on':
					// Skip if 'show' is filled & the current ID is NOT in it
					if (!empty($display[$post_type]['show']) && !in_array($current_id, $display[$post_type]['show'])) continue 2;
					break;

				case 'hide_on':
					// Skip if 'hide' is filled & the current ID is in it
					if (!empty($display[$post_type]['hide']) && in_array($current_id, $display[$post_type]['hide'])) continue 2;
					break;
			}

			$matched_widget = $widget;
			break;
		}
	}

	if (!empty($matched_widget)) :
		// Button variables
		$button_primary = get_field('button_primary', 'mbwidget');
		$button_secondary = get_field('button_secondary', 'mbwidget');
		$button_primary_styling = sprintf('--color: %s; --bg-color: %s; --border-color: %s; --color-hover: %s; --bg-color-hover: %s; --border-color-hover: %s;', $button_primary['color'] ?? '#fff', $button_primary['background_color'] ?? '#000', $button_primary['border_color'] ?? '#000', $button_primary['color_hover'] ?? '#fff', $button_primary['background_color_hover'] ?? '#000', $button_primary['border_color_hover'] ?? '#000');
		$button_secondary_styling = sprintf('--color: %s; --bg-color: %s; --border-color: %s; --color-hover: %s; --bg-color-hover: %s; --border-color-hover: %s;', $button_secondary['color'] ?? '#fff', $button_secondary['background_color'] ?? '#000', $button_secondary['border_color'] ?? '#000', $button_secondary['color_hover'] ?? '#fff', $button_secondary['background_color_hover'] ?? '#000', $button_secondary['border_color_hover'] ?? '#000');

		// Content variables
		$welcome_text = get_field('welcome_text', $matched_widget->ID);
		$image = get_field('image', $matched_widget->ID) ?? 0;
		$buttons = get_field('buttons', $matched_widget->ID) ?? [];
		$content_frames = [];
		$buttons_frames = [];
		$frame_id_counter = 0;

		// Open automatically variables
		$open = get_field('open', $matched_widget->ID);
		$open_type = $open['type'] ?? 'none';
		$open_amount = match ($open_type) {
			'page_visits' => floatval($open['page_visits'] ?? 0),
			'scroll_percentage' => floatval($open['scroll_percentage'] ?? 0),
			'seconds' => floatval($open['seconds'] ?? 0),
			default => 0,
		};
		$open_automatically = !empty($open_type) && $open_type != 'none' && !empty($open_amount);
		?>
		<article class="mbw<?= $open_automatically ? ' mbw--' . esc_attr($open_type) : ''; ?>" <?= $open_automatically ? ' data-amount="' . esc_attr($open_amount) . '"' : ''; ?>>
			<div class="mbw__container">
				<div class="mbw__close"></div>

				<?php if (!empty($image)) : ?>
					<?= wp_get_attachment_image($image, 'full', false, ['class' => 'mbw__image', 'loading' => 'lazy']); ?>
				<?php endif; ?>

				<div class="mbw__overflow-container">
					<div class="mbw__welcome content-layout">
						<?= $welcome_text; ?>
					</div>

					<?php if (!empty($buttons)) : ?>
						<div class="mbw__buttons">
							<?php foreach ($buttons as $button) : ?>
								<?php if (empty($button['text']) || empty($button['type'])) continue; ?>

								<?php $button_styling = match ($button['type']) {
									'primary' => $button_primary_styling,
									'secondary' => $button_secondary_styling,
									default => '',
								}; ?>

								<?php mbw_render_button($button, $button_styling, $content_frames, $buttons_frames, $frame_id_counter); ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php for ($button_frame_counter = 0; $button_frame_counter < count($buttons_frames); $button_frame_counter++) : ?>
						<?php $button_frame = $buttons_frames[$button_frame_counter]; ?>
						<div class="mbw__buttons-frame" data-frame="<?= esc_attr($button_frame['id']); ?>">
							<?php if (!empty($button_frame['content'])) : ?>
								<div class="mbw__buttons-frame-content content-layout">
									<?= $button_frame['content']; ?>
								</div>
							<?php endif; ?>
							<?php foreach ($button_frame['buttons'] as $btn) : ?>
								<?php if (empty($btn['text']) || empty($btn['type'])) continue; ?>

								<div class="mbw__buttons">
									<?php $btn_styling = match ($btn['type']) {
										'primary' => $button_primary_styling,
										'secondary' => $button_secondary_styling,
										default => '',
									}; ?>

									<?php mbw_render_button($btn, $btn_styling, $content_frames, $buttons_frames, $frame_id_counter); ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endfor; ?>

					<?php if (!empty($content_frames)) : ?>
						<?php foreach ($content_frames as $frame) : ?>
							<div class="mbw__content-frame content-layout" data-frame="<?= wp_create_nonce($frame); ?>">
								<?= $frame; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>

			<button id="mbw-toggle" class="mbw__toggle" style="<?= esc_attr($button_primary_styling); ?>" aria-label="<? __('Bekijk de contactmogelijkheden', 'mb-widget'); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
					<path fill="currentColor" d="M256 64C125.8 64 32 148.6 32 240c0 37.1 15.5 70.6 40 100c5.2 6.3 8.4 14.8 7.4 23.9c-3.1 27-11.4 52.5-25.7 76.3c-.5 .9-1.1 1.8-1.6 2.6c11.1-2.9 22.2-7 32.7-11.5L91.2 446l-6.4-14.7c17-7.4 33-16.7 48.4-27.4c8.5-5.9 19.4-7.5 29.2-4.2C193 410.1 224.1 416 256 416c130.2 0 224-84.6 224-176s-93.8-176-224-176zM0 240C0 125.2 114.5 32 256 32s256 93.2 256 208s-114.5 208-256 208c-36 0-70.5-6.7-103.8-17.9c-.2-.1-.5 0-.7 .1c-16.9 11.7-34.7 22.1-53.9 30.5C73.6 471.1 44.7 480 16 480c-6.5 0-12.3-3.9-14.8-9.8s-1.1-12.8 3.4-17.4c8.1-8.2 15.2-18.2 21.7-29c11.7-19.6 18.7-40.6 21.3-63.1c0 0-.1-.1-.1-.2C19.6 327.1 0 286.6 0 240z" />
				</svg>
			</button>
		</article>
<?php
	endif;
}
add_action('wp_footer', 'mbw_show_widget');

function mbw_get_post_types(): array {
	$post_type_objects = get_post_types(['public' => true], 'objects');
	unset($post_type_objects['attachment']);

	$post_types = [];

	foreach ($post_type_objects as $post_type => $object) {
		$post_types[$post_type] = $object->label;
	}

	return $post_types;
}
