<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The Template for displaying all single posts.
 *
 * @package mbeffect
 */

if (!function_exists('get_field')) {
	echo 'Activate ACF for this theme to work';
	return;
}

get_header(); ?>

<div id="primary" class="content-area">

	<?php
	while (have_posts()) : the_post();
		get_template_part('content', 'single');
	endwhile;
	?>

</div>
<?php get_footer(); ?>
