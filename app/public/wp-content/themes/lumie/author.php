<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The Template for displaying the single page of the author.
 *
 * @package lumie
 */

if (!function_exists('get_field')) {
	echo 'Activate ACF for this theme to work';
	return;
}

get_header(); ?>

<div id="primary" class="content-area">
	<?php
	get_template_part('content', 'author');
	?>
</div>
<?php get_footer(); ?>
