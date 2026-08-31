/** @type {import('stylelint').Config} */
export default {
	extends: [
		'stylelint-config-standard',
		'@wordpress/stylelint-config/stylistic',
	],
	plugins: ['stylelint-no-unsupported-browser-features'],
	rules: {
		// The `inset` shorthand is above our floor (Chrome 87 / Safari 14.1).
		'declaration-block-no-redundant-longhand-properties': [
			true,
			{
				ignoreShorthands: ['inset'],
			},
		],
		'media-feature-range-notation': 'prefix',
		'plugin/no-unsupported-browser-features': [
			true,
			{
				// Caniuse's partial flag covers `overflow: clip`, not `overflow-y`.
				ignore: ['css-sel2', 'css-overflow'],
			},
		],
	},
};
