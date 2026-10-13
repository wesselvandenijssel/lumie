<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Checks whether a post is set to index in Yoast: the post's own setting, or the default of its post type.
 * The site-wide "discourage search engines" option is ignored on purpose, so the sitemap also works on local and test sites.
 *
 * @param WP_Post $post The post to check
 * @return bool
 */
function lumie_is_indexed(WP_Post $post): bool {
	if ($post->post_password !== '') {
		return false;
	}

	if (!class_exists('WPSEO_Meta') || !class_exists('WPSEO_Options')) {
		return true;
	}

	$noindex = WPSEO_Meta::get_value('meta-robots-noindex', $post->ID);

	if ($noindex === '1') return false;
	if ($noindex === '2') return true;

	return !WPSEO_Options::get('noindex-' . $post->post_type, false);
}

/**
 * Returns the sitemap sections: every indexed item of every public post type, grouped per post type.
 * A new custom post type shows up automatically, titled with its own label. Pages come first, then blog posts, then the rest by label.
 * Hierarchical post types keep their hierarchy; children of a hidden item move up to that item's level.
 *
 * @param int $exclude Post ID to leave out, usually the sitemap page itself
 * @return array[] List of ['title' => string, 'items' => array] where each item is ['post' => WP_Post, 'children' => array]
 */
function lumie_sitemap_sections(int $exclude = 0): array {
	$post_types = get_post_types(['public' => true], 'objects');
	unset($post_types['attachment']);

	uasort($post_types, function (WP_Post_Type $a, WP_Post_Type $b): int {
		$rank = ['page' => 0, 'post' => 1];

		return ($rank[$a->name] ?? 2) <=> ($rank[$b->name] ?? 2) ?: strcasecmp($a->labels->name, $b->labels->name);
	});

	$sections = [];

	foreach ($post_types as $post_type) {
		if (class_exists('WPSEO_Options') && WPSEO_Options::get('noindex-' . $post_type->name, false)) continue;

		$is_post = $post_type->name === 'post';

		$posts = get_posts([
			'post_type' => $post_type->name,
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby' => $is_post ? 'date' : 'menu_order title',
			'order' => $is_post ? 'DESC' : 'ASC',
			'exclude' => $exclude ? [$exclude] : [],
		]);

		$posts = array_filter($posts, 'lumie_is_indexed');

		if (empty($posts)) continue;

		if ($post_type->name === 'page') {
			$posts = lumie_sitemap_sort_pages($posts);
		}

		$blog_page = (int) get_option('page_for_posts');

		$sections[] = [
			'title' => $is_post && $blog_page ? get_the_title($blog_page) : $post_type->labels->name,
			'items' => lumie_sitemap_tree($posts),
		];
	}

	return $sections;
}

/**
 * Sorts pages like the site: the front page first, then the order of the main menu, then the rest by title.
 *
 * @param WP_Post[] $pages Pages to sort
 * @return WP_Post[]
 */
function lumie_sitemap_sort_pages(array $pages): array {
	$order = [(int) get_option('page_on_front')];
	$locations = get_nav_menu_locations();

	if (!empty($locations['primary'])) {
		foreach (wp_get_nav_menu_items($locations['primary']) ?: [] as $item) {
			if ($item->object === 'page') {
				$order[] = (int) $item->object_id;
			}
		}
	}

	$order = array_flip(array_unique($order));

	usort($pages, function (WP_Post $a, WP_Post $b) use ($order): int {
		$rank_a = $order[$a->ID] ?? PHP_INT_MAX;
		$rank_b = $order[$b->ID] ?? PHP_INT_MAX;

		return $rank_a <=> $rank_b ?: strcasecmp($a->post_title, $b->post_title);
	});

	return $pages;
}

/**
 * Nests posts under their nearest visible ancestor.
 *
 * @param WP_Post[] $posts Visible posts of one post type
 * @return array[] List of ['post' => WP_Post, 'children' => array]
 */
function lumie_sitemap_tree(array $posts): array {
	$visible = [];

	foreach ($posts as $post) {
		$visible[$post->ID] = $post;
	}

	$children = [];

	foreach ($visible as $post) {
		$parent = 0;

		foreach (get_post_ancestors($post) as $ancestor) {
			if (isset($visible[$ancestor])) {
				$parent = $ancestor;
				break;
			}
		}

		$children[$parent][] = $post;
	}

	$build = function (int $parent) use (&$build, $children): array {
		return array_map(fn(WP_Post $post) => [
			'post' => $post,
			'children' => $build($post->ID),
		], $children[$parent] ?? []);
	};

	return $build(0);
}

/**
 * Renders a sitemap list, including nested lists for child pages.
 *
 * @param array[] $items Items from lumie_sitemap_tree()
 */
function lumie_sitemap_list(array $items): void {
	if (empty($items)) return;
	?>
	<ul class="sitemap__list">
		<?php foreach ($items as $item) : ?>
			<li class="sitemap__item">
				<a href="<?= esc_url(get_permalink($item['post'])); ?>"><?= esc_html(get_the_title($item['post'])); ?></a>
				<?php lumie_sitemap_list($item['children']); ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}
