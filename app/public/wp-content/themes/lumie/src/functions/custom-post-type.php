<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

// Flush rewrite rules for custom post types
add_action('after_switch_theme', 'bones_flush_rewrite_rules');

// Flush your rewrite rules
function bones_flush_rewrite_rules() {
	flush_rewrite_rules();
}

function custom_team_member() {
	register_post_type(
		'team_member', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		[
			'labels' => [
				'name' => esc_html__('Teamleden', 'mbeffect'),
				'singular_name' => esc_html__('Teamlid', 'mbeffect'),
				'all_items' => esc_html__('Alle teamleden', 'mbeffect'),
				'add_new' => esc_html__('Nieuw teamlid', 'mbeffect'),
				'add_new_item' => esc_html__('Nieuw teamlid', 'mbeffect'),
				'edit' => esc_html__('Bewerken', 'mbeffect'),
				'edit_item' => esc_html__('Teamlid bewerken', 'mbeffect'),
				'new_item' => esc_html__('Nieuw teamlid', 'mbeffect'),
				'view_item' => esc_html__('Teamlid bekijken', 'mbeffect'),
				'search_items' => esc_html__('Teamleden zoeken', 'mbeffect'),
				'not_found' => esc_html__('Geen teamleden gevonden.', 'mbeffect'),
				'not_found_in_trash' => esc_html__('Geen teamleden gevonden', 'mbeffect'),
				'parent_item_colon' => ''
			],
			'description' => '',
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8,
			'menu_icon' => 'dashicons-groups',
			'rewrite' => ['slug' => 'team_member', 'with_front' => false],
			'has_archive' => '',
			'capability_type' => 'post',
			'hierarchical' => true,
			'show_in_rest' => true,
			'supports' => [
				'title',
				'thumbnail',
				'custom-fields',
				'revisions',
				'page-attributes',
				'excerpt',
			]
		]
	);
}
add_action('init', 'custom_team_member');

function custom_project() {
	register_post_type(
		'project', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		[
			'labels' => [
				'name' => esc_html__('Projecten', 'mbeffect'),
				'singular_name' => esc_html__('Project', 'mbeffect'),
				'all_items' => esc_html__('Alle projecten', 'mbeffect'),
				'add_new' => esc_html__('Nieuw project', 'mbeffect'),
				'add_new_item' => esc_html__('Nieuw project', 'mbeffect'),
				'edit' => esc_html__('Bewerken', 'mbeffect'),
				'edit_item' => esc_html__('Project bewerken', 'mbeffect'),
				'new_item' => esc_html__('Nieuw project', 'mbeffect'),
				'view_item' => esc_html__('Project bekijken', 'mbeffect'),
				'search_items' => esc_html__('Projecten zoeken', 'mbeffect'),
				'not_found' => esc_html__('Geen projecten gevonden.', 'mbeffect'),
				'not_found_in_trash' => esc_html__('Geen projecten gevonden', 'mbeffect'),
				'parent_item_colon' => ''
			],
			'description' => '',
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8,
			'menu_icon' => 'dashicons-portfolio',
			'rewrite' => ['slug' => 'project', 'with_front' => false],
			'has_archive' => '',
			'capability_type' => 'post',
			'hierarchical' => true,
			'show_in_rest' => true,
			'supports' => [
				'title',
				'thumbnail',
				'custom-fields',
				'revisions',
				'page-attributes',
				'excerpt',
				'editor',
			]
		]
	);
}
add_action('init', 'custom_project');

/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/

// register_taxonomy(
// 	'product_category',
// 	['product'],
// 	[
// 		'hierarchical' => true, // True for categories, false for tags
// 		'labels' => [
// 			'name' => esc_html__('Productcategorieën', 'mbeffect'),
// 			'singular_name' => esc_html__('Productcategorie', 'mbeffect'),
// 			'search_items' => esc_html__('Zoek productcategorie', 'mbeffect'),
// 			'all_items' => esc_html__('Alle productcategorieën', 'mbeffect'),
// 			'parent_item' => esc_html__('Parent productcategorie', 'mbeffect'),
// 			'parent_item_colon' => esc_html__('Parent productcategorie:', 'mbeffect'),
// 			'edit_item' => esc_html__('Productcategorie bewerken', 'mbeffect'),
// 			'update_item' => esc_html__('Productcategorie updaten', 'mbeffect'),
// 			'add_new_item' => esc_html__('Nieuwe productcategorie', 'mbeffect'),
// 			'new_item_name' => esc_html__('Nieuwe productcategorie', 'mbeffect')
// 		],
// 		'show_admin_column' => true,
// 		'show_in_rest' => true,
// 		'show_ui' => true,
// 		'query_var' => true,
// 	]
// );

// register_taxonomy(
// 	'product_tag',
// 	['product'],
// 	[
// 		'hierarchical' => false, // True for categories, false for tags
// 		'labels' => [
// 			'name' => esc_html__('Tags', 'mbeffect'),
// 			'singular_name' => esc_html__('Tag', 'mbeffect'),
// 			'search_items' => esc_html__('Zoek tag', 'mbeffect'),
// 			'all_items' => esc_html__('Alle tags', 'mbeffect'),
// 			'parent_item' => esc_html__('Parent tag', 'mbeffect'),
// 			'parent_item_colon' => esc_html__('Parent tag:', 'mbeffect'),
// 			'edit_item' => esc_html__('Tag bewerken', 'mbeffect'),
// 			'update_item' => esc_html__('Tag updaten', 'mbeffect'),
// 			'add_new_item' => esc_html__('Nieuwe tag', 'mbeffect'),
// 			'new_item_name' => esc_html__('Nieuwe tag', 'mbeffect')
// 		],
// 		'show_admin_column' => true,
// 		'show_in_rest' => true,
// 		'show_ui' => true,
// 		'query_var' => true,
// 	]
// );

function custom_vacancy() {
	register_post_type(
		'vacancy',
		[
			'labels' => [
				'name' => esc_html__('Vacatures', 'mbeffect'),
				'singular_name' => esc_html__('Vacature', 'mbeffect'),
				'all_items' => esc_html__('Alle vacatures', 'mbeffect'),
				'add_new' => esc_html__('Nieuwe vacature', 'mbeffect'),
				'add_new_item' => esc_html__('Nieuwe vacature', 'mbeffect'),
				'edit' => esc_html__('Bewerken', 'mbeffect'),
				'edit_item' => esc_html__('Vacature bewerken', 'mbeffect'),
				'new_item' => esc_html__('Nieuwe vacature', 'mbeffect'),
				'view_item' => esc_html__('Vacature bekijken', 'mbeffect'),
				'search_items' => esc_html__('Vacatures zoeken', 'mbeffect'),
				'not_found' => esc_html__('Geen vacatures gevonden.', 'mbeffect'),
				'not_found_in_trash' => esc_html__('Geen vacatures gevonden', 'mbeffect'),
				'parent_item_colon' => ''
			],
			'description' => esc_html__('', 'mbeffect'),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8,
			'menu_icon' => 'dashicons-share',
			'rewrite' => ['slug' => 'vacancy', 'with_front' => false],
			'has_archive' => '',
			'capability_type' => 'post',
			'hierarchical' => true,
			'show_in_rest' => true,
			'supports' => [
				'custom-fields',
				'editor',
				'excerpt',
				'page-attributes',
				'revisions',
				'thumbnail',
				'title',
			]
		]
	);
}
add_action('init', 'custom_vacancy');


function custom_popups() {
	register_post_type(
		'popup', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		[
			'labels' => [
				'name' => esc_html__('Pop-ups', 'mbeffect'),
				'singular_name' => esc_html__('Pop-up', 'mbeffect'),
				'all_items' => esc_html__('Alle pop-ups', 'mbeffect'),
				'add_new' => esc_html__('Nieuwe pop-up', 'mbeffect'),
				'add_new_item' => esc_html__('Nieuwe pop-up', 'mbeffect'),
				'edit' => esc_html__('Bewerken', 'mbeffect'),
				'edit_item' => esc_html__('Pop-up bewerken', 'mbeffect'),
				'new_item' => esc_html__('Nieuwe pop-up', 'mbeffect'),
				'view_item' => esc_html__('Pop-up bekijken', 'mbeffect'),
				'search_items' => esc_html__('Pop-ups zoeken', 'mbeffect'),
				'not_found' => esc_html__('Geen pop-ups gevonden.', 'mbeffect'),
				'not_found_in_trash' => esc_html__('Geen pop-ups gevonden', 'mbeffect'),
				'parent_item_colon' => ''
			],
			'description' => '',
			'public' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8,
			'menu_icon' => 'dashicons-slides',
			'rewrite' => ['slug' => 'popup', 'with_front' => false],
			'has_archive' => '',
			'capability_type' => 'post',
			'hierarchical' => true,
			'show_in_rest' => false,
			'supports' => [
				'title',
			]
		]
	);
}
add_action('init', 'custom_popups');
