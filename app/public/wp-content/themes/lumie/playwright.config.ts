import { defineConfig, devices } from "@playwright/test";

/**
 * Playwright Configuration for Screenshot Testing
 *
 * This config is separate from the main TypeScript build process
 * to avoid dependency conflicts in CI environments.
 */
export default defineConfig({
	testDir: "./src/scripts/files/screenshots/tests",
	/* Output directories */
	outputDir: "./playwright/test-results",

	/* Run tests in files in parallel */
	fullyParallel: true,
	/* Fail the build on CI if you accidentally left test.only in the source code. */
	forbidOnly: !process?.env?.CI,
	/* Retry on CI only */
	retries: process?.env?.CI ? 2 : 0,
	/* Opt out of parallel tests on CI. */
	workers: process?.env?.CI ? 1 : undefined,
	/* Reporter to use. See https://playwright.dev/docs/test-reporters */
	reporter: [["html", { outputFolder: "./playwright/reports" }]],
	/* Shared settings for all the projects below. See https://playwright.dev/docs/api/class-testoptions. */
	use: {
		/* Collect trace when retrying the failed test. See https://playwright.dev/docs/trace-viewer */
		trace: "on-first-retry",

		/* Screenshot settings for failed tests */
		screenshot: "only-on-failure",
		video: "retain-on-failure",
	},

	/* Configure projects for major browsers */
	projects: [
		{
			name: "chromium",
			use: { ...devices["Desktop Chrome"] },
		},

		{
			name: "firefox",
			use: { ...devices["Desktop Firefox"] },
		},

		{
			name: "webkit",
			use: { ...devices["Desktop Safari"] },
		},
	],
});
