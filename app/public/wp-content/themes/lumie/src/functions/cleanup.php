<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

function mbeffect_head_cleanup() {
	// EditURI link
	remove_action('wp_head', 'rsd_link');
	// windows live writer
	remove_action('wp_head', 'wlwmanifest_link');
	// previous link
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	// start link
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	// links for adjacent posts
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	// WP version
	remove_action('wp_head', 'wp_generator');
}

// http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
function rw_title($title, $sep, $seplocation) {
	global $page, $paged;

	// Don't affect in feeds.
	if (is_feed()) return $title;

	// Add the blog's name
	if ('right' == $seplocation) {
		$title .= get_bloginfo('name');
	} else {
		$title = get_bloginfo('name') . $title;
	}

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo('description', 'display');

	if ($site_description && (is_home() || is_front_page())) {
		$title .= " {$sep} {$site_description}";
	}

	// Add a page number if necessary:
	if ($paged >= 2 || $page >= 2) {
		$title .= " {$sep} " . sprintf(esc_html__('Page %s', 'mbeffect'), max($paged, $page));
	}

	return $title;
}

// remove WP version from RSS
function mbeffect_rss_version() {
	return '';
}

// remove injected CSS for recent comments widget
function mbeffect_remove_wp_widget_recent_comments_style() {
	if (has_filter('wp_head', 'wp_widget_recent_comments_style')) {
		remove_filter('wp_head', 'wp_widget_recent_comments_style');
	}
}

// remove injected CSS from recent comments widget
function mbeffect_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (!empty($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action('wp_head', [$wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style']);
	}
}
