<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$title = $title ?? [];
$block = $block ?? [];
$centered = $centered ?? false;

if (empty($title) || empty($block) && !isset($title['main_title'])) return;

$title['main_title'] = sanitize_title_custom($title['main_title']);

$block_title = new BlockTitle($title['main_title']);

if (!empty($title['type']) && $title['type'] !== 'default') {
	// Set the heading type
	$block_title->setType(
		$title['type'] ?? 'h2',
	);
} else {
	// Set the heading element based on the calculated title element from the block
	$block_title->setType(
		'h' . calculate_title_element($block)
	);
}

if (!empty($title['type'])) {
	// Set the look (heading type) based on the provided title type
	$block_title->setLook(
		$title['type'],
	);
}

if (!empty($title['suptitle'])) {
	$title['suptitle'] = sanitize_title_custom($title['suptitle']);

	$block_title->setSuptitle($title['suptitle']);
}

if (!empty($title['subtitle'])) {
	$title['subtitle'] = sanitize_title_custom($title['subtitle']);

	$block_title->setSubtitle($title['subtitle']);
}

if (!empty($centered)) {
	$block_title->setCentered();
}

echo $block_title->getTitle();
