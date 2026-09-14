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
					'title' => esc_html__('MB blokken', 'mbeffect'),
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
			'title' => sprintf('%s: %s', esc_html__('Block', 'mbeffect'), $block['title']),
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
			$metadata['category'] = 'mb-blocks';
			$metadata['icon'] = "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 25.385353 12.023844\"><g fill=\"#000\" transform=\"translate(-288.01821 -242.64453)\"><path d=\"m299.60695 246.66613c-1.19063 0-2.27542.60854-2.8575 1.40229-.3175-.79375-1.37584-1.37583-2.48709-1.37583-.0265 0-.0265 0-.0529 0-.0794 0-.15875-.0265-.23813-.0265-1.19062 0-2.40771.635-2.98979 1.42875-.0265-.0794-.0794-.13229-.13229-.15875-.23813-.39688-.47625-.55563-.47625-.55563-1.05833-.97895-2.35479-.84666-2.35479-.84666v1.98437c.635-.10583.97896.29104 1.16416.68792.1323.29104.21167.60854.21167.89958v.58209 3.81h1.87854.39688v-3.91584c0-1.03187.66146-1.95791 1.77271-1.95791.92604 0 1.42875.66145 1.42875 1.98437v3.88938h2.43416v-3.91584c0-1.03187.66146-1.95791 1.77271-1.95791.92604 0 1.42875.66145 1.42875 1.98437v3.88938h2.27542v-4.20688c0-2.2225-.92604-3.62479-3.175-3.62479z\"/><path d=\"m312.01591 247.61863c-.97896-.84667-2.27542-1.24354-3.54542-1.00542-.3175.0529-.55563.13229-.76729.21167v2.03729c1.16416-.47625 2.14312-.0529 2.14312-.0529.74084.3175 1.05834.82021 1.13771 1.29646.15875.635-.0794 1.32292-.52917 1.79917-.39687.42333-.87312.58208-1.29645.635-1.19063.13229-2.2225-.68792-2.38125-1.85208-.0529-.37042-.0265-.635-.0265-.635l-.0265.0265v-7.43479h-2.54l.0265 7.85813c-.0265 2.67229 2.51354 3.73062 2.51354 3.73062 1.905.97896 3.99521 0 3.99521 0 3.01625-1.34937 2.67229-3.86291 2.67229-3.86291-.0794-1.27-.71438-2.16959-1.37583-2.75167z\"/></g></svg>";
			$metadata['acf']['blockVersion'] = $block_version;
			$metadata['acf']['renderCallback'] = "acf_block_render_callback";
			$metadata['acf']['hideFieldsInSidebar'] = true;
		}

		return $metadata;
	}
	add_filter('block_type_metadata', 'my_filter_block_type_metadata');
}
