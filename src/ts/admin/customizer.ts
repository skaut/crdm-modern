interface LiveReloadProperty {
	name: string;
	postfix?: string;
}

function hash( str: string ): string {
	if ( str.length === 0 ) {
		return '';
	}
	let ret = 0;
	for ( let i = 0; i < str.length; i++ ) {
		ret = ( ( ret << 5 ) - ret ) + str.charCodeAt( i ); // eslint-disable-line no-bitwise
		ret |= 0; // eslint-disable-line no-bitwise
	}
	return ret.toString();
}

function liveReload( setting: string, selector: string, properties: Array<LiveReloadProperty> ): void {
	wp.customize( setting, function( value: any ) {
		value.bind( function( newValue: any ) {
			const el = $( selector );
			if ( el.length > 0 ) {
				$.each( properties, function( _, property ) {
					el.css( property.name, newValue + ( property.postfix ?? '' ) );
				} );
			} else {
				$( 'head style#' + hash( setting + selector ) ).remove();
				$( 'head' ).append( '<style id="' + hash( setting + selector ) + '">\n' + selector + ' {\n' + $.map( properties, function( property ) {
					return '\t' + property.name + ': ' + newValue + ( property.postfix ?? '' ) + ';\n';
				} ).join( '' ) + '}\n' + '</style>' );
			}
		} );
	} );
}

// Site Identity.
liveReload( 'crdm_modern[header_image_height]', '.crdm_modern_nav_image', [ { name: 'max-height', postfix: 'px' } ] );
liveReload( 'crdm_modern[header_image_top]', '.crdm_modern_nav_image', [ { name: 'top', postfix: 'px' } ] );
liveReload( 'crdm_modern[header_image_right]', '.crdm_modern_nav_image', [ { name: 'right', postfix: 'px' } ] );

// Colors.
liveReload( 'generate_settings[sidebar_widget_background_color]', '.sidebar .widget_search .search-field', [ { name: 'background-color' } ] );
liveReload( 'generate_settings[sidebar_widget_text_color]', '.sidebar .widget_search .search-field', [ { name: 'border-color' } ] );
liveReload( 'generate_settings[sidebar_widget_text_color]', '.sidebar .widget_search .search-field', [ { name: 'color' } ] );
liveReload( 'generate_settings[sidebar_widget_link_color]', '.sidebar .widget_search .search-field:focus', [ { name: 'border-color' } ] );

liveReload( 'crdm_modern[sidebar_widget_separator_color]', '.sidebar .inside-right-sidebar .widget', [ { name: 'border-left-color' } ] );
liveReload( 'crdm_modern[excerpt_border_color]', '.crdm_modern_excerpt', [ { name: 'border-color' } ] );
liveReload( 'crdm_modern[excerpt_hover_background_color]', '.crdm_modern_excerpt:hover', [ { name: 'background-color' } ] );
liveReload( 'generate_settings[blog_post_title_hover_color]', '.crdm_modern_excerpt:hover .entry-title a', [ { name: 'color' } ] );
liveReload( 'generate_settings[entry_meta_link_color_hover]', '.crdm_modern_excerpt:hover .entry-meta a', [ { name: 'color' } ] );
liveReload( 'crdm_modern[excerpt_hover_text_color]', '.crdm_modern_excerpt:hover', [ { name: 'color' } ] );
liveReload( 'crdm_modern[read_more_color]', 'a.read-more', [ { name: 'color' } ] );
liveReload( 'crdm_modern[read_more_hover_color]', 'a.read-more:hover', [ { name: 'color' } ] );
liveReload( 'crdm_modern[read_more_hover_color]', '.crdm_modern_excerpt:hover a.read-more', [ { name: 'color' } ] );

// Layout.
liveReload( 'generate_settings[header_background_color]', '.main-navigation', [ { name: 'background-color' } ] );

liveReload( 'crdm_modern[primary_navigation_spacing]', '.main-navigation .inside-navigation', [ { name: 'margin-left', postfix: 'px' }, { name: 'margin-right', postfix: 'px' } ] );
liveReload( 'crdm_modern[primary_navigation_shadow]', '.main-navigation .inside-navigation', [ { name: 'box-shadow' } ] );
liveReload( 'generate_settings[navigation_background_color]', '.main-navigation .inside-navigation', [ { name: 'background-color' } ] );

liveReload( 'crdm_modern[sidebar_widget_separator_width]', '.sidebar .inside-right-sidebar .widget', [ { name: 'border-left-width', postfix: 'px' } ] );
liveReload( 'crdm_modern[sidebar_widget_separator_spacing]', '.sidebar .inside-right-sidebar .widget', [ { name: 'padding-left', postfix: 'px' } ] );

// Title widget.
liveReload( 'generate_settings[logo_width]', '.widget_crdm_modern_title_widget img', [ { name: 'width' } ] );
liveReload( 'generate_settings[site_title_font_size]', '.crdm_modern_title_widget_title', [ { name: 'font-size', postfix: 'px' } ] );
liveReload( 'generate_settings[site_title_font_weight]', '.crdm_modern_title_widget_title', [ { name: 'font-weight' } ] );
liveReload( 'generate_settings[site_tagline_font_size]', '.crdm_modern_title_widget_tagline', [ { name: 'font-size', postfix: 'px' } ] );
liveReload( 'generate_settings[site_tagline_font_weight]', '.crdm_modern_title_widget_tagline', [ { name: 'font-weight' } ] );
