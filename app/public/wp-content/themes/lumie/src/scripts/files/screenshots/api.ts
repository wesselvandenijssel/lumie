/**
 * WordPress REST API Integration
 *
 * Simplified API client for finding which pages contain specific blocks.
 */

import { getCachedWordPressUrl, getScreenshotsApiKey } from "./config";

interface PageData {
	id: number;
	title: string;
	url: string;
	slug: string;
	post_type: string;
}

interface BlockPagesResponse {
	block: string;
	total: number;
	pages: PageData[];
}

/**
 * Get pages that contain a specific block
 */
export async function getPagesWithBlock(blockName: string): Promise<string[]> {
	try {
		const url = `${getCachedWordPressUrl()}/wp-json/screenshots/v1/blocks/${blockName}`;
		const response = await fetch(url, {
			headers: { "X-Screenshots-Key": getScreenshotsApiKey() },
		});

		// Get response as text first
		const text = await response.text();

		if (!response.ok) {
			console.error(
				`API Error (${response.status}):`,
				text.substring(0, 300),
			);
			throw new Error(`API returned ${response.status}`);
		}

		// WordPress might output debug notices before JSON
		// Extract JSON from response (look for first { or [)
		let jsonText = text;
		const jsonStart = text.search(/[{[]/);
		if (jsonStart > 0) {
			jsonText = text.substring(jsonStart);
		}

		// Parse JSON
		const data: BlockPagesResponse = JSON.parse(jsonText);
		return data.pages.map((page) => page.url);
	} catch (error) {
		if (error instanceof SyntaxError) {
			console.error(`Invalid JSON response for block ${blockName}`);
		} else {
			console.error(
				`Error fetching pages for block ${blockName}:`,
				error,
			);
		}
		return [];
	}
}
