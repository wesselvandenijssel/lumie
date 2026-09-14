<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Archive - https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/templates/archive-product.php
 */

add_action('woocommerce_before_main_content', 'wc_archive_start', 25, 0);
add_action('woocommerce_after_main_content', 'wc_archive_end', 20, 0);
function wc_archive_start() {
	if (is_archive()) : ?>
		<section class="pad--top-medium pad--bottom-medium">
			<div class="columns-12 center">
			<?php endif;
	}
	function wc_archive_end() {
		if (is_archive()) : ?>
			</div>
		</section>
<?php endif;
	}
