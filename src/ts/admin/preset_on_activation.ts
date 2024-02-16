function handleResponse(response: string): void {
	let html = '';
	if (response === 'success') {
		html +=
			'<div class="notice notice-success is-dismissible">' +
			'<p>' +
			crdmModernPresetOnActivationLocalize.success;
	} else {
		html +=
			'<div class="notice notice-error is-dismissible">' +
			'<p>' +
			crdmModernPresetOnActivationLocalize.error;
	}
	html +=
		'</p>' +
		'<button type="button" class="notice-dismiss crdm-modern-notice-dismiss"><span class="screen-reader-text">' +
		crdmModernPresetOnActivationLocalize.dismiss +
		'</span></button>' +
		'</div>';
	$('.theme-browser').first().before(html);

	$('.crdm-modern-notice-dismiss').on('click', function () {
		$(this).parent().remove();
	});
	tb_remove();
}

function applyCallback(): void {
	const applyButton = $('#crdm-modern-preset-on-activation-apply');
	applyButton.attr('disabled', 'disabled');
	applyButton.off('click');
	const id = $('input[name=crdm-modern-preset-on-activation]:checked').val();
	void $.get(
		crdmModernPresetOnActivationLocalize.ajax_url,
		{
			// eslint-disable-next-line camelcase -- Fixed name of WordPress parameter
			_ajax_nonce: crdmModernPresetOnActivationLocalize.nonce,
			action: 'crdm_modern_apply_preset',
			id,
		},
		handleResponse
	);
}

function onActivation(): void {
	let html =
		'<div id="crdm-modern-preset-on-activation-modal">' +
		'<div id="crdm-modern-preset-on-activation-overflow">' +
		'<div>' +
		crdmModernPresetOnActivationLocalize.intro +
		'</div>' +
		'<br>';
	$.each(
		crdmModernPresetOnActivationLocalize.presets,
		(id: string, preset) => {
			html +=
				'<div class="crdm-modern-preset-on-activation-preset">' +
				'<label>' +
				'<input type="radio" name="crdm-modern-preset-on-activation" value="' +
				id +
				'">' +
				preset.name +
				'<img src="' +
				preset.image +
				'" alt="' +
				preset.name +
				'" class="crdm-modern-preset-on-activation-image">' +
				'</label>' +
				'</div>';
		}
	);
	html +=
		'</div>' +
		'<div id="crdm-modern-preset-on-activation-footer">' +
		'<a id="crdm-modern-preset-on-activation-apply" class="button button-primary" disabled>' +
		crdmModernPresetOnActivationLocalize.apply +
		'</a>' +
		'<a id="crdm-modern-preset-on-activation-skip" class="button">' +
		crdmModernPresetOnActivationLocalize.skip +
		'</a>' +
		'</div>' +
		'</div>';
	$('body').append(html);
	tb_show(
		crdmModernPresetOnActivationLocalize.title,
		'#TB_inline?inlineId=crdm-modern-preset-on-activation-modal'
	);

	$('input[name=crdm-modern-preset-on-activation]').on('change', () => {
		const applyButton = $('#crdm-modern-preset-on-activation-apply');
		applyButton.removeAttr('disabled');
		applyButton.on('click', applyCallback);
	});
	$('#crdm-modern-preset-on-activation-skip').on('click', () => {
		tb_remove();
	});
}

onActivation();
