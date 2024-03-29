function isArchive(): boolean {
	const articles = $('.site-main article .inside-article');
	return (
		articles.length !== 1 || articles.find('.entry-summary').length !== 0
	);
}

function addExcerptBorders(): void {
	if (!isArchive()) {
		return;
	}
	const articles = $('.site-main article .inside-article');
	articles.addClass('crdm-modern-excerpt');
}

function addExcerptClickability(): void {
	if (!isArchive()) {
		return;
	}
	const articles = $('.site-main article .inside-article');
	articles.each((_, article) => {
		const href = $(article).find('.entry-title a').attr('href') ?? '';
		$(article).on('click', () => {
			window.location.href = href;
		});
	});
}

addExcerptBorders();
addExcerptClickability();
