/**
 * Change Detection & Dependency Resolution
 *
 * Detects which blocks need screenshots based on git changes,
 * component dependencies, and style dependencies.
 */

import { execSync } from "child_process";
import { readdirSync, readFileSync, existsSync } from "fs";
import { join } from "path";
import type { BlockInfo } from "./config";
import { TRIGGER_PATTERNS } from "./config";

/**
 * Get changed files from git (working tree + branch diff)
 */
export function getChangedFiles(): string[] {
	try {
		// Get uncommitted changes (working tree)
		const workingTreeOutput = execSync(
			"git status --porcelain --untracked-files=all",
			{
				encoding: "utf8",
			},
		).trim();

		// Get files changed in branch compared to base
		// In GitHub Actions: GITHUB_BASE_REF = "development"
		// Locally: use "origin/development"
		const baseBranch = process.env.GITHUB_BASE_REF || "origin/development";

		let branchDiffOutput = "";
		try {
			const diffCommand = `git diff --name-only ${baseBranch}...HEAD`;
			branchDiffOutput = execSync(diffCommand, {
				encoding: "utf8",
			}).trim();
		} catch (error) {
			// Branch not available, skip
		}

		// Combine both sources
		const workingTreeFiles = workingTreeOutput
			? workingTreeOutput
					.split("\n")
					.map((line) => line.trim().substring(2).trim())
			: [];

		const branchDiffFiles = branchDiffOutput
			? branchDiffOutput.split("\n").map((line) => line.trim())
			: [];

		// Merge and deduplicate
		const allFiles = Array.from(
			new Set([...workingTreeFiles, ...branchDiffFiles]),
		);

		return allFiles.filter((file) => file.trim() !== "");
	} catch (error) {
		console.warn("Could not get git changes");
		return [];
	}
}

/**
 * Extract block name from file path
 */
function getBlockFromPath(filePath: string): string | null {
	const normalized = filePath.replace(/\/+/g, "/");
	if (normalized.startsWith("blocks/")) {
		const parts = normalized.split("/");
		if (parts.length >= 2) return parts[1];
	}
	return null;
}

/**
 * Extract component name from file path
 */
function getComponentFromPath(filePath: string): string | null {
	const normalized = filePath.replace(/\/+/g, "/");
	if (normalized.startsWith("components/")) {
		const parts = normalized.split("/");
		if (parts.length >= 2) return parts[1];
	}
	return null;
}

/**
 * Find blocks that use a specific component
 */
function findBlocksUsingComponent(componentName: string): string[] {
	const blocksDir = "blocks";
	if (!existsSync(blocksDir)) return [];

	const blockDirs = readdirSync(blocksDir, { withFileTypes: true })
		.filter((d) => d.isDirectory())
		.map((d) => d.name);

	const result: string[] = [];

	for (const blockName of blockDirs) {
		const viewPath = join(blocksDir, blockName, "view.php");
		if (!existsSync(viewPath)) continue;

		try {
			const content = readFileSync(viewPath, "utf8");
			const escaped = componentName.replace(
				/[.*+?^${}()|[\]\\]/g,
				"\\$&",
			);
			const pattern = new RegExp(`component\\(['"]${escaped}['"]`, "g");

			if (pattern.test(content)) {
				result.push(blockName);
			}
		} catch {
			// Skip file
		}
	}

	return result;
}

/**
 * Check if file matches any pattern
 */
function matchesAnyPattern(filePath: string, patterns: string[]): boolean {
	return patterns.some((pattern) => filePath.includes(pattern));
}

/**
 * Get blocks that need screenshots based on changed files
 */
export function detectChangedBlocks(): BlockInfo[] {
	const changedFiles = getChangedFiles();
	const blocks = new Map<string, BlockInfo["reason"]>();

	for (const file of changedFiles) {
		// Direct block changes
		const isBlockFile =
			(file.includes("view.php") || file.includes(".scss")) &&
			file.includes("blocks");

		if (isBlockFile) {
			const blockName = getBlockFromPath(file);
			if (blockName) {
				blocks.set(blockName, "direct");
				continue;
			}
		}

		// Header changes
		if (matchesAnyPattern(file, TRIGGER_PATTERNS.header)) {
			blocks.set("header", "direct");
		}

		// Footer changes
		if (matchesAnyPattern(file, TRIGGER_PATTERNS.footer)) {
			blocks.set("footer", "direct");
		}

		// 404 changes
		if (matchesAnyPattern(file, TRIGGER_PATTERNS["404"])) {
			blocks.set("404", "direct");
		}

		// Component changes
		const componentName = getComponentFromPath(file);
		if (componentName) {
			const dependentBlocks = findBlocksUsingComponent(componentName);
			dependentBlocks.forEach((block) => blocks.set(block, "component"));
		}

		// Button style changes
		if (matchesAnyPattern(file, TRIGGER_PATTERNS.buttons)) {
			blocks.set("__DETECT_BUTTONS__", "buttons");
		}

		// Content-layout changes
		if (matchesAnyPattern(file, TRIGGER_PATTERNS.contentLayout)) {
			blocks.set("__DETECT_CONTENT_LAYOUT__", "content-layout");
		}

		// Titles changes
		if (matchesAnyPattern(file, TRIGGER_PATTERNS.titles)) {
			blocks.set("__DETECT_TITLES__", "titles");
		}

		// Color palette changes
		if (matchesAnyPattern(file, TRIGGER_PATTERNS.colorPalette)) {
			blocks.set("background", "color-palette");
		}
	}

	return Array.from(blocks.entries()).map(([name, reason]) => ({
		name,
		reason,
	}));
}
