<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/* Login page aanpassingen */
//http://codex.wordpress.org/Plugin_API/Action_Reference/login_enqueue_scripts
function mbeffect_login_css() {
	wp_enqueue_style('mbeffect_login_css', get_template_directory_uri() . '/src/styles/login.css', false);
}

// changing the alt text on the logo to show your site name
function mbeffect_login_title() {
	return get_option('blogname');
}

// changing the logo link from wordpress.org to your site
function mbeffect_login_url() {
	return home_url();
}

// calling it only on the login page
add_action('login_enqueue_scripts', 'mbeffect_login_css', 10);
add_filter('login_headertext', 'mbeffect_login_title');
add_filter('login_headerurl', 'mbeffect_login_url');
