interface LiveReloadProperty {
	name: string;
	postfix?: string;
}

function liveReload( setting: string, selector: string, properties: Array<LiveReloadProperty> ): void {
	wp.customize( setting, function( value: any ) {
		value.bind( function( newValue: any ) {
			const el = $( selector );
			$.each( properties, function( _, property ) {
				el.css( property.name, newValue + ( property.postfix ?? '' ) );
			} );
		} );
	} );
}

liveReload( 'generate_settings[header_background_color]', '.main-navigation', [ { name: 'background-color' } ] );

liveReload( 'crdm_modern[primary_navigation_spacing]', '.main-navigation .inside-navigation', [ { name: 'margin-left', postfix: 'px' }, { name: 'margin-right', postfix: 'px' } ] );
liveReload( 'crdm_modern[primary_navigation_shadow]', '.main-navigation .inside-navigation', [ { name: 'box-shadow' } ] );
liveReload( 'generate_settings[navigation_background_color]', '.main-navigation .inside-navigation', [ { name: 'background-color' } ] );
