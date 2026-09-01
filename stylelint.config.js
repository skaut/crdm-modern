/** @type {import('stylelint').Config} */
export default {
	extends: [
		'stylelint-config-standard',
		'@wordpress/stylelint-config/stylistic',
	],
	rules: {
		// The `inset` shorthand is above our floor (Chrome 87 / Safari 14.1).
		'declaration-block-no-redundant-longhand-properties': [
			true,
			{
				ignoreShorthands: ['inset'],
			},
		],
		'media-feature-range-notation': 'prefix',
	},
};
