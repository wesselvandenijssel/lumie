<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Convert provided attribute to string
 * This is the default callback of the attr() function.
 *
 * @param mixed $attribute
 */
function attribute_map_callback($attribute, string $index): string {
	if (is_bool($attribute)) {
		return esc_attr($index);
	}

	if (is_string($attribute)) {
		return esc_attr($index) . '="' . esc_attr($attribute) . '"';
	}

	// For arrays, escape each value
	$escaped_values = array_map('esc_attr', $attribute);
	return sprintf('%s="%s"', esc_attr($index), implode(' ', $escaped_values));
}

/**
 * Build html attributes passed as array.
 */
function get_attr(array $attributes, ?callable $custom_callback = null): string {
	$atts = array_map($custom_callback ?? 'attribute_map_callback', $attributes, array_keys($attributes));

	return implode(' ', $atts);
}

/**
 * Transform an array to HTML attributes.
 *
 * Usage:
 * ```php
 * $attributes = [];
 * $attributes['class'][] = 'my-class';
 * $attributes['class'][] = 'my-class--modifier';
 * $attributes['id'] = 'my-id';
 * ?>
 * <div <?php attr($attributes); ?>>My div</div>
 * ```
 */
function attr(array $attributes, ?callable $custom_callback = null): void {
	echo get_attr($attributes, $custom_callback);
}
