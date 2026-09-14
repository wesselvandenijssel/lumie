<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

if (!function_exists('yoast_breadcrumb')) return;
?>

<div class="columns-12 breadcrumb center">
	<?php yoast_breadcrumb('<p class="breadcrumbs">', '</p>'); ?>
</div>
