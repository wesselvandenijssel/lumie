<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Modify video attributes for lazy loading and YouTube parameters
 *
 * This function checks if the video is a YouTube link and modifies the src attribute
 * to include parameters that disable related videos and adds lazy loading if not already present.
 *
 * @param string $video The video iframe HTML
 * @return string Modified video HTML with updated src and lazy loading attribute
 */
function modify_video_attributes($video) {
	if (!preg_match('/src="(.+?)"/', $video, $matches)) return $video;

	$src = $matches[1];

	// Check if the source is a YouTube link
	if (strpos($src, 'youtube.com') !== false || strpos($src, 'youtu.be') !== false) {
		$params = [
			'rel' => 0,
		];

		$new_src = add_query_arg($params, $src);
		$video = str_replace($src, $new_src, $video);

		// Add lazy loading to iframe if not already present
		if (strpos($video, 'loading=') === false) {
			$video = preg_replace('/<iframe(.*?)>/', '<iframe loading="lazy"$1>', $video);
		}
	}

	return $video;
}

/**
 * Process video URL and add fancybox attributes to wrapper
 *
 * This function extracts YouTube URL from iframe, applies video attributes modification,
 * and returns the modified attributes array with fancybox data attributes.
 *
 * @param string $video The video iframe HTML
 * @param array $attrs attributes array
 * @return array Modified attributes array with fancybox data
 */
function video_in_fancybox($video, $attrs = []) {
	if (empty($video)) {
		return $attrs;
	}

	$modified_video = modify_video_attributes($video);
	$modified_video_url = '';

	if (preg_match('/src="([^"]*)"/', $modified_video, $mod_matches)) {
		$modified_video_url = $mod_matches[1];
	}
	if (!empty($modified_video_url)) {
		$attrs['class'][] = 'video-in-fancybox';
		$attrs['data-src'] = esc_url($modified_video_url);
		$attrs['data-type'] = 'iframe';
		$attrs['data-width'] = '1280';
		$attrs['data-height'] = '720';
	}

	return $attrs;
}
