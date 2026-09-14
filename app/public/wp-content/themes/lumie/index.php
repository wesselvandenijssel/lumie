<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
		get_template_part('content', 'page');
	endwhile;
	?>

</div>

<?php get_footer(); ?>
