/* exported liveReload */

/* eslint-disable no-bitwise -- Hashing function can do bitwise operations */
function hash(str: string): string {
	if (str.length === 0) {
		return '';
	}
	let ret = 0;
	for (let i = 0; i < str.length; i++) {
		ret = (ret << 5) - ret + str.charCodeAt(i);
		ret |= 0;
	}
	return ret.toString();
}
/* eslint-enable */

function setCSSInHead(
	setting: string,
	target: LiveReloadTarget,
	value: string
): void {
	const targetHash = hash(setting + target.selector);
	let mediaBegin = '';
	let mediaEnd = '';
	if (target.media) {
		mediaBegin = '@media (';
		if (target.media.minWidth !== undefined) {
			mediaBegin += 'min-width: ' + target.media.minWidth.toString();
		} else if (target.media.maxWidth !== undefined) {
			mediaBegin += 'max-width: ' + target.media.maxWidth.toString();
		}
		mediaBegin += 'px) {\n';
		mediaEnd = '}\n';
	}
	$('head style#' + targetHash).remove();
	$('head').append(
		'<style id="' +
			targetHash +
			'">\n' +
			mediaBegin +
			target.selector +
			' {\n' +
			$.map(target.properties, (property) => {
				let computedValue = value;
				if (property.computed) {
					let additionalValues: Array<string> = [];
					if (property.computed.additionalSettings) {
						additionalValues = $.map(
							property.computed.additionalSettings,
							(additionalSetting) =>
								String(wp.customize(additionalSetting).get())
						);
					}
					computedValue = property.computed.value(
						value,
						additionalValues
					);
				}
				return (
					'\t' +
					property.name +
					': ' +
					(property.prefix ?? '') +
					computedValue +
					(property.postfix ?? '') +
					';\n'
				);
			}).join('') +
			'}\n' +
			mediaEnd +
			'</style>'
	);
}

function customizeFallback(
	setting: string,
	targets: Array<LiveReloadTarget>,
	fallbacks: Array<string>,
	i: number
): void {
	void wp.customize(fallbacks[i], (value) => {
		value.bind((newValue: string) => {
			if (wp.customize(setting).get() !== undefined) {
				return;
			}
			for (let j = 0; j < i; j++) {
				if (wp.customize(fallbacks[j]).get() !== undefined) {
					return;
				}
			}
			$.each(targets, (_, target) => {
				setCSSInHead(setting, target, newValue);
			});
		});
	});
}

function liveReload(
	setting: string,
	targets: Array<LiveReloadTarget>,
	fallbacks?: Array<string>
): void {
	void wp.customize(setting, (value) => {
		value.bind((newValue: string) => {
			if (!newValue && fallbacks) {
				$.each(fallbacks, (_, fallback) => {
					const fallbackValue = String(wp.customize(fallback).get());
					if (fallbackValue) {
						newValue = fallbackValue;
						return false;
					}
					return true;
				});
			}
			$.each(targets, (_, target) => {
				setCSSInHead(setting, target, newValue);
			});
		});
	});
	if (fallbacks) {
		for (let i = 0; i < fallbacks.length; i++) {
			customizeFallback(setting, targets, fallbacks, i);
		}
	}
}
