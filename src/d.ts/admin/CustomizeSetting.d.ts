/* eslint-disable @typescript-eslint/consistent-type-imports -- The bundles share a global scope, so this type cannot be imported by a statement. */
declare type CustomizeSetting<T> =
	import('@wordpress/customize-browser/Setting').Setting<T>;
