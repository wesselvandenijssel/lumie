module.exports = {
	plugins: ["stylelint-scss", "stylelint-order"],
	extends: ["stylelint-config-standard-scss"],
	rules: {
		"max-nesting-depth": 8,
		"scss/at-extend-no-missing-placeholder": null,
		"no-descending-specificity": null,
		"order/order": [
			[
				{ type: "at-rule", name: "use" },
				{ type: "at-rule", name: "forward" },
				"dollar-variables",
				"custom-properties",
				"at-variables",
				"declarations",
				"at-rules",
				"rules",
			],
			{
				unspecified: "bottom",
			},
		],
		"at-rule-no-unknown": [
			true,
			{
				ignoreAtRules: [
					"else",
					"extend",
					"function",
					"if",
					"include",
					"mixin",
					"return",
					"use",
					"warn",
				],
			},
		],
		"scss/dollar-variable-empty-line-before": null,
	},
};
