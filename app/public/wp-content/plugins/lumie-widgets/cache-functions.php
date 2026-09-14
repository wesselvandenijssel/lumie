<?php

/**
 * Cache Functions for MB Widgets Plugin
 * 
 * This file contains all caching-related functions for clearing various
 * WordPress caching plugins and systems.
 */

defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Clear all caches including Hummingbird
 */
function mbw_clear_all_caches() {

	// Clear Hummingbird cache if plugin is active
	if (class_exists('Hummingbird\\WP_Hummingbird')) {
		try {
			if (function_exists('do_action')) {
				do_action('wphb_clear_page_cache');
				do_action('wphb_clear_minify_cache');
				do_action('wphb_clear_browser_cache');
			}

			if (function_exists('wphb_clear_cache')) {
				wphb_clear_cache();
			} elseif (function_exists('wphb_flush_cache')) {
				wphb_flush_cache(false, false);
			}

			if (function_exists('delete_transient')) {
				delete_transient('wphb_cache');
				delete_transient('wphb_minify_cache');
				delete_transient('wphb_page_cache');
			}
		} catch (Exception $e) {
			if (function_exists('error_log')) {
				error_log('MB Widgets: Hummingbird cache clearing failed: ' . $e->getMessage());
			}
		}
	}

	// Clear other common caches
	// WordPress built-in cache
	if (function_exists('wp_cache_flush')) {
		wp_cache_flush();
	}

	// W3 Total Cache
	if (function_exists('w3tc_flush_all')) {
		w3tc_flush_all();
	}

	// WP Super Cache
	if (function_exists('wp_cache_clear_cache')) {
		wp_cache_clear_cache();
	}

	// LiteSpeed Cache
	if (class_exists('LiteSpeed\\Purge') && method_exists('LiteSpeed\\Purge', 'purge_all')) {
		try {
			\LiteSpeed\Purge::purge_all();
		} catch (Exception $e) {
		}
	}

	// WP Rocket
	if (function_exists('rocket_clean_domain')) {
		rocket_clean_domain();
	}

	// Autoptimize
	if (class_exists('autoptimizeCache') && method_exists('autoptimizeCache', 'clearall')) {
		try {
			autoptimizeCache::clearall();
		} catch (Exception $e) {
		}
	}

	// Clear opcache if available
	if (function_exists('opcache_reset')) {
		opcache_reset();
	}

	// Manual cache directory clearing as fallback
	mbw_clear_cache_directories();
}

/**
 * Manually clear common cache directories as fallback
 */
function mbw_clear_cache_directories() {
	// Common cache directories to clear
	$cache_dirs = [
		WP_CONTENT_DIR . '/cache/',
		WP_CONTENT_DIR . '/cache/hummingbird/',
		WP_CONTENT_DIR . '/cache/page/',
		WP_CONTENT_DIR . '/cache/minify/',
		WP_CONTENT_DIR . '/uploads/hummingbird-assets/',
		WP_CONTENT_DIR . '/wphb-cache/',
	];

	foreach ($cache_dirs as $dir) {
		if (is_dir($dir)) {
			mbw_recursive_rmdir($dir, false); // Don't remove the directory itself, just contents
		}
	}
}

/**
 * Recursively remove directory contents
 */
function mbw_recursive_rmdir($dir, $remove_dir = true) {
	if (!is_dir($dir)) {
		return;
	}

	$files = glob($dir . '/*');
	if ($files === false) return;

	foreach ($files as $file) {
		if (is_dir($file)) {
			mbw_recursive_rmdir($file, true);
		} else {
			@unlink($file);
		}
	}

	if ($remove_dir) {
		@rmdir($dir);
	}
}
