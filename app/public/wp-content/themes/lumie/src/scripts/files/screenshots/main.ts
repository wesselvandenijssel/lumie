/**
 * Screenshot Generation - Main Entry Point
 *
 * Simplified screenshot system that:
 * 1. Detects changed blocks via git
 * 2. Resolves dependencies (components, styles)
 * 3. Uses WordPress API to find relevant pages
 * 4. Captures screenshots with Playwright
 */

import { detectChangedBlocks } from "./detector";
import {
	captureScreenshot,
	closeBrowser,
	findBlocksWithSelector,
	findBlocksOnPage,
} from "./capture";
import { getPagesWithBlock } from "./api";
import {
	BREAKPOINTS,
	MAX_SCREENSHOTS_PER_BLOCK,
	getCachedWordPressUrl,
} from "./config";

async function main() {
	try {
		// Check for detect-only flag for GitHub Actions
		const detectOnly = process.argv.includes("--detect-only");

		// Parse command line arguments for --block and --page
		const args = process.argv.slice(2);
		const blockArg = args.find((arg) => arg.startsWith("--block="));
		const pageArg = args.find((arg) => arg.startsWith("--page="));

		const specificBlock = blockArg?.split("=")[1];
		const specificPage = pageArg?.split("=")[1];

		if (!detectOnly) {
			console.log("\n=== Screenshot Generation ===\n");
			if (specificBlock) {
				console.log(`Targeting specific block: ${specificBlock}`);
			}
			if (specificPage) {
				console.log(`Targeting specific page: ${specificPage}\n`);
			}
		}

		// Step 1: Detect changed blocks or use specific block
		let changedBlocks;
		if (specificBlock) {
			// User specified a block - require page to be specified too
			if (!specificPage) {
				console.error(
					"Error: --block requires --page to be specified.\nYou must provide a specific page URL to avoid screenshotting thousands of pages.\n",
				);
				process.exit(1);
			}
			changedBlocks = [{ name: specificBlock, reason: "manual" }];
		} else if (specificPage) {
			// Only page specified - detect all blocks on that page
			if (!detectOnly) {
				console.log(
					"Page specified without block - detecting all blocks on page...",
				);
			}
			// Placeholder blocks to be detected in browser later
			changedBlocks = [
				{ name: "__DETECT_ALL_BLOCKS__", reason: "direct" },
			];
		} else {
			// Auto-detect changed blocks
			changedBlocks = detectChangedBlocks();
		}

		if (changedBlocks.length === 0) {
			if (!detectOnly) {
				console.log("No blocks or components need screenshot updates.");
			}
			return;
		}

		// For GitHub Actions: just output block names
		if (detectOnly) {
			const blockNames = changedBlocks.map((b) => b.name).join(" ");
			console.log(blockNames);
			return;
		}

		console.log(`Found ${changedBlocks.length} block(s) to screenshot:`);
		changedBlocks.forEach((b) => console.log(` - ${b.name} (${b.reason})`));
		console.log();

		// Step 2: Resolve browser-based detections
		const blocksToProcess = [];

		for (const blockInfo of changedBlocks) {
			// Browser-based detection markers
			if (blockInfo.name === "__DETECT_ALL_BLOCKS__") {
				console.log("Detecting all blocks on the specified page...");
				// Convert specificPage to full URL if needed
				let pageUrl = specificPage!;
				if (
					!pageUrl.startsWith("http://") &&
					!pageUrl.startsWith("https://")
				) {
					const wpUrl = getCachedWordPressUrl();
					pageUrl =
						wpUrl.replace(/\/$/, "") +
						"/" +
						pageUrl.replace(/^\//, "");
				}
				const blocks = await findBlocksOnPage(pageUrl);
				console.log(
					`Found ${blocks.length} block(s) on page: ${blocks.join(", ")}\n`,
				);
				blocks.forEach((name) =>
					blocksToProcess.push({ name, reason: blockInfo.reason }),
				);
			} else if (blockInfo.name === "__DETECT_BUTTONS__") {
				console.log("Detecting blocks with buttons in browser...");
				const blocks = await findBlocksWithSelector(".btn");
				console.log(
					`Found ${blocks.length} block(s) with buttons: ${blocks.join(", ")}\n`,
				);
				blocks.forEach((name) =>
					blocksToProcess.push({ name, reason: blockInfo.reason }),
				);
			} else if (blockInfo.name === "__DETECT_CONTENT_LAYOUT__") {
				console.log(
					"Detecting blocks with content-layout in browser...",
				);
				const blocks = await findBlocksWithSelector(".content-layout");
				console.log(
					`Found ${blocks.length} block(s) with content-layout: ${blocks.join(", ")}\n`,
				);
				blocks.forEach((name) =>
					blocksToProcess.push({ name, reason: blockInfo.reason }),
				);
			} else if (blockInfo.name === "__DETECT_TITLES__") {
				console.log("Detecting blocks with titles in browser...");
				const blocks = await findBlocksWithSelector(".titles");
				console.log(
					`Found ${blocks.length} block(s) with titles: ${blocks.join(", ")}\n`,
				);
				blocks.forEach((name) =>
					blocksToProcess.push({ name, reason: blockInfo.reason }),
				);
			} else {
				blocksToProcess.push(blockInfo);
			}
		}

		// Remove duplicates
		const uniqueBlocks = Array.from(
			new Map(blocksToProcess.map((b) => [b.name, b])).values(),
		);

		console.log(`Processing ${uniqueBlocks.length} unique block(s)\n`);

		// Step 3: Generate screenshots
		for (const blockInfo of uniqueBlocks) {
			console.log(`\nProcessing block: ${blockInfo.name}`);

			// Special blocks (header, footer, 404) don't use WordPress API
			const specialBlocks = ["header", "footer", "404"];
			let urls: string[] = [];

			if (specificPage) {
				// User specified a page URL or slug
				let pageUrl = specificPage;
				// If it's not a full URL, prepend WordPress URL
				if (
					!specificPage.startsWith("http://") &&
					!specificPage.startsWith("https://")
				) {
					const wpUrl = getCachedWordPressUrl();
					pageUrl =
						wpUrl.replace(/\/$/, "") +
						"/" +
						specificPage.replace(/^\//, "");
				}
				urls = [pageUrl];
				console.log(` Using specified page: ${pageUrl}`);
			} else if (specialBlocks.includes(blockInfo.name)) {
				// Use homepage for header/footer, special handling for 404 in capture.ts
				urls = [getCachedWordPressUrl()];
				console.log(` Using homepage for ${blockInfo.name}`);
			} else {
				// Get URLs where this block exists
				urls = await getPagesWithBlock(blockInfo.name);

				if (urls.length === 0) {
					console.warn(
						` No pages found for ${blockInfo.name}. Skipping.`,
					);
					continue;
				}

				// Limit URLs to prevent excessive screenshots
				urls = urls.slice(0, MAX_SCREENSHOTS_PER_BLOCK);
				console.log(
					` Found ${urls.length} URL(s), using ${urls.length}`,
				);
			}

			// Capture at each breakpoint
			for (const url of urls) {
				// Extract slug from URL if specificPage is provided
				let pageSlug: string | undefined;
				if (specificPage) {
					// Extract everything after the first / as slug
					const urlPath = new URL(url).pathname;
					const rawSlug =
						urlPath.replace(/^\/+/, "").replace(/\/$/, "") ||
						"home";
					// Slugs with dashes are fine, just ensure it's filesystem-safe
					pageSlug = rawSlug
						.replace(/[^a-z0-9/-]/gi, "-")
						.toLowerCase();
				}

				for (const breakpoint of BREAKPOINTS) {
					const success = await captureScreenshot(
						blockInfo.name,
						url,
						breakpoint,
						blockInfo.reason,
						pageSlug,
					);

					if (success) {
						console.log(` ✓ ${breakpoint.name}`);
					} else {
						console.warn(` ✗ ${breakpoint.name} - not found`);
					}
				}
			}
		}

		console.log("\n=== Screenshot Generation Complete ===\n");
	} finally {
		await closeBrowser();
	}
}

/**
 * Usage Examples:
 *
 * Auto-detect changed blocks:
 *	npm run screenshots
 *
 * Specific page (all blocks on that page):
 *	npm run screenshots -- --page=https://example.local/about
 *
 * Specific block on specific page (all instances):
 *	npm run screenshots -- --block=content-image --page=https://example.local/services
 *
 * Note: --block requires --page to prevent screenshotting thousands of pages
 */

main().catch((error) => {
	console.error("Fatal error:", error);
	process.exit(1);
});
