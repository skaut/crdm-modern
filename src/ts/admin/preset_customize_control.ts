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

console.log( flatten( crdmModernPresetCustomizeControlLocalize ) );
