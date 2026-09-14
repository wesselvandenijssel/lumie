/**
 * WordPress REST API Integration
 *
 * Simplified API client for finding which pages contain specific blocks.
 */

import { getCachedScreenshotsApiKey, getCachedWordPressUrl } from "./config";

interface PageData {
	id: number;
	title: string;
	url: string;
	slug: string;
	post_type: string;
}

interface BlocksBatchResponse {
	blocks: Record<string, PageData[]>;
}

/**
 * Get pages that contain each of the given blocks, in a single request.
 *
 * Batching avoids one full-site post scan per block on the WordPress side.
 */
export async function getPagesForBlocks(
	blockNames: string[],
): Promise<Map<string, string[]>> {
	const result = new Map<string, string[]>();
	if (blockNames.length === 0) return result;

	try {
		const namesParam = blockNames.join(",");
		const url = `${getCachedWordPressUrl()}/wp-json/screenshots/v1/blocks-batch?names=${encodeURIComponent(namesParam)}`;
		const response = await fetch(url, {
			headers: { "X-Screenshots-Key": getCachedScreenshotsApiKey() },
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
		const data: BlocksBatchResponse = JSON.parse(jsonText);
		for (const [blockName, pages] of Object.entries(data.blocks)) {
			result.set(
				blockName,
				pages.map((page) => page.url),
			);
		}
	} catch (error) {
		if (error instanceof SyntaxError) {
			console.error("Invalid JSON response for blocks batch request");
		} else {
			console.error("Error fetching pages for blocks:", error);
		}
	}

	return result;
}
