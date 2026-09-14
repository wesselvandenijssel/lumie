import * as webpack from "webpack";
import * as sass from "sass";
import { pathToFileURL, fileURLToPath } from "url";

const isDevWatch = process.env.NODE_ENV === "dev-watch";
const autoprefixer = require("autoprefixer");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const ESLintPlugin = require("eslint-webpack-plugin");
const glob = require("glob");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require("path");

const GLOB_SCHEME = "sass-glob:";

const toUseRule = (file: string): string => {
	const ref = pathToFileURL(file).href;
	return `@use "${ref}" as *;`;
};

const globImporter = {
	canonicalize(url: string, context: { containingUrl: URL | null }) {
		if (!url.includes("*")) return null; // not a glob — let the default resolvers handle it
		const fromDir = context.containingUrl
			? path.dirname(fileURLToPath(context.containingUrl))
			: __dirname;
		const pattern = path.resolve(fromDir, url).replace(/\\/g, "/");
		return new URL(GLOB_SCHEME + encodeURIComponent(pattern));
	},
	load(canonicalUrl: URL) {
		const pattern = decodeURIComponent(canonicalUrl.pathname);
		const contents = glob.sync(pattern).sort().map(toUseRule).join("\n");
		return { contents, syntax: "scss" };
	},
};
const StylelintWebpackPlugin = require("stylelint-webpack-plugin");
const WebpackBar = require("webpackbar");
const localConfig = isDevWatch
	? require("../../../../../local-site.json")
	: null;

const dev =
	process.env.NODE_ENV === "dev" || process.env.NODE_ENV === "dev-watch";

// Use glob to find all vendor files
const fancyboxFile = glob.sync("./src/scripts/files/fancybox/*.ts");

const entry = {
	main: [
		...glob.sync("./src/scripts/files/*.ts"),
		...glob.sync("./blocks/*/*.ts"),
		...glob.sync("./components/*/*.ts"),
		"./src/styles/main.scss",
	],
	admin: ["./src/styles/admin.scss"],
	"fancybox/fancybox": fancyboxFile,
};

const output = {
	path: path.resolve(__dirname, "dist"),
	filename: "[name].js",
};

const plugins = [
	new MiniCssExtractPlugin({
		filename: "[name].css",
	}),
	new WebpackBar(),
	new ESLintPlugin({
		files: "./**/*.ts",
		overrideConfigFile: "./eslint.config.mjs",
	}),
	new StylelintWebpackPlugin({
		files: "./**/*.scss",
	}),
];

if (isDevWatch && localConfig) {
	plugins.push(
		new BrowserSyncPlugin({
			host: localConfig.domain ?? "thema.local",
			port: 3000,
			proxy: `https://${localConfig.domain ?? "thema.local"}/`,
		}),
	);
}

const config: webpack.Configuration = {
	mode: dev ? "development" : "production",
	entry,
	output,
	devtool: dev ? "source-map" : false,
	resolve: {
		extensions: [".ts", ".tsx", ".js"],
		extensionAlias: {
			".js": [".js", ".ts"],
			".cjs": [".cjs", ".cts"],
			".mjs": [".mjs", ".mts"],
		},
		preferRelative: true,
	},
	plugins,
	module: {
		rules: [
			{
				test: /\.([cm]?ts|tsx)$/,
				loader: "ts-loader",
			},
			{
				test: /\.(scss|sass)$/i,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: "css-loader",
						options: { url: false, sourceMap: true },
					},
					{
						loader: "postcss-loader",
						options: {
							postcssOptions: {
								plugins: [autoprefixer()],
							},
						},
					},
					{
						loader: "sass-loader",
						options: {
							implementation: sass,
							api: "modern",
							warnRuleAsWarning: false,
							sassOptions: {
								loadPaths: [
									"node_modules",
									path.resolve(__dirname, "src/styles"),
								],
								importers: [globImporter],
								quietDeps: true,
							},
						},
					},
				],
			},
			{
				test: /\.css$/i,
				use: ["style-loader", "css-loader"],
			},
		],
	},
	optimization: { minimize: !dev },
	watch: dev,
};

export default config;
