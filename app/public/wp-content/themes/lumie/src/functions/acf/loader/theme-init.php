<?php

defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.


if (class_exists('ACF') && function_exists('acf_register_block')) {
	// Add custom block categories
	function mb_block_category($categories) {
		return array_merge(
			$categories,
			[
				[
					'slug' => 'block-elements',
					'title' => esc_html__('MB blokken', 'lumie'),
				],
			]
		);
	}
	add_filter('block_categories_all', 'mb_block_category', 10, 2);

	// Render callback of our blocks
	function acf_block_render_callback($block) {
		$blockslug = str_replace('acf/', '', $block['name']);
		$view = block_path($blockslug, 'view.php');

		if (file_exists($view)) {
			acf_setup_meta($block['data'] ?? [], $block['id'], true);
			extract((array) get_field(sprintf('group_%s_fields', $blockslug), $block['id']));
			include $view;
			acf_reset_meta($block['id']);
		}
	}

	// Register all blocks via block.json on the init hook
	function register_theme_block_types() {
		$blocks_dir = get_theme_file_path('blocks/');
		foreach (array_diff(scandir($blocks_dir), ['.', '..']) as $folder) {
			$block_path = $blocks_dir . $folder;
			if (is_dir($block_path) && file_exists($block_path . '/block.json')) {
				register_block_type($block_path);
			}
		}
	}
	add_action('init', 'register_theme_block_types');

	// Register ACF field groups for all blocks
	function acf_register_block_fields() {
		foreach (get_blocks() as $block) {
			register_acf_fields($block);
		}
	}
	add_action('acf/init', 'acf_register_block_fields');

	add_filter('allowed_block_types_all', 'allowed_block_types', 10, 2);
	function allowed_block_types($allowed_blocks, $post) {
		// Ensure that $allowed_blocks is an array
		if (!is_array($allowed_blocks)) {
			$allowed_blocks = [];
		}

		// Add the default WordPress blocks you want to include
		$default_blocks = [
			'core/block',
			'core/heading',
			'core/html',
			'core/list',
			'core/list-item',
			'core/image',
			'core/paragraph',
			'core/quote',
			'core/shortcode',
			// Add more default blocks here
		];

		// Call the function to get the list of block names
		$theme_blocks = get_theme_blocks();

		// Merge the theme block names and default WordPress blocks with the allowed blocks
		$allowed_blocks = array_merge($allowed_blocks, $theme_blocks, $default_blocks);

		return $allowed_blocks;
	}

	// Define a function to get the list of block names from the theme's block directory
	function get_theme_blocks() {
		$theme_blocks = [];
		$blocks = get_blocks(); // Your existing function to get blocks

		foreach ($blocks as $block) {
			// Modify this condition to only include ACF blocks
			$theme_blocks[] = 'acf/' . $block['name'];
		}

		return $theme_blocks;
	}


	// Get all blocks located in our theme folder
	function get_blocks() {
		$directory = get_theme_file_path('blocks/');

		$dirEntries = array_diff(scandir($directory), ['..', '.']);

		$blocks = array_map(
			function ($file) {
				try {
					if (
						is_dir(block_path($file))
						&& file_exists(block_path($file, 'view.php'))
						&& file_exists(block_path($file, 'block.json'))
					) {
						$json = json_decode(file_get_contents(block_path($file, 'block.json')), true);
						$config_path = block_path($file, 'config.php');
						$config = file_exists($config_path) ? (include $config_path) : [];

						return [
							'name' => $file,
							'title' => $json['title'],
							'fields' => $config['fields'] ?? [],
						];
					}

					return false;
				} catch (Exception $e) {
					return false;
				}
			},
			$dirEntries
		);

		return array_filter($blocks);
	}

	function block_path(string $block, string $filename = '') {
		return get_theme_file_path(sprintf('blocks/%s/%s', $block, $filename));
	}

	function register_acf_fields($block) {
		if (!isset($block['fields']) || empty($block['fields'])) {
			return $block;
		}

		if (!in_array($block['name'], ['background', 'hero'])) {
			$block['fields'] = array_merge(get_general_settings(), $block['fields']);
		}

		$subfields = [];

		foreach ($block['fields'] as $key => $field) {
			$subfields[] = array_merge([
				'key' => sprintf('field_%s_%s', $block['name'], $key),
				'name' => $key,
			], $field);
		}

		$group = [
			'key' => sprintf('field_group_%s', $block['name']),
			'title' => sprintf('%s: %s', esc_html__('Block', 'lumie'), $block['title']),
			'category' => 'block-elements',
			'fields' => [
				[
					'key' => sprintf('group_%s', $block['name']),
					'label' => $block['title'],
					'name' => sprintf('group_%s_fields', $block['name']),
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => $subfields,
				],
			],
			'location' => [
				[
					[
						'param' => 'block',
						'operator' => '==',
						'value' => sprintf('acf/%s', $block['name']),
					],
				],
			],
		];

		acf_add_local_field_group($group);

		return $block;
	}

	function my_filter_block_type_metadata(array $metadata) {
		$block_version = get_field('blocks', 'utilities')['version'] ?? 3;

		// Check if the block is an ACF block by looking for the 'acf' key in the metadata
		if (isset($metadata['acf'])) {
			$metadata['$schema'] = "https://schemas.wp.org/trunk/block.json";
			$metadata['apiVersion'] = 3;
			$metadata['category'] = 'lumie';
			$metadata['icon'] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 322 120"><g transform="translate(10,10)"><g fill="none" stroke="currentColor" stroke-width="17"><path d="M 8.5 0 V 100"/><path d="M 37.5 38 V 69 A 22.5 22.5 0 0 0 82.5 69 V 38"/><path d="M 111.5 100 V 66.25 A 19.75 19.75 0 0 1 151 66.25 V 100"/><path d="M 151 100 V 66.25 A 19.75 19.75 0 0 1 190.5 66.25 V 100"/><path d="M 219.5 38 V 100"/><path d="M 249.62 62 H 292.38 A 22.5 22.5 0 1 0 289.43 81.9"/></g><path fill="currentColor" d="M 219.5 2 Q 221.74 13.76 233.5 16 Q 221.74 18.24 219.5 30 Q 217.26 18.24 205.5 16 Q 217.26 13.76 219.5 2 Z"/></g></svg>';
			$metadata['acf']['blockVersion'] = $block_version;
			$metadata['acf']['renderCallback'] = "acf_block_render_callback";
			$metadata['acf']['hideFieldsInSidebar'] = true;
		}

		return $metadata;
	}
	add_filter('block_type_metadata', 'my_filter_block_type_metadata');
}
