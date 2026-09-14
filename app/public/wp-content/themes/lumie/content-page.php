<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The template used for displaying page content in page.php
 *
 * @package lumie
 */
?>

<div class="entry-content">
	<?php component('breadcrumb'); ?>

	<?php
	the_content();
	?>
</div>
