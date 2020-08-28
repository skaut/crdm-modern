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
	$.each( preset, function ( key, value ) {
		if ( isAssoc( value ) ) {
			$.each( value, function ( innerKey, innerValue ) {
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

void wp.customize.control( 'crdm_modern_preset', function ( control ) {
	control.container
		.find( 'input[name=crdm_modern_preset]' )
		.change( function () {
			control.container.find( '.button' ).prop( 'disabled', false );
		} );

	control.container.find( '.button' ).click( function () {
		applyPreset( control );
	} );
} );
