function addExcerptBorders(): void {
	const articles = $( '.site-main article .inside-article' );
	if ( articles.length === 1 && articles.find( '.entry-summary' ).length === 0 ) {
		return;
	}
	articles.addClass( 'crdm_modern_excerpt' );
	articles.slice( 0, 2 ).addClass( 'crdm_modern_excerpt_vertical' );
}

addExcerptBorders();
