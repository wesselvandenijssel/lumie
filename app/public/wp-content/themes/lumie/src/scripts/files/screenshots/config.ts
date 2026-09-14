import { readFileSync } from "fs";
import { join } from "path";

/**
 * Screenshot System Configuration
 *
 * Centralized configuration for the screenshot generation system.
 * Defines breakpoints, paths, and special file patterns.
 */

/**
 * Viewport breakpoint configuration
 */
export interface Breakpoint {
	name: string;
	width: number;
	height: number;
}

/**
 * Block to screenshot with metadata
 */
export interface BlockInfo {
	name: string;
	reason:
		| "direct"
		| "component"
		| "buttons"
		| "content-layout"
		| "titles"
		| "color-palette";
}

/**
 * Breakpoints for screenshot generation
 */
export const BREAKPOINTS: Breakpoint[] = [
	{ name: "mobile", width: 375, height: 1600 },
	{ name: "narrow", width: 820, height: 1380 },
	{ name: "normal", width: 1180, height: 1200 },
	{ name: "large", width: 1600, height: 1200 },
];

/**
 * Screenshot output directory
 */
export const SCREENSHOTS_DIR = "playwright/screenshots";

/**
 * Maximum screenshots per block to prevent excessive generation
 */
export const MAX_SCREENSHOTS_PER_BLOCK = 5;

/**
 * WordPress site URL detection
 *
 * Tries multiple strategies to find the WordPress URL:
 * 1. Extract from path (/Local Sites/[site-name]/)
 * 2. Environment variable LOCAL_URL
 * 3. Fallback to localhost
 */
export function getWordPressUrl(): string {
	// Strategy 1: Extract from current path, look for "Local Sites" and normalize Windows paths
	const cwd = process.cwd().replace(/\\/g, "/");
	const match = cwd.match(/Local Sites\/([^/]+)/);
	if (match) {
		const siteName = match[1];
		console.log(`Detected site: ${siteName}.local`);
		return `http://${siteName}.local`;
	}

	// Strategy 2: Environment variable
	if (process.env.LOCAL_URL) {
		return process.env.LOCAL_URL;
	}

	// Strategy 3: Fallback
	console.warn("Could not detect WordPress URL, using localhost");
	return "http://localhost";
}

/**
 * Memoized getter for the WordPress URL to avoid side effects at import time.
 *
 * The URL is resolved on first call and cached for subsequent calls.
 */
let cachedWordPressUrl: string | null = null;

export function getCachedWordPressUrl(): string {
	if (cachedWordPressUrl === null) {
		cachedWordPressUrl = getWordPressUrl();
	}

	return cachedWordPressUrl;
}

/**
 * Read the screenshots API secret token from the .screenshots-token file
 * written by WordPress on first REST API initialisation.
 *
 * @returns The secret token string, or empty string if the file is not found
 */
export function getScreenshotsApiKey(): string {
	try {
		const tokenFile = join(process.cwd(), ".screenshots-token");
		return readFileSync(tokenFile, "utf-8").trim();
	} catch {
		console.warn(
			"Warning: .screenshots-token not found. " +
				"Open the WordPress site once to generate it.",
		);
		return "";
	}
}

/**
 * Memoized getter for the screenshots API key to avoid re-reading the
 * token file on every request.
 */
let cachedScreenshotsApiKey: string | null = null;

export function getCachedScreenshotsApiKey(): string {
	if (cachedScreenshotsApiKey === null) {
		cachedScreenshotsApiKey = getScreenshotsApiKey();
	}

	return cachedScreenshotsApiKey;
}

/**
 * WordPress URL constant for backwards compatibility
 * @deprecated Use getCachedWordPressUrl() instead
 */
export const WORDPRESS_URL = getCachedWordPressUrl();

/**
 * Pages to exclude from screenshots (bedankt, privacy, etc.)
 */
export const EXCLUDED_PATHS = [
	"contact/bedankt",
	"contact/disclaimer",
	"contact/privacy-statement",
	"sitemap",
];

/**
 * File patterns that trigger specific block screenshots
 */
export const TRIGGER_PATTERNS = {
	header: [
		"header.php",
		"src/styles/components/head/_header.scss",
		"src/styles/components/head/menu/_menu.scss",
		"src/styles/components/head/menu/_toggle.scss",
		"src/styles/components/head/menu/_navigation.scss",
	],
	footer: [
		"footer.php",
		"src/styles/components/footer/_footer-top.scss",
		"src/styles/components/footer/_footer-bottom.scss",
	],
	"404": ["404.php", "src/styles/components/_404.scss"],
	buttons: ["src/styles/components/_buttons.scss"],
	contentLayout: [
		"src/styles/components/_content-layout.scss",
		"src/classes/flex-content.php",
	],
	titles: ["src/styles/components/_titles.scss"],
	colorPalette: [
		"src/functions/custom-color-palette.php",
		"src/styles/components/_color-palette.scss",
	],
};
