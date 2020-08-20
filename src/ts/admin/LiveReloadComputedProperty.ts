interface LiveReloadComputedProperty {
	additionalSettings?: Array< string >;
	value: (
		value: LiveReloadValue,
		additionalValues: Array< LiveReloadValue >
	) => string;
}
