<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * @package lumie
 */
?>

<a href="<?= esc_url(get_permalink()); ?>" title="<?= esc_attr(get_the_title()); ?>" class="search-result">
	<div class="search-result__content">
		<h3 class="search-result__title">
			<?= esc_html(get_the_title()); ?>
		</h3>

		<div class="buttons search-result__buttons">
			<span class="btn btn--read-more">
				<?= esc_html__('Lees meer', 'lumie'); ?>
			</span>
		</div>
	</div>
</a>
