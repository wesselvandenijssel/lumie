import js from "@eslint/js";
import tseslint from "@typescript-eslint/eslint-plugin";
import tsparser from "@typescript-eslint/parser";

export default [
	js.configs.recommended,
	{
		files: ["**/*.ts", "**/*.tsx"],
		languageOptions: {
			parser: tsparser,
			parserOptions: {
				project: "./tsconfig.json",
				ecmaVersion: 2022,
				sourceType: "module",
			},
			globals: {
				// Browser globals
				window: "readonly",
				document: "readonly",
				console: "readonly",
				setTimeout: "readonly",
				setInterval: "readonly",
				clearTimeout: "readonly",
				clearInterval: "readonly",
				localStorage: "readonly",
				sessionStorage: "readonly",
				// WordPress global
				wp: "readonly",
			},
		},
		plugins: {
			"@typescript-eslint": tseslint,
		},
		rules: {
			// Inherit recommended TypeScript rules
			...tseslint.configs.recommended.rules,

			// Your custom rules from .eslintrc.js
			"function-paren-newline": "off",
			"implicit-arrow-linebreak": "off",
			"no-tabs": "off",
			"operator-linebreak": "off",
			"@typescript-eslint/comma-dangle": "off",
			"@typescript-eslint/indent": "off",
			"@typescript-eslint/quotes": "off",
			"wrap-iife": ["error", "inside"],
		},
	},
	{
		files: ["webpack.config.ts", "src/scripts/files/screenshots/**/*.ts"],
		languageOptions: {
			globals: {
				process: "readonly",
				require: "readonly",
				module: "readonly",
				__dirname: "readonly",
				__filename: "readonly",
				console: "readonly",
				fetch: "readonly",
			},
		},
		rules: {
			"@typescript-eslint/no-require-imports": "off",
		},
	},
	{
		ignores: [
			"node_modules/**",
			"dist/**",
			"vendor/**",
			"playwright.config.ts",
		],
	},
];
