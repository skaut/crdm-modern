wp.customize( 'crdm_modern[primary_navigation_spacing]', function( value: any ) {
	value.bind( function( newVal: any ) {
		const el = $( '.main-navigation .inside-navigation' );
		el.css( 'margin-left', newVal + 'px' );
		el.css( 'margin-right', newVal + 'px' );
	} );
} );
