/**
 * Screenshot Capture
 *
 * Simplified screenshot logic using Playwright's built-in APIs.
 * Handles blocks, header, footer, 404, and multiple instances.
 */

import {
	chromium,
	type Browser,
	type BrowserContext,
	type Page,
} from "playwright";
import { mkdirSync } from "fs";
import type { Breakpoint } from "./config";
import { getCachedWordPressUrl, SCREENSHOTS_DIR } from "./config";

let sharedBrowser: Browser | null = null;
const contextMap = new Map<string, BrowserContext>();
const pageMap = new Map<string, Page>();

async function getBrowser(): Promise<Browser> {
	if (!sharedBrowser) {
		sharedBrowser = await chromium.launch();
	}

	return sharedBrowser;
}

async function getPageForBreakpoint(breakpoint: Breakpoint): Promise<Page> {
	const existing = pageMap.get(breakpoint.name);
	if (existing) return existing;

	const browser = await getBrowser();
	const context = await browser.newContext({
		viewport: { width: breakpoint.width, height: breakpoint.height },
		ignoreHTTPSErrors: true,
	});
	const page = await context.newPage();

	contextMap.set(breakpoint.name, context);
	pageMap.set(breakpoint.name, page);

	return page;
}

export async function closeBrowser(): Promise<void> {
	const contexts: BrowserContext[] = [];
	contextMap.forEach((context) => {
		contexts.push(context);
	});

	for (let i = 0; i < contexts.length; i++) {
		await contexts[i].close();
	}

	contextMap.clear();
	pageMap.clear();

	if (sharedBrowser) {
		await sharedBrowser.close();
		sharedBrowser = null;
	}
}

/**
 * Capture header with expanded menus
 */
async function captureHeader(
	page: Page,
	breakpoint: Breakpoint,
	filepath: string,
): Promise<boolean> {
	const header = page.locator("header").first();
	if (!(await header.count())) return false;

	await header.scrollIntoViewIfNeeded();
	await page.waitForTimeout(200);

	const isMobile = breakpoint.name === "mobile";
	const bp = breakpoint.name;

	// Mobile: menu + submenu screenshots
	if (isMobile) {
		const getBounds = () =>
			page.evaluate(() => {
				const h = document.querySelector("header");
				const n = document.querySelector(".main-navigation__content");
				if (!h) return null;
				const hRect = h.getBoundingClientRect();
				const nRect = n?.getBoundingClientRect();
				const top = Math.max(hRect.top, 0);
				const bottom = nRect ? nRect.bottom : hRect.bottom;
				return {
					x: 0,
					y: top,
					width: hRect.width,
					height: bottom - top,
				};
			});

		const menuToggle = page.locator(".menu-toggle").first();
		if (await menuToggle.count()) {
			await menuToggle.click();
			await page.waitForTimeout(1100);
		}

		const menuBounds = await getBounds();
		if (menuBounds) {
			await page.screenshot({
				path: filepath.replace(/-mobile\.png$/, "-menu-mobile.png"),
				clip: menuBounds,
			});
		}

		await page.evaluate(() => {
			const nav = document.querySelector<HTMLElement>(
				".main-navigation__content",
			);
			if (nav) {
				nav.classList.add("active");
				nav.style.display = "block";
			}
			const root =
				nav?.querySelector(".main-navigation__menu--mobile") ??
				nav ??
				document;
			const toggle = root.querySelector<HTMLElement>(".submenu-toggle");
			if (!toggle) return;
			const sub = toggle.nextElementSibling as HTMLElement;
			if (!sub?.classList.contains("sub-menu")) return;
			toggle.dispatchEvent(
				new MouseEvent("click", { bubbles: true, cancelable: true }),
			);
			toggle.classList.add("submenu-toggle--active");
			Object.assign(sub.style, {
				display: "grid",
				visibility: "visible",
				opacity: "1",
				right: "0",
				height: "auto",
			});
			sub.classList.add("sub-menu--active");
		});

		await page.waitForTimeout(600);

		const subBounds = await getBounds();
		if (subBounds) {
			await page.screenshot({
				path: filepath.replace(/-mobile\.png$/, "-submenu-mobile.png"),
				clip: subBounds,
			});
		}

		return true;
	}

	// Desktop: unique submenu screenshots
	const count = await page
		.locator("ul.menu > .menu-item-has-children")
		.count();
	const seen = new Set<string>();
	let idx = 0;

	for (let i = 0; i < count; i++) {
		const text = await page.evaluate(
			(i) =>
				document
					.querySelectorAll("ul.menu > .menu-item-has-children")[i]
					?.querySelector<HTMLElement>(":scope > a, :scope > span")
					?.textContent?.trim() || null,
			i,
		);

		if (!text || seen.has(text)) continue;

		const result = await page.evaluate((i) => {
			const h = document.querySelector("header");
			const items = Array.from(
				document.querySelectorAll("ul.menu > .menu-item-has-children"),
			);
			if (!h || !items.length) return null;

			// Close all open submenus first
			items.forEach((el) => {
				el.querySelector<HTMLElement>(
					":scope > span",
				)?.classList.remove("active");
				el.querySelector<HTMLElement>(
					":scope > .submenu-toggle",
				)?.classList.remove("submenu-toggle--active");
				const sm = el.querySelector<HTMLElement>(":scope > .sub-menu");
				if (sm) {
					sm.classList.remove("sub-menu--active");
					sm.style.minHeight = "";
					Object.assign(sm.style, {
						display: "none",
						visibility: "",
						opacity: "",
					});
				}
				// Also close all Level 2 submenus
				el.querySelectorAll<HTMLElement>(
					".menu-item--2 > .sub-menu--active",
				).forEach((s) => {
					s.classList.remove("sub-menu--active");
					Object.assign(s.style, {
						display: "none",
						visibility: "",
						opacity: "",
					});
				});
				el.querySelectorAll<HTMLElement>(
					".menu-item--2 > a.active, .menu-item--2 > span.active",
				).forEach((a) => a.classList.remove("active"));
			});

			const item = items[i];
			if (!item) return null;
			const sub = item.querySelector<HTMLElement>(":scope > .sub-menu");
			if (!sub) return null;

			// Open the outer (Level 1) submenu
			item.querySelector<HTMLElement>(":scope > span")?.classList.add(
				"active",
			);
			item.querySelector<HTMLElement>(
				":scope > .submenu-toggle",
			)?.classList.add("submenu-toggle--active");
			Object.assign(sub.style, {
				display: "grid",
				visibility: "visible",
				opacity: "1",
			});
			sub.classList.add("sub-menu--active");

			// Check if there is a Level 2 submenu to open
			const hasInnerSubmenu = !!sub.querySelector(
				".menu-item--2.menu-item-has-children",
			);

			const hRect = h.getBoundingClientRect();
			const sRect = sub.getBoundingClientRect();
			const boundsWithoutInner = {
				x: hRect.left,
				y: hRect.top,
				width: hRect.width,
				height: Math.max(hRect.bottom, sRect.bottom) - hRect.top,
			};

			return { bounds: boundsWithoutInner, hasInnerSubmenu };
		}, i);

		if (result) {
			seen.add(text);

			// If the submenu has inner submenus, open it first before taking screenshot
			if (result.hasInnerSubmenu) {
				// Click on the first child menu item that has children
				await page.evaluate((i) => {
					const items = Array.from(
						document.querySelectorAll(
							"ul.menu > .menu-item-has-children",
						),
					);
					const sub =
						items[i]?.querySelector<HTMLElement>(
							":scope > .sub-menu",
						);
					if (!sub) return;

					// Find first direct child menu item that has children
					const childItems = Array.from(
						sub.querySelectorAll<HTMLElement>(
							":scope > .menu-item",
						),
					);
					const firstChildWithSubmenu = childItems.find((child) =>
						child.classList.contains("menu-item-has-children"),
					);

					if (!firstChildWithSubmenu) return;

					// Click on the link/span to trigger the submenu
					const clickTarget =
						firstChildWithSubmenu.querySelector<HTMLElement>(
							":scope > a, :scope > span",
						);
					if (clickTarget) {
						clickTarget.click();
					}
				}, i);

				// Wait for submenu animation/transition
				await page.waitForTimeout(400);

				// Capture the header with the inner submenu open
				const innerBounds = await page.evaluate(() => {
					const h = document.querySelector("header");
					if (!h) return null;

					const hRect = h.getBoundingClientRect();
					const allSubmenus = Array.from(
						document.querySelectorAll(".sub-menu--active"),
					);

					// Find the maximum bottom position of all active submenus
					let maxBottom = hRect.bottom;
					allSubmenus.forEach((sub) => {
						const subRect = sub.getBoundingClientRect();
						maxBottom = Math.max(maxBottom, subRect.bottom);
					});

					return {
						x: hRect.left,
						y: hRect.top,
						width: hRect.width,
						height: maxBottom - hRect.top,
					};
				});

				if (innerBounds) {
					await page.screenshot({
						path: filepath.replace(
							new RegExp(`-${bp}\\.png$`),
							`-submenu-${++idx}-${bp}.png`,
						),
						clip: innerBounds,
					});
				}
			} else {
				// No inner submenu, take regular screenshot
				await page.screenshot({
					path: filepath.replace(
						new RegExp(`-${bp}\\.png$`),
						`-submenu-${++idx}-${bp}.png`,
					),
					clip: result.bounds,
				});
			}
		}
	}

	return true;
}

/**
 * Capture footer
 */
async function captureFooter(page: Page, filepath: string): Promise<boolean> {
	const footer = page.locator("footer").first();
	if (!(await footer.count())) return false;

	await footer.scrollIntoViewIfNeeded();
	await page.waitForTimeout(200);
	await footer.screenshot({ path: filepath });
	return true;
}

/**
 * Capture 404 page
 */
async function capture404(page: Page, filepath: string): Promise<boolean> {
	await page.goto(`${getCachedWordPressUrl()}/404-not-found`, {
		waitUntil: "networkidle",
	});
	await page.waitForTimeout(300);

	const element = page.locator(".page-404").first();
	if (!(await element.count())) return false;

	await element.scrollIntoViewIfNeeded();
	await element.screenshot({ path: filepath });
	return true;
}

/**
 * Capture block with multiple instance support
 */
async function captureBlock(
	page: Page,
	blockName: string,
	filepath: string,
	reason?: string,
): Promise<boolean> {
	// Build selectors using exact class matching (not substrings)
	// e.g. section.cards should NOT match section.video-cards or section.hero--has-cards
	const selectors = [
		`section.${blockName}`,
		`.section.${blockName}`,
		`.${blockName}`,
	];

	// For content-layout changes, only capture blocks that contain .content-layout
	let selector = selectors.join(", ");
	if (reason === "content-layout") {
		selector = selectors.map((s) => `${s}:has(.content-layout)`).join(", ");
	}

	const elements = page.locator(selector);
	const count = await elements.count();

	if (count === 0) return false;

	// Hide overlapping headers before capture
	await page.evaluate(() => {
		const headers = document.querySelectorAll<HTMLElement>("header");
		headers.forEach((h) => {
			h.dataset.originalDisplay = h.style.display;
			h.style.display = "none";
		});
	});

	try {
		// Capture each instance
		for (let i = 0; i < count; i++) {
			const element = elements.nth(i);
			await element.scrollIntoViewIfNeeded();
			await page.waitForTimeout(200);

			// Expand first accordion if present
			await page.evaluate(
				({ selector, index }) => {
					const sections = document.querySelectorAll(selector);
					const section = sections[index];
					if (section) {
						const firstAccordion =
							section.querySelector(".accordion");
						if (firstAccordion) {
							firstAccordion.classList.add("accordion--active");
							const answer = firstAccordion.querySelector(
								".accordion__answer",
							) as HTMLElement;
							if (answer) {
								answer.style.display = "block";
							}
						}
					}
				},
				{ selector, index: i },
			);

			await page.waitForTimeout(600);

			// Generate filename
			const instancePath =
				count > 1
					? filepath.replace(".png", `-instance-${i + 1}.png`)
					: filepath;

			await element.screenshot({ path: instancePath });
		}

		return true;
	} finally {
		// Restore headers
		await page.evaluate(() => {
			const headers = document.querySelectorAll<HTMLElement>("header");
			headers.forEach((h) => {
				h.style.display = h.dataset.originalDisplay || "";
				delete h.dataset.originalDisplay;
			});
		});
	}
}

/**
 * Main screenshot capture function
 */
export async function captureScreenshot(
	blockName: string,
	url: string,
	breakpoint: Breakpoint,
	reason?: string,
	pageSlug?: string,
): Promise<boolean> {
	try {
		const page = await getPageForBreakpoint(breakpoint);

		// Navigate to URL
		const isSpecial404 = blockName === "404";
		if (!isSpecial404) {
			await page.goto(url, { waitUntil: "networkidle" });
			await page.waitForTimeout(300);
		}

		// Generate filepath
		mkdirSync(SCREENSHOTS_DIR, { recursive: true });
		// Normalise the block name so a crafted value cannot escape SCREENSHOTS_DIR.
		const safeBlock = blockName.replace(/[^a-z0-9-]/gi, "-");
		let filepath: string;
		if (pageSlug) {
			// Include page slug in filename when capturing specific page
			filepath = `${SCREENSHOTS_DIR}/block-${safeBlock}-${pageSlug}-${breakpoint.name}.png`;
		} else {
			filepath = `${SCREENSHOTS_DIR}/block-${safeBlock}-${breakpoint.name}.png`;
		}

		// Capture based on block type
		let success = false;

		if (blockName === "header") {
			success = await captureHeader(page, breakpoint, filepath);
		} else if (blockName === "footer") {
			success = await captureFooter(page, filepath);
		} else if (blockName === "404") {
			success = await capture404(page, filepath);
		} else {
			success = await captureBlock(page, blockName, filepath, reason);
		}

		return success;
	} catch (error) {
		console.error(
			`Error capturing ${blockName} at ${breakpoint.name}:`,
			(error as Error).message,
		);
		return false;
	}
}

/**
 * Find all block names on a given page URL
 */
export async function findBlocksOnPage(url: string): Promise<string[]> {
	let context: BrowserContext | null = null;

	try {
		const browser = await getBrowser();
		context = await browser.newContext({ ignoreHTTPSErrors: true });
		const page = await context.newPage();

		await page.goto(url, { waitUntil: "networkidle" });
		await page.waitForTimeout(300);

		const blockNames = await page.evaluate(() => {
			const sections = document.querySelectorAll("section");
			const blocks: string[] = [];

			sections.forEach((section) => {
				const classList = Array.from(section.classList);

				for (const className of classList) {
					const classNameStr = String(className);
					if (
						classNameStr.includes("--") ||
						classNameStr.startsWith("pad-") ||
						classNameStr.startsWith("text-") ||
						classNameStr === "section"
					) {
						continue;
					}

					if (classNameStr && !blocks.includes(classNameStr)) {
						blocks.push(classNameStr);
						break;
					}
				}
			});

			return blocks;
		});

		return blockNames;
	} catch (error) {
		console.error("Error finding blocks on page:", error);
		return [];
	} finally {
		if (context) await context.close();
	}
}

/**
 * Find blocks containing specific selector in browser
 */
export async function findBlocksWithSelector(
	selector: string,
): Promise<string[]> {
	let context: BrowserContext | null = null;

	try {
		const browser = await getBrowser();
		context = await browser.newContext({ ignoreHTTPSErrors: true });
		const page = await context.newPage();

		await page.goto(getCachedWordPressUrl(), { waitUntil: "networkidle" });
		await page.waitForTimeout(300);

		const blockNames = await page.evaluate((cssSelector) => {
			const elements = document.querySelectorAll(cssSelector);
			const blocks = new Set<string>();

			elements.forEach((el) => {
				let current = el.parentElement;
				while (current) {
					if (current.tagName === "SECTION") {
						const classList = Array.from(current.classList);

						for (const className of classList) {
							// Skip utility classes
							const classNameStr = String(className);
							if (
								classNameStr.includes("--") ||
								classNameStr.startsWith("pad-") ||
								classNameStr.startsWith("text-") ||
								classNameStr === "section"
							) {
								continue;
							}

							if (classNameStr) {
								blocks.add(classNameStr);
								break;
							}
						}
						break;
					}
					current = current.parentElement;
					if (current?.tagName === "BODY") break;
				}
			});

			return Array.from(blocks);
		}, selector);

		return blockNames;
	} catch (error) {
		console.error("Error finding blocks with selector:", error);
		return [];
	} finally {
		if (context) await context.close();
	}
}
