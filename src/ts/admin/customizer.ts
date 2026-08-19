// Customizer - Colors.
liveReload('generate_settings[sidebar_widget_background_color]', [
	{
		properties: [{ name: 'background-color' }],
		selector: '.sidebar .widget_search .search-field',
	},
]);
liveReload(
	'generate_settings[sidebar_widget_text_color]',
	[
		{
			properties: [{ name: 'border-color' }, { name: 'color' }],
			selector: '.sidebar .widget_search .search-field',
		},
	],
	['generate_settings[content_text_color]', 'generate_settings[text_color]']
);
liveReload(
	'generate_settings[sidebar_widget_link_color]',
	[
		{
			properties: [{ name: 'border-color' }],
			selector: '.sidebar .widget_search .search-field:focus',
		},
	],
	['generate_settings[content_link_color]', 'generate_settings[link_color]']
);

liveReload('crdm_modern[sidebar_widget_separator_color]', [
	{
		properties: [{ name: 'border-left-color' }],
		selector: '.sidebar .inside-right-sidebar .widget',
	},
]);
liveReload('crdm_modern[excerpt_border_color]', [
	{
		properties: [{ name: 'border-color' }],
		selector: '.crdm-modern-excerpt',
	},
]);
liveReload('crdm_modern[excerpt_hover_background_color]', [
	{
		properties: [{ name: 'background-color' }],
		selector: '.crdm-modern-excerpt:hover',
	},
]);
liveReload('generate_settings[blog_post_title_hover_color]', [
	{
		properties: [{ name: 'color' }],
		selector: '.crdm-modern-excerpt:hover .entry-title a',
	},
]);
liveReload('generate_settings[entry_meta_link_color]', [
	{
		properties: [{ name: 'color' }],
		selector: '.entry-meta a:hover',
	},
]);
liveReload('generate_settings[entry_meta_link_color_hover]', [
	{
		properties: [{ name: 'color' }],
		selector: '.crdm-modern-excerpt:hover .entry-meta a',
	},
]);
liveReload('crdm_modern[excerpt_hover_text_color]', [
	{
		properties: [{ name: 'color' }],
		selector: '.crdm-modern-excerpt:hover',
	},
]);
liveReload('crdm_modern[read_more_color]', [
	{
		properties: [{ name: 'color' }],
		selector: 'a.read-more',
	},
]);
liveReload('crdm_modern[read_more_hover_color]', [
	{
		properties: [{ name: 'color' }],
		selector: 'a.read-more:hover',
	},
	{
		properties: [{ name: 'color' }],
		selector: '.crdm-modern-excerpt:hover a.read-more',
	},
]);

// Customizer - Layout.
liveReload('generate_settings[header_background_color]', [
	{
		properties: [{ name: 'background-color', postfix: '!important' }],
		selector: '.main-navigation',
	},
]);

liveReload('crdm_modern[primary_navigation_spacing]', [
	{
		properties: [
			{ name: 'margin-left', postfix: 'px' },
			{ name: 'margin-right', postfix: 'px' },
		],
		selector: '.main-navigation .inside-navigation',
	},
]);
liveReload('crdm_modern[primary_navigation_shadow]', [
	{
		properties: [{ name: 'box-shadow' }],
		selector: '.main-navigation .inside-navigation',
	},
]);
liveReload('generate_settings[navigation_background_color]', [
	{
		properties: [{ name: 'background-color' }],
		selector: '.main-navigation .inside-navigation',
	},
]);

liveReload('crdm_modern[sidebar_widget_separator_width]', [
	{
		properties: [{ name: 'border-left-width', postfix: 'px' }],
		selector: '.sidebar .inside-right-sidebar .widget',
	},
]);
liveReload('crdm_modern[sidebar_widget_separator_spacing]', [
	{
		properties: [{ name: 'padding-left', postfix: 'px' }],
		selector: '.sidebar .inside-right-sidebar .widget',
	},
]);

// Customizer - Site Identity.
liveReload('crdm_modern[header_image_height]', [
	{
		properties: [{ name: 'max-height', postfix: 'px' }],
		selector: '.crdm-modern-nav-image',
	},
]);
liveReload('crdm_modern[header_image_top]', [
	{
		properties: [{ name: 'top', postfix: 'px' }],
		selector: '.crdm-modern-nav-image',
	},
]);
liveReload('crdm_modern[header_image_right]', [
	{
		properties: [{ name: 'right', postfix: 'px' }],
		selector: '.crdm-modern-nav-image',
	},
]);

// Customizer - Header Image edit button
liveReload('crdm_modern[header_image_top]', [
	{
		properties: [{ name: 'top', postfix: 'px' }],
		selector: '.customize-partial-edit-shortcut-crdm_modern-header_image',
	},
]);
liveReload('crdm_modern[header_image_right]', [
	{
		properties: [
			{
				computed: {
					value: (value): string =>
						Math.max(parseInt(value, 10) - 30, 30).toString(),
				},
				name: 'right',
				postfix: 'px',
			},
		],
		selector: '.customize-partial-edit-shortcut-crdm_modern-header_image',
	},
]);

// Customizer - Typography.
liveReload('crdm_modern[blog_font_weight]', [
	{
		properties: [{ name: 'font-weight' }],
		selector: '.crdm-modern-excerpt',
	},
]);
liveReload('crdm_modern[blog_font_size]', [
	{
		properties: [{ name: 'font-size', postfix: 'px' }],
		selector: '.crdm-modern-excerpt',
	},
]);
liveReload('crdm_modern[blog_font_transform]', [
	{
		properties: [{ name: 'text-transform' }],
		selector: '.crdm-modern-excerpt',
	},
]);

// Frontend - Blog.
liveReload('generate_spacing_settings[separator]', [
	{
		properties: [{ name: 'padding-left', postfix: 'px' }],
		selector: '.generate-columns',
	},
	{
		properties: [
			{ name: 'margin-bottom', postfix: 'px' },
			{ name: 'margin-left', postfix: 'px', prefix: '-' },
		],
		selector: '.generate-columns-container',
	},
	{
		properties: [{ name: 'margin-bottom', postfix: 'px' }],
		selector: '.crdm-modern-excerpt',
	},
]);
liveReload('generate_spacing_settings[content_element_separator]', [
	{
		media: { maxWidth: 768 },
		properties: [
			{
				computed: {
					value: (value): string =>
						Math.max(2 * parseFloat(value) - 0.5, 0).toString(),
				},
				name: 'margin-top',
				postfix: 'em',
			},
		],
		selector:
			'.post-image-below-header.post-image-aligned-left .inside-article.crdm-modern-excerpt .post-image,' +
			'.post-image-below-header.post-image-aligned-right .inside-article.crdm-modern-excerpt .post-image',
	},
	{
		media: { maxWidth: 768 },
		properties: [
			{
				computed: {
					value: (value): string =>
						Math.max(parseFloat(value) - 0.5, 0).toString(),
				},
				name: 'margin-top',
				postfix: 'em',
			},
		],
		selector:
			'.post-image-below-header .crdm-modern-excerpt .entry-summary',
	},
	{
		media: { minWidth: 769 },
		properties: [
			{
				computed: {
					additionalSettings: [
						'generate_blog_settings[post_image_width]',
					],
					value: (value, additionalValues): string =>
						`calc(${additionalValues[0]}px + ${value}em)`,
				},
				name: 'margin-left',
			},
		],
		selector:
			'.post-image-aligned-left .crdm-modern-excerpt .entry-header,' +
			'.post-image-aligned-left .crdm-modern-excerpt .entry-summary',
	},
	{
		media: { minWidth: 769 },
		properties: [
			{
				computed: {
					additionalSettings: [
						'generate_blog_settings[post_image_width]',
					],
					value: (value, additionalValues): string =>
						`calc(${additionalValues[0]}px + ${value}em)`,
				},
				name: 'margin-right',
			},
		],
		selector:
			'.post-image-aligned-right .crdm-modern-excerpt .entry-header,' +
			'.post-image-aligned-right .crdm-modern-excerpt .entry-summary',
	},
	{
		media: { minWidth: 769 },
		properties: [
			{
				name: 'margin-left',
				postfix: 'em',
			},
			{
				name: 'margin-right',
				postfix: 'em',
			},
		],
		selector:
			'.post-image-aligned-left .generate-columns .crdm-modern-excerpt .entry-header,' +
			'.post-image-aligned-left .generate-columns .crdm-modern-excerpt .entry-summary,' +
			'.post-image-aligned-right .generate-columns .crdm-modern-excerpt .entry-header,' +
			'.post-image-aligned-right .generate-columns .crdm-modern-excerpt .entry-summary',
	},
	{
		media: { minWidth: 769 },
		properties: [{ name: 'top', postfix: 'em' }],
		selector: '.generate-columns .crdm-modern-excerpt .entry-header',
	},
	{
		media: { minWidth: 769 },
		properties: [
			{
				computed: {
					value: (value): string =>
						(parseFloat(value) + 0.5).toString(),
				},
				name: 'margin-top',
				postfix: 'em',
			},
		],
		selector: '.generate-columns .crdm-modern-excerpt .entry-summary',
	},
	{
		properties: [
			{
				computed: {
					value: (value): string =>
						Math.max(2 * parseFloat(value) - 0.5, 0).toString(),
				},
				name: 'margin-top',
				postfix: 'em',
			},
		],
		selector:
			'.post-image-below-header.post-image-aligned-center .crdm-modern-excerpt .post-image,' +
			'.post-image-below-header .generate-columns .inside-article.crdm-modern-excerpt .post-image',
	},
	{
		properties: [
			{
				computed: {
					value: (value): string =>
						Math.max(parseFloat(value) - 0.5, 0).toString(),
				},
				name: 'margin-top',
				postfix: 'em',
			},
		],
		selector:
			'.post-image-below-header.post-image-aligned-center .crdm-modern-excerpt .entry-summary,' +
			'.post-image-below-header .generate-columns .inside-article.crdm-modern-excerpt .entry-summary',
	},
	{
		properties: [
			{
				name: 'margin',
				postfix: 'em',
				prefix: '0 ',
			},
			{
				computed: {
					value: (value): string =>
						(parseFloat(value) - 0.5).toString(),
				},
				name: 'top',
				postfix: 'em',
			},
		],
		selector: '.crdm-modern-excerpt .entry-header',
	},
	{
		properties: [
			{
				computed: {
					value: (value): string =>
						`${value}em ${value}em ${value}em ${value}em`,
				},
				name: 'margin',
			},
		],
		selector: '.crdm-modern-excerpt .entry-summary',
	},
]);

// Frontend - Title widget.
liveReload('generate_settings[logo_width]', [
	{
		properties: [{ name: 'width', postfix: 'px' }],
		selector: '.crdm-modern-title-widget-image',
	},
	{
		properties: [
			{
				computed: {
					value: (value): string =>
						(parseInt(value, 10) + 20).toString(),
				},
				name: 'margin-left',
				postfix: 'px',
			},
		],
		selector: '.crdm-modern-title-widget-title',
	},
	{
		properties: [
			{
				computed: {
					value: (value): string =>
						(parseInt(value, 10) + 20).toString(),
				},
				name: 'margin-left',
				postfix: 'px',
			},
		],
		selector: '.crdm-modern-title-widget-tagline',
	},
]);
liveReload('generate_settings[site_title_font_size]', [
	{
		properties: [{ name: 'font-size', postfix: 'px' }],
		selector: '.crdm-modern-title-widget-title',
	},
]);
liveReload('generate_settings[site_title_font_weight]', [
	{
		properties: [{ name: 'font-weight' }],
		selector: '.crdm-modern-title-widget-title',
	},
]);
liveReload('generate_settings[site_tagline_font_size]', [
	{
		properties: [{ name: 'font-size', postfix: 'px' }],
		selector: '.crdm-modern-title-widget-tagline',
	},
]);
liveReload('generate_settings[site_tagline_font_weight]', [
	{
		properties: [{ name: 'font-weight' }],
		selector: '.crdm-modern-title-widget-tagline',
	},
]);
