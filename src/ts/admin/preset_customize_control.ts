// eslint-disable-next-line @typescript-eslint/no-explicit-any
function isAssoc( value: any ): value is Record< string, any > {
	return (
		// eslint-disable-next-line @typescript-eslint/no-explicit-any
		!! value && ( value as Record< string, any > ).constructor === Object
	);
}

function applyPreset( control: wordpress__customize.Control ): void {
	const chosen = control.container
		.find( 'input[name=crdm_modern_preset]:checked' )
		.val() as string;
	if ( ! chosen ) {
		return;
	}

	const preset = crdmModernPresetCustomizeControlLocalize[ chosen ];
	$.each( preset, ( key, value ) => {
		if ( isAssoc( value ) ) {
			$.each( value, ( innerKey, innerValue ) => {
				const innerSetting = wp.customize( key + '[' + innerKey + ']' );
				if ( ! innerSetting ) {
					return;
				}
				innerSetting.set( innerValue );
			} );
		} else {
			const setting = wp.customize( key );
			if ( ! setting ) {
				return;
			}
			setting.set( value );
		}
	} );

	$( '.generatepress-font-variant select' ).trigger( 'change' );
}

void wp.customize.control( 'crdm_modern_preset', ( control ) => {
	control.container.find( 'input[name=crdm_modern_preset]' ).change( () => {
		control.container.find( '.button' ).prop( 'disabled', false );
	} );

	control.container.find( '.button' ).click( () => {
		applyPreset( control );
	} );
} );
