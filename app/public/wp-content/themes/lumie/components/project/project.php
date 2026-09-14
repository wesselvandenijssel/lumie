<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$image = $image ?? 0;
$title = $title ?? '';
$suptitle = $suptitle ?? '';
$thumbnail = $thumbnail ?? 'Project Landscape';
$link = $link ?? [];

if (empty($title) || empty($image)) return;

$project_attr['class'][] = 'project';

$project_attr['data-thumbnail'] = [esc_attr($thumbnail)];

if (!empty($link['url'])) {
	$project_attr['href'] = [esc_url($link['url'])];
	$project_attr['title'] = [esc_attr($link['title'] ?? '')];
	$project_attr['target'] = [esc_attr(!empty($link['target']) ? $link['target'] : '_self')];
}
?>

<?php if (!empty($link['url'])) : ?>
	<a <?php attr($project_attr); ?>>
	<?php else : ?>
		<article <?php attr($project_attr); ?>>
		<?php endif; ?>

		<div class="project__image-wrapper">
			<?= wp_get_attachment_image($image, $thumbnail, false, ['class' => 'project__image', 'loading' => 'lazy']); ?>
		</div>


		<div class="project__content">
			<?php if (!empty($suptitle)) : ?>
				<div class="project__suptitle">
					<?= esc_html($suptitle); ?>
				</div>
			<?php endif; ?>

			<h3 class="project__title">
				<?= esc_html($title); ?>
			</h3>
		</div>

		<?php if (empty($link['url'])) : ?>
		</article>
	<?php else: ?>
	</a>
<?php endif; ?>
