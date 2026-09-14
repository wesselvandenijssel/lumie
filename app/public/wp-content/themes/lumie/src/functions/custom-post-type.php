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
				'name' => esc_html__('Teamleden', 'lumie'),
				'singular_name' => esc_html__('Teamlid', 'lumie'),
				'all_items' => esc_html__('Alle teamleden', 'lumie'),
				'add_new' => esc_html__('Nieuw teamlid', 'lumie'),
				'add_new_item' => esc_html__('Nieuw teamlid', 'lumie'),
				'edit' => esc_html__('Bewerken', 'lumie'),
				'edit_item' => esc_html__('Teamlid bewerken', 'lumie'),
				'new_item' => esc_html__('Nieuw teamlid', 'lumie'),
				'view_item' => esc_html__('Teamlid bekijken', 'lumie'),
				'search_items' => esc_html__('Teamleden zoeken', 'lumie'),
				'not_found' => esc_html__('Geen teamleden gevonden.', 'lumie'),
				'not_found_in_trash' => esc_html__('Geen teamleden gevonden', 'lumie'),
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
				'name' => esc_html__('Projecten', 'lumie'),
				'singular_name' => esc_html__('Project', 'lumie'),
				'all_items' => esc_html__('Alle projecten', 'lumie'),
				'add_new' => esc_html__('Nieuw project', 'lumie'),
				'add_new_item' => esc_html__('Nieuw project', 'lumie'),
				'edit' => esc_html__('Bewerken', 'lumie'),
				'edit_item' => esc_html__('Project bewerken', 'lumie'),
				'new_item' => esc_html__('Nieuw project', 'lumie'),
				'view_item' => esc_html__('Project bekijken', 'lumie'),
				'search_items' => esc_html__('Projecten zoeken', 'lumie'),
				'not_found' => esc_html__('Geen projecten gevonden.', 'lumie'),
				'not_found_in_trash' => esc_html__('Geen projecten gevonden', 'lumie'),
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
// 			'name' => esc_html__('Productcategorieën', 'lumie'),
// 			'singular_name' => esc_html__('Productcategorie', 'lumie'),
// 			'search_items' => esc_html__('Zoek productcategorie', 'lumie'),
// 			'all_items' => esc_html__('Alle productcategorieën', 'lumie'),
// 			'parent_item' => esc_html__('Parent productcategorie', 'lumie'),
// 			'parent_item_colon' => esc_html__('Parent productcategorie:', 'lumie'),
// 			'edit_item' => esc_html__('Productcategorie bewerken', 'lumie'),
// 			'update_item' => esc_html__('Productcategorie updaten', 'lumie'),
// 			'add_new_item' => esc_html__('Nieuwe productcategorie', 'lumie'),
// 			'new_item_name' => esc_html__('Nieuwe productcategorie', 'lumie')
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
// 			'name' => esc_html__('Tags', 'lumie'),
// 			'singular_name' => esc_html__('Tag', 'lumie'),
// 			'search_items' => esc_html__('Zoek tag', 'lumie'),
// 			'all_items' => esc_html__('Alle tags', 'lumie'),
// 			'parent_item' => esc_html__('Parent tag', 'lumie'),
// 			'parent_item_colon' => esc_html__('Parent tag:', 'lumie'),
// 			'edit_item' => esc_html__('Tag bewerken', 'lumie'),
// 			'update_item' => esc_html__('Tag updaten', 'lumie'),
// 			'add_new_item' => esc_html__('Nieuwe tag', 'lumie'),
// 			'new_item_name' => esc_html__('Nieuwe tag', 'lumie')
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
				'name' => esc_html__('Vacatures', 'lumie'),
				'singular_name' => esc_html__('Vacature', 'lumie'),
				'all_items' => esc_html__('Alle vacatures', 'lumie'),
				'add_new' => esc_html__('Nieuwe vacature', 'lumie'),
				'add_new_item' => esc_html__('Nieuwe vacature', 'lumie'),
				'edit' => esc_html__('Bewerken', 'lumie'),
				'edit_item' => esc_html__('Vacature bewerken', 'lumie'),
				'new_item' => esc_html__('Nieuwe vacature', 'lumie'),
				'view_item' => esc_html__('Vacature bekijken', 'lumie'),
				'search_items' => esc_html__('Vacatures zoeken', 'lumie'),
				'not_found' => esc_html__('Geen vacatures gevonden.', 'lumie'),
				'not_found_in_trash' => esc_html__('Geen vacatures gevonden', 'lumie'),
				'parent_item_colon' => ''
			],
			'description' => esc_html__('', 'lumie'),
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
				'name' => esc_html__('Pop-ups', 'lumie'),
				'singular_name' => esc_html__('Pop-up', 'lumie'),
				'all_items' => esc_html__('Alle pop-ups', 'lumie'),
				'add_new' => esc_html__('Nieuwe pop-up', 'lumie'),
				'add_new_item' => esc_html__('Nieuwe pop-up', 'lumie'),
				'edit' => esc_html__('Bewerken', 'lumie'),
				'edit_item' => esc_html__('Pop-up bewerken', 'lumie'),
				'new_item' => esc_html__('Nieuwe pop-up', 'lumie'),
				'view_item' => esc_html__('Pop-up bekijken', 'lumie'),
				'search_items' => esc_html__('Pop-ups zoeken', 'lumie'),
				'not_found' => esc_html__('Geen pop-ups gevonden.', 'lumie'),
				'not_found_in_trash' => esc_html__('Geen pop-ups gevonden', 'lumie'),
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
