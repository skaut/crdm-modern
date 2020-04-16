interface LiveReloadComputedProperty {
	additionalSettings?: Array<string>;
	value: (value: any, additionalValues: Array<any>) => string;
}
