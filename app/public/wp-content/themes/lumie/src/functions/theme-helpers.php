<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * This function will return the URI of the needed file passed through as parameter.
 * It will look in the assets folder.
 */
function assets(string $file): string {
	return get_template_directory_uri() . '/assets/' . $file;
}

/**
 * Include a component.
 *
 * @param string $component_name The name of the component
 */
function component(string $component_name, array $args = []): void {
	$template_path = get_template_directory() . "/components/" . $component_name . "/" . $component_name . ".php";

	if (!file_exists($template_path)) {
		return;
	}

	extract($args);

	include $template_path;
}

/**
 * Include a layout.
 *
 * @param string $layout_name The name of the component
 */
function layout(string $layout_name, array $args = []): void {
	$template_path = get_template_directory() . "/layout/layout-" . $layout_name . ".php";

	if (!file_exists($template_path)) {
		return;
	}

	extract($args);

	include $template_path;
}

function get_all_forms() {
	$forms = [];
	$forms[''] = esc_html__('Selecteer een formulier', 'mbeffect');

	if (!class_exists('GFAPI')) {
		return $forms;
	}

	foreach (GFAPI::get_forms() as $form) {
		$forms[$form['id']] = $form['title'];
	}

	return $forms;
}

// Populate Gravity Forms choices dynamically for ACF select fields
add_filter('acf/load_field', function ($field) {
	// Only apply to form_id fields
	if (strpos($field['name'], 'form_id') === false) {
		return $field;
	}

	$field['choices'] = get_all_forms();
	return $field;
});

/**
 * Return array with general ACF settings fields.
 */
function get_general_settings(bool $spacings = true, bool $background = true, $spacing_top = 'large', $spacing_bottom = 'large'): array {
	$general = [
		'accordiongeneral' => [
			'type' => 'accordion',
			'label' => esc_html__('Algemene instellingen', 'mbeffect'),
			'multi_expand' => true,
		],
		'spacing_top' => [
			'label' => esc_html__('Ruimte boven', 'mbeffect'),
			'type' => 'select',
			'ui' => true,
			'choices' => [
				'none' => esc_html__('Geen', 'mbeffect'),
				'small' => esc_html__('Klein', 'mbeffect'),
				'medium' => esc_html__('Standaard', 'mbeffect'),
				'large' => esc_html__('Groot', 'mbeffect'),
				'extra-large' => esc_html__('Extra groot', 'mbeffect'),
			],
			'default_value' => 'medium',
			'wrapper' => [
				'width' => '50',
			]
		],
		'spacing_bottom' => [
			'label' => esc_html__('Ruimte onder', 'mbeffect'),
			'type' => 'select',
			'ui' => true,
			'choices' => [
				'none' => esc_html__('Geen', 'mbeffect'),
				'small' => esc_html__('Klein', 'mbeffect'),
				'medium' => esc_html__('Standaard', 'mbeffect'),
				'large' => esc_html__('Groot', 'mbeffect'),
				'extra-large' => esc_html__('Extra groot', 'mbeffect'),
			],
			'default_value' => 'medium',
			'wrapper' => [
				'width' => '50',
			]
		],
	];

	// Empty if all settings are false
	if (!$spacings && !$background) {
		$general = [];
	}

	return $general;
}

/**
 * The get_flex_content function is a way to include the flexible content field in a block.
 *
 * @param string $template_type the key of the template field
 */
function get_flex_content(string $template_type): array {
	return [
		'key' => $template_type . '_content',
		'label' => esc_html__('Content', 'mbeffect'),
		'name' => 'content',
		'type' => 'flexible_content',
		'button_label' => esc_html__('Nieuwe contentregel', 'mbeffect'),
		'layouts' => [
			[
				'key' => $template_type . '_content_layout_title',
				'name' => 'title',
				'label' => esc_html__('Titel', 'mbeffect'),
				'display' => 'block',
				'sub_fields' => [
					[
						'key' => $template_type . '_content_layout_title_title',
						'label' => esc_html__('Titel', 'mbeffect'),
						'name' => 'title',
						'type' => 'clone',
						'clone' => [
							'clone_titles_block_title',
						],
					],
				],
			],
			[
				'key' => $template_type . '_content_layout_content',
				'name' => 'content',
				'label' => esc_html__('Contentvlak', 'mbeffect'),
				'sub_fields' => [
					[
						'key' => $template_type . '_content_layout_content_content',
						'label' => esc_html__('Contentvlak', 'mbeffect'),
						'name' => 'content',
						'type' => 'wysiwyg',
						'delay' => true,
					],
				],
			],
			[
				'key' => $template_type . '_content_layout_fold_content',
				'name' => 'fold_content',
				'label' => esc_html__('Uitklapbaar contentvlak', 'mbeffect'),
				'sub_fields' => [
					[
						'key' => $template_type . '_content_layout_fold_content_content',
						'label' => esc_html__('Uitklapbaar contentvlak', 'mbeffect'),
						'name' => 'content',
						'type' => 'wysiwyg',
						'delay' => true,
					],
				],
			],
			[
				'key' => $template_type . '_content_layout_image',
				'name' => 'image',
				'label' => esc_html__('Afbeelding', 'mbeffect'),
				'sub_fields' => [
					[
						'key' => $template_type . '_content_layout_image_image',
						'label' => esc_html__('Afbeelding', 'mbeffect'),
						'name' => 'image',
						'type' => 'image',
						'mime_types' => 'svg, png, jpg, jpeg, webp',
						'return_format' => 'id',
					],
				],
			],
			[
				'key' => $template_type . '_content_layout_logos',
				'name' => 'logos',
				'label' => esc_html__('Logo\'s', 'mbeffect'),
				'sub_fields' => [
					[
						'key' => $template_type . '_content_layout_logos_logos',
						'label' => esc_html__('Logo\'s', 'mbeffect'),
						'name' => 'logos',
						'type' => 'repeater',
						'button_label' => esc_html__('Nieuw logo', 'mbeffect'),
						'sub_fields' => [
							[

								'key' => $template_type . '_content_layout_logos_logos_logo',
								'label' => esc_html__('Logo', 'mbeffect'),
								'name' => 'logo',
								'type' => 'image',
								'mime_types' => 'svg, png, jpg, jpeg, webp',
								'return_format' => 'id',
								'wrapper' => [
									'width' => '50',
								],
							],
							[

								'key' => $template_type . '_content_layout_logos_logos_link',
								'label' => esc_html__('Link', 'mbeffect'),
								'name' => 'link',
								'type' => 'link',
								'wrapper' => [
									'width' => '50',
								],
							],
						],
					],
				],
			],
			[
				'key' => $template_type . '_content_layout_accordions',
				'name' => 'accordions',
				'label' => esc_html__('Accordions', 'mbeffect'),
				'sub_fields' => [
					[
						'key' => $template_type . '_content_layout_accordions_accordions',
						'label' => esc_html__('Accordions', 'mbeffect'),
						'name' => 'accordions',
						'type' => 'repeater',
						'button_label' => esc_html__('Nieuwe accordion', 'mbeffect'),
						'sub_fields' => [
							[

								'key' => $template_type . '_content_layout_accordions_accordions_question',
								'label' => esc_html__('Vraag', 'mbeffect'),
								'name' => 'question',
								'type' => 'text',
								'wrapper' => [
									'width' => '50',
								],
							],
							[

								'key' => $template_type . '_content_layout_accordions_accordions_answer',
								'label' => esc_html__('Antwoord', 'mbeffect'),
								'name' => 'answer',
								'type' => 'wysiwyg',
								'wrapper' => [
									'width' => '50',
								],
							],
							[

								'key' => $template_type . '_content_layout_accordions_accordions_open_by_default',
								'label' => esc_html__('Standaard geopend', 'mbeffect'),
								'name' => 'open_by_default',
								'type' => 'true_false',
								'ui' => true,
								'default_value' => false,
							],
						],
					],
				],
			],
			[
				'key' => $template_type . '_content_layout_form',
				'name' => 'form',
				'label' => esc_html__('Formulier', 'mbeffect'),
				'sub_fields' => [
					[
						'key' => $template_type . '_content_layout_form_form_id',
						'label' => esc_html__('Formulier', 'mbeffect'),
						'name' => 'form_id',
						'type' => 'select',
						'allow_null' => 1,
						'ui' => 1,
					],
				],
			],
			[
				'key' => $template_type . '_content_layout_buttons',
				'name' => 'buttons_clone',
				'label' => esc_html__('Button(s)', 'mbeffect'),
				'sub_fields' => [
					[
						'key' => $template_type . '_content_layout_buttons_buttons',
						'label' => esc_html__('Button(s)', 'mbeffect'),
						'name' => 'buttons',
						'type' => 'clone',
						'clone' => [
							'clone_buttons_buttons_group',
						],
					],
				],
			],
		],
	];
}

/**
 * Return attribute array for section with general settings for block included.
 */
function general_section(array $block, array $section): array {
	$blockName = str_replace('acf/', '', strtolower($block['name']));
	if (isset($block["data"]['group_' . $blockName . '_fields_spacing_top'])) {
		$spacing_top = $block["data"]['group_' . $blockName . '_fields_spacing_top'];
	};
	if (isset($block["data"]['group_' . $blockName . '_fields_spacing_bottom'])) {
		$spacing_bottom = $block["data"]['group_' . $blockName . '_fields_spacing_bottom'];
	};

	// Section general settings
	if (isset($spacing_top)) {
		$section['class'][] = sprintf('pad--top-%s', $spacing_top);
	}
	if (isset($spacing_bottom)) {
		$section['class'][] = sprintf('pad--bottom-%s', $spacing_bottom);
	}

	// Anchor link
	if (!empty($block['anchor'])) {
		$section['id'] = $block['anchor'];
	}

	// Text alignment
	if (!empty($block['align_text'])) {
		$section['class'][] = 'text-alignment--' . $block['align_text'];
	}

	// Custom CSS class
	if (!empty($block['className'])) {
		$section['class'][] = $block['className'];
	}

	return $section;
}

/**
 * Returns a logo, linked to home.
 *
 * @param array $args (optional) - Some arguments to edit the string output
 *
 * @var string $args[class] (optional) - Add a custom class for the logo shell
 * @var string $args[name] (optional) - Makes it easy to add a different logo type like: footer or mobile
 *
 * @return string logo markup
 */
function get_logo(array $args = []): string {
	$html = '<span %s>%s</span>';
	$logo_id = get_field($args['name'] ?? 'logo', 'option');

	if (!$logo_id) {
		return '';
	}

	// We have a logo. Logo is go.
	$logo_attr = [
		'class' => 'logo',
		'loading' => false,
		'alt' => '',
	];
	$logo_link_attr = [];
	$logo_link_attr['class'][] = 'logo-link';
	if (!empty($args['class'])) {
		// If args contains a class, add it to the logo_link_attr
		$logo_link_attr['class'][] = $args['class'];
	}

	$html = '<a %s>%s</a>';

	/**
	 * If the logo alt attribute is empty, get the site title and explicitly pass it to the attributes used by wp_get_attachment_image().
	 */
	$image_alt = get_post_meta($logo_id, '_wp_attachment_image_alt', true);
	$logo_attr['alt'] = empty($image_alt) ? get_bloginfo('name') : $image_alt;

	$logo_link_attr['href'][] = esc_url(home_url('/'));
	$logo_link_attr['rel'][] = 'home';
	$logo_link_attr['aria-label'][] = get_bloginfo('name');


	// Generate the img html
	$image = wp_get_attachment_image($logo_id, 'full', false, $logo_attr);

	// Generate an attributes string
	$logo_link_atts = array_map('attribute_map_callback', $logo_link_attr, array_keys($logo_link_attr));
	$logo_link_attr_str = implode(' ', $logo_link_atts);

	// Add all variables together and return the html string
	return sprintf($html, $logo_link_attr_str, $image);
}

/**
 * Get ID of the first ACF block on the page
 */
function get_first_block_id() {
	$post = get_post();

	if (has_blocks($post->post_content)) {
		$blocks = parse_blocks($post->post_content);

		// Extract the attributes of the first block
		$first_block_attrs = !empty($blocks[0]['innerBlocks']) ? $blocks[0]['innerBlocks'][0]['attrs'] : $blocks[0]['attrs'];

		if (array_key_exists('id', $first_block_attrs)) {
			return $first_block_attrs['id'];
		}
	}
}

/**
 * Calculate the title element based on the block data.
 *
 * @param array $block The Gutenberg block data or 'first' string.
 *
 * @return int The calculated title element.
 */
function calculate_title_element($block) {
	$first = false;
	if ($block && $block !== 'first') {
		$first = $block['id'] === get_first_block_id();
		return $first ? 1 : 2;
	} else if ($block === 'first') {
		return 1;
	} else {
		return 2;
	}
}
