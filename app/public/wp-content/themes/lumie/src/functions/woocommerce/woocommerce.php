<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

add_theme_support('woocommerce');

// function mbeffect_wrapper_start() {
// 	echo '<section class="pad--top-medium pad--bottom-medium"><div class="columns-12 center">';
// }
// remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
// add_action( 'woocommerce_before_main_content', 'mbeffect_wrapper_start', 10 );

// function mbeffect_wrapper_end() {
// 	echo '</div></div></section>';
// }
// remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
// add_action( 'woocommerce_after_main_content', 'mbeffect_wrapper_end', 10 );

function columns_start() {
	echo '<div class="columns-12 center">';
}
function columns_end() {
	echo '</div>';
}

// Unhook sidebar
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

remove_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');

// Enable taxonomy fields for woocommerce with gutenberg on
function enable_taxonomy_rest($args) {
	$args['show_in_rest'] = true;
	return $args;
}
add_filter('woocommerce_taxonomy_args_product_cat', 'enable_taxonomy_rest');
add_filter('woocommerce_taxonomy_args_product_tag', 'enable_taxonomy_rest');



/*********************
	WOOCOMMERCE Functies
 *********************/
// Change number or products per row
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

//remove breadcrumbs
add_action('init', 'jk_remove_wc_breadcrumbs');
function jk_remove_wc_breadcrumbs() {
	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
}

//related products
add_action('init', 'remove_related_products');
function remove_related_products() {
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20, 0);
}

// Past de overzichtpagina's aan
add_action('woocommerce_after_shop_loop_item', 'verander_overzicht', 1);
function verander_overzicht() {
	if (is_product_category() || is_shop()) {
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 5);
		remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
	}
}
// Past de productpagina's aan
add_action('woocommerce_before_single_product_summary', 'verander_product', 1);
function verander_product() {
	remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
}

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 20);


//text aanpassingen
function my_text_strings($translated_text, $text, $domain) {
	$translated_text = match ($translated_text) {
		'Aanbieding' => esc_html__('Korting', 'woocommerce'),
		'In winkelmand' => esc_html__('In winkelwagen', 'woocommerce'),
		'Andere suggesties&hellip;' => esc_html__('Gerelateerde producten', 'woocommerce'),
		'Bestelling plaatsen' => esc_html__('Afrekenen', 'woocommerce'),
		default => $translated_text,
	};
	return $translated_text;
}
add_filter('gettext', 'my_text_strings', 20, 3);

//hide coupon field
function hide_coupon_field_on_checkout($enabled) {
	if (is_checkout()) $enabled = false;

	return $enabled;
}
add_filter('woocommerce_coupons_enabled', 'hide_coupon_field_on_checkout');
// hide coupon field on cart page
function hide_coupon_field_on_cart($enabled) {
	if (is_cart()) $enabled = false;

	return $enabled;
}
add_filter('woocommerce_coupons_enabled', 'hide_coupon_field_on_cart');


//Woocommerce niet op elke pagina laden
add_action('wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99);

function child_manage_woocommerce_styles() {
	//first check that woo exists to prevent fatal errors
	if (function_exists('is_woocommerce')) {
		if (!empty($GLOBALS['woocommerce'])) {
			remove_action('wp_head', [$GLOBALS['woocommerce'], 'generator']);
		}

		//dequeue scripts and styles
		if (!is_woocommerce() && !is_cart() && !is_checkout()) {
			wp_dequeue_style('woocommerce_frontend_styles');
			wp_dequeue_style('woocommerce_fancybox_styles');
			wp_dequeue_style('woocommerce_chosen_styles');
			wp_dequeue_style('woocommerce_prettyPhoto_css');
			wp_dequeue_script('wc_price_slider');
			wp_dequeue_script('wc-single-product');
			wp_dequeue_script('wc-add-to-cart');
			wp_dequeue_script('wc-cart-fragments');
			wp_dequeue_script('wc-checkout');
			wp_dequeue_script('wc-add-to-cart-variation');
			wp_dequeue_script('wc-single-product');
			wp_dequeue_script('wc-cart');
			wp_dequeue_script('wc-chosen');
			wp_dequeue_script('woocommerce');
			wp_dequeue_script('prettyPhoto');
			wp_dequeue_script('prettyPhoto-init');
			wp_dequeue_script('jquery-blockui');
			wp_dequeue_script('jquery-placeholder');
			wp_dequeue_script('fancybox');
			wp_dequeue_script('jqueryui');
		}
	}
}
