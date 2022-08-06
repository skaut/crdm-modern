/* exported LiveReloadComputedProperty */

interface LiveReloadComputedProperty {
	additionalSettings?: Array<string>;
	value(value: string, additionalValues: Array<string>): string;
}
