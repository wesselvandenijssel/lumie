<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

// Required classes
require_once('src/classes/button.php');
require_once('src/classes/flex-content.php');
require_once('src/classes/popup.php');
require_once('src/classes/title.php');
require_once('src/classes/walker-menu.php');
require_once('src/classes/walker-menu-fold.php');

// Required functions
require_once(get_template_directory() . '/src/functions/screenshots-api.php');
require_once(get_template_directory() . '/src/functions/theme-helpers.php');
require_once(get_template_directory() . '/src/functions/theme-support.php');
require_once(get_template_directory() . '/src/functions/video-helpers.php');
require(get_template_directory() . '/src/functions/autoload.php');

function mbeffect_setup() {
	// let's get language support going, if you need it
	load_theme_textdomain('mbeffect', get_template_directory() . '/src/languages');

	// launching operation cleanup
	add_action('init', 'mbeffect_head_cleanup');
	// A better title
	add_filter('wp_title', 'rw_title', 10, 3);
	// remove WP version from RSS
	add_filter('the_generator', 'mbeffect_rss_version');
	// remove pesky injected css for recent comments widget
	add_filter('wp_head', 'mbeffect_remove_wp_widget_recent_comments_style', 1);
	// clean up comment styles in the head
	add_action('wp_head', 'mbeffect_remove_recent_comments_style', 1);

	// enqueue base scripts and styles
	add_action('wp_enqueue_scripts', 'mbeffect_scripts_and_styles', 999);

	// cleaning up random code around images
	add_filter('the_content', 'mbeffect_filter_ptags_on_images');
	// cleaning up excerpt
	add_filter('excerpt_more', 'mbeffect_excerpt_more');
}

// let's get this party started
add_action('after_setup_theme', 'mbeffect_setup');
