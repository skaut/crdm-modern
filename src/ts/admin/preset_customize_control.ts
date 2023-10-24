function isAssoc(value: unknown): value is Record<string, unknown> {
	return (
		value !== undefined &&
		(value as Record<string, unknown>).constructor === Object
	);
}

function applyPreset(control: wordpress__customize.Control): void {
	const chosen = control.container
		.find('input[name=crdm_modern_preset]:checked')
		.val() as string;
	if (!chosen) {
		return;
	}

	const preset = crdmModernPresetCustomizeControlLocalize[chosen];
	$.each(preset, (key, value) => {
		if (isAssoc(value)) {
			$.each(value, (innerKey, innerValue) => {
				const innerSetting = wp.customize(key + '[' + innerKey + ']');
				// eslint-disable-next-line @typescript-eslint/no-unnecessary-condition, @typescript-eslint/strict-boolean-expressions
				if (!innerSetting) {
					return;
				}
				innerSetting.set(innerValue);
			});
		} else {
			const setting = wp.customize(key);
			// eslint-disable-next-line @typescript-eslint/no-unnecessary-condition, @typescript-eslint/strict-boolean-expressions
			if (!setting) {
				return;
			}
			setting.set(value);
		}
	});

	$('.generatepress-font-variant select').trigger('change');
}

void wp.customize.control('crdm_modern_preset', (control) => {
	control.container
		.find('input[name=crdm_modern_preset]')
		.on('change', () => {
			control.container.find('.button').prop('disabled', false);
		});

	control.container.find('.button').on('click', () => {
		applyPreset(control);
	});
});
