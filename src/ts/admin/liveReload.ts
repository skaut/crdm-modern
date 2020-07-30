/* exported liveReload */

function hash( str: string ): string {
	if ( str.length === 0 ) {
		return '';
	}
	let ret = 0;
	for ( let i = 0; i < str.length; i++ ) {
		ret = ( ret << 5 ) - ret + str.charCodeAt( i ); // eslint-disable-line no-bitwise
		ret |= 0; // eslint-disable-line no-bitwise
	}
	return ret.toString();
}

function setCSSInHead(
	setting: string,
	target: LiveReloadTarget,
	value: any
): void {
	const targetHash = hash( setting + target.selector );
	let mediaBegin = '';
	let mediaEnd = '';
	if ( target.media ) {
		mediaBegin = '@media (';
		if ( target.media.minWidth ) {
			mediaBegin += 'min-width: ' + target.media.minWidth.toString();
		} else if ( target.media.maxWidth ) {
			mediaBegin += 'max-width: ' + target.media.maxWidth.toString();
		}
		mediaBegin += 'px) {\n';
		mediaEnd = '}\n';
	}
	$( 'head style#' + targetHash ).remove();
	$( 'head' ).append(
		'<style id="' +
			targetHash +
			'">\n' +
			mediaBegin +
			target.selector +
			' {\n' +
			$.map( target.properties, function ( property ) {
				let computedValue = value;
				if ( property.computed ) {
					let additionalValues = [];
					if ( property.computed.additionalSettings ) {
						additionalValues = $.map(
							property.computed.additionalSettings,
							( additionalSetting ) =>
								wp.customize( additionalSetting ).get()
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
					( property.prefix ?? '' ) +
					computedValue +
					( property.postfix ?? '' ) +
					';\n'
				);
			} ).join( '' ) +
			'}\n' +
			mediaEnd +
			'</style>'
	);
}

function liveReload(
	setting: string,
	targets: Array< LiveReloadTarget >,
	fallbacks?: Array< string >
): void {
	wp.customize( setting, function ( value: any ) {
		value.bind( function ( newValue: any ) {
			if ( ! newValue && fallbacks ) {
				$.each( fallbacks, function ( _, fallback ) {
					const fallbackValue = wp.customize( fallback ).get();
					if ( fallbackValue ) {
						newValue = fallbackValue;
						return false;
					}
					return true;
				} );
			}
			$.each( targets, function ( _, target ) {
				setCSSInHead( setting, target, newValue );
			} );
		} );
	} );
	if ( fallbacks ) {
		for ( let i = 0; i < fallbacks.length; i++ ) {
			wp.customize( fallbacks[ i ], function ( value: any ) {
				value.bind( function ( newValue: any ) {
					if ( wp.customize( setting ).get() ) {
						return;
					}
					for ( let j = 0; j < i; j++ ) {
						if ( wp.customize( fallbacks[ j ] ).get() ) {
							return;
						}
					}
					$.each( targets, function ( _, target ) {
						setCSSInHead( setting, target, newValue );
					} );
				} );
			} );
		}
	}
}
