function flatten( input: object ): Record<string, string> {
	function flattenInner( inputInner: object ): Record<string, string> {
		const retInner: Record<string, string> = {};
		$.each( inputInner, function( key, value ) {
			if ( typeof ( value ) === 'object' ) {
				$.each( flattenInner( value ), function( keyInner, valueInner ) {
					retInner[ '[' + key + ']' + keyInner ] = valueInner;
				} );
			} else {
				retInner[ '[' + key + ']' ] = value;
			}
		} );
		return retInner;
	}

	const ret: Record<string, string> = {};
	$.each( input, function( key, value ) {
		$.each( flattenInner( value ), function( keyInner, valueInner ) {
			ret[ key + keyInner ] = valueInner;
		} );
	} );
	return ret;
}

function applyPreset( control: any ): void {
	const chosen = control.container.find( 'input[name=crdm_modern_preset]:checked' ).val();
	if ( ! chosen ) {
		return;
	}

	const preset = flatten( crdmModernPresetCustomizeControlLocalize[ chosen ] );
	$.each( preset, function( key, value ) {
		const setting = wp.customize( key );
		if ( ! setting ) {
			return;
		}
		setting.set( value );
	} );
}

wp.customize.control(
	'crdm_modern_preset',
	function( control: any ) {
		control.container.find( 'input[name=crdm_modern_preset]' ).change( function() {
			control.container.find( '.button' ).prop( 'disabled', false );
		} );

		control.container.find( '.button' ).click( function() {
			applyPreset( control );
		} );
	}
);
