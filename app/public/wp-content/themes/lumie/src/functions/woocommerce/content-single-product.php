<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Single product - https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/templates/content-single-product.php
 */

// Summary - Structure
add_action('woocommerce_before_single_product_summary', 'single_product_summary_start', 5, 0);
add_action('woocommerce_after_single_product_summary', 'single_product_summary_end', 5, 0);
function single_product_summary_start() {
	echo '<section class="columns-12 center pad--top-medium pad--bottom-medium"><div class="intro-grid">';
}
function single_product_summary_end() {
	echo '</div></section>';
}
// Summary - Rearranging
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40, 0);

// Product data tabs
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10, 0);

// Description
add_action('woocommerce_after_single_product_summary', 'single_product_description', 35, 0);
function single_product_description() {
	$post_content = apply_filters('the_content', get_post(get_the_ID())->post_content);

	if ($post_content) {
		echo $post_content;
	}
}
