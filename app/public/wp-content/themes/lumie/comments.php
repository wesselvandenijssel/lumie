<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

if (post_password_required()) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php if (have_comments()) : ?>
		<h2 class="comments-title">
			<?php
			printf(
				_nx('Reactie &ldquo;%2$s&rdquo;', 'Reacties &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'mbeffect'),
				number_format_i18n(get_comments_number()),
				'<span>' . esc_html(get_the_title()) . '</span>'
			);
			?>
		</h2>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
			<nav id="comment-nav-above" class="comment-navigation" role="navigation">
				<h1 class="screen-reader-text"><?= esc_html__('Comment navigation', 'mbeffect'); ?></h1>
				<div class="nav-previous"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'mbeffect')); ?></div>
				<div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'mbeffect')); ?></div>
			</nav>
		<?php endif; ?>

		<ol class="comment-list">
			<?php
			wp_list_comments(['callback' => 'mbeffect_comment']);
			?>
		</ol>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
			<nav id="comment-nav-below" class="comment-navigation" role="navigation">
				<h1 class="screen-reader-text"><?= esc_html__('Comment navigation', 'mbeffect'); ?></h1>
				<div class="nav-previous"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'mbeffect')); ?></div>
				<div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'mbeffect')); ?></div>
			</nav>
		<?php endif; ?>

	<?php endif; ?>

	<?php
	if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
	?>
		<p class="no-comments"><?= esc_html__('Comments are closed.', 'mbeffect'); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>
