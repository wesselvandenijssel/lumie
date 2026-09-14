<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

if (!function_exists('wpex_pagination')) {
	function wpex_pagination() {
		$prev_arrow = '';
		$next_arrow = '';

		global $wp_query;
		$total = $wp_query->max_num_pages;
		$big = 999999999; // need an unlikely integer
		if ($total > 1) {
			if (!$current_page = get_query_var('paged'))
				$current_page = 1;
			if (get_option('permalink_structure')) {
				$format = 'pagina/%#%/';
			} else {
				$format = '&paged=%#%';
			}

			echo '<div class="page-number-wrapper">';
			echo paginate_links([
				'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format' => $format,
				'current' => max(1, get_query_var('paged')),
				'total' => $total,
				'mid_size' => 1,
				'type' => 'list',
				'prev_text' => $prev_arrow,
				'next_text' => $next_arrow,
			]);
			echo '</div>';
		}
	}
}

if (!function_exists('wpex_pagination_outside_query')) {
	/**
	 * Display pagination for queries outside the main WP_Query.
	 * Uses GET parameter 'pagina' for page number.
	 *
	 * @param int $total Total number of pages
	 * @param string $anchor Optional anchor ID to append to pagination URLs
	 * @return void
	 */
	function wpex_pagination_outside_query($total, $anchor = '') {
		$total = absint($total);
		if ($total <= 1) {
			return;
		}

		// Get current page from query parameter
		$current_page = isset($_GET['pagina'])
			? max(1, min($total, absint($_GET['pagina'])))
			: 1;

		// Build pagination base URL
		$base = get_permalink() . '%_%';
		if (!empty($anchor)) {
			$base .= '#' . $anchor;
		}

		echo '<div class="page-number-wrapper">';
		echo paginate_links([
			'base' => $base,
			'format' => '?pagina=%#%',
			'current' => $current_page,
			'total' => $total,
			'mid_size' => 1,
			'type' => 'list',
			'prev_text' => '',
			'next_text' => '',
		]);
		echo '</div>';
	}
}
