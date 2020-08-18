declare interface CrdmModernPresetOnActivationLocalize {
	ajax_url: string; // eslint-disable-line camelcase
	apply: string;
	dismiss: string;
	error: string;
	intro: string;
	nonce: string;
	skip: string;
	success: string;
	title: string;
	presets: Record< string, PresetOnActivationPreset >;
}
