// Customizer - Colors.
liveReload("generate_settings[sidebar_widget_background_color]", [
  {
    selector: ".sidebar .widget_search .search-field",
    properties: [{ name: "background-color" }]
  }
]);
liveReload(
  "generate_settings[sidebar_widget_text_color]",
  [
    {
      selector: ".sidebar .widget_search .search-field",
      properties: [{ name: "border-color" }, { name: "color" }]
    }
  ],
  ["generate_settings[content_text_color]", "generate_settings[text_color]"]
);
liveReload(
  "generate_settings[sidebar_widget_link_color]",
  [
    {
      selector: ".sidebar .widget_search .search-field:focus",
      properties: [{ name: "border-color" }]
    }
  ],
  ["generate_settings[content_link_color]", "generate_settings[link_color]"]
);

liveReload("crdm_modern[sidebar_widget_separator_color]", [
  {
    selector: ".sidebar .inside-right-sidebar .widget",
    properties: [{ name: "border-left-color" }]
  }
]);
liveReload("crdm_modern[excerpt_border_color]", [
  {
    selector: ".crdm-modern-excerpt",
    properties: [{ name: "border-color" }]
  }
]);
liveReload("crdm_modern[excerpt_hover_background_color]", [
  {
    selector: ".crdm-modern-excerpt:hover",
    properties: [{ name: "background-color" }]
  }
]);
liveReload("generate_settings[blog_post_title_hover_color]", [
  {
    selector: ".crdm-modern-excerpt:hover .entry-title a",
    properties: [{ name: "color" }]
  }
]);
liveReload("generate_settings[entry_meta_link_color]", [
  {
    selector: ".entry-meta a:hover",
    properties: [{ name: "color" }]
  }
]);
liveReload("generate_settings[entry_meta_link_color_hover]", [
  {
    selector: ".crdm-modern-excerpt:hover .entry-meta a",
    properties: [{ name: "color" }]
  }
]);
liveReload("crdm_modern[excerpt_hover_text_color]", [
  {
    selector: ".crdm-modern-excerpt:hover",
    properties: [{ name: "color" }]
  }
]);
liveReload("crdm_modern[read_more_color]", [
  {
    selector: "a.read-more",
    properties: [{ name: "color" }]
  }
]);
liveReload("crdm_modern[read_more_hover_color]", [
  {
    selector: "a.read-more:hover",
    properties: [{ name: "color" }]
  },
  {
    selector: ".crdm-modern-excerpt:hover a.read-more",
    properties: [{ name: "color" }]
  }
]);

// Customizer - Layout.
liveReload("generate_settings[header_background_color]", [
  {
    selector: ".main-navigation",
    properties: [{ name: "background-color", postfix: "!important" }]
  }
]);

liveReload("crdm_modern[primary_navigation_spacing]", [
  {
    selector: ".main-navigation .inside-navigation",
    properties: [
      { name: "margin-left", postfix: "px" },
      { name: "margin-right", postfix: "px" }
    ]
  }
]);
liveReload("crdm_modern[primary_navigation_shadow]", [
  {
    selector: ".main-navigation .inside-navigation",
    properties: [{ name: "box-shadow" }]
  }
]);
liveReload("generate_settings[navigation_background_color]", [
  {
    selector: ".main-navigation .inside-navigation",
    properties: [{ name: "background-color" }]
  }
]);

liveReload("crdm_modern[sidebar_widget_separator_width]", [
  {
    selector: ".sidebar .inside-right-sidebar .widget",
    properties: [{ name: "border-left-width", postfix: "px" }]
  }
]);
liveReload("crdm_modern[sidebar_widget_separator_spacing]", [
  {
    selector: ".sidebar .inside-right-sidebar .widget",
    properties: [{ name: "padding-left", postfix: "px" }]
  }
]);

// Customizer - Site Identity.
liveReload("crdm_modern[header_image_height]", [
  {
    selector: ".crdm-modern-nav-image",
    properties: [{ name: "max-height", postfix: "px" }]
  }
]);
liveReload("crdm_modern[header_image_top]", [
  {
    selector: ".crdm-modern-nav-image",
    properties: [{ name: "top", postfix: "px" }]
  }
]);
liveReload("crdm_modern[header_image_right]", [
  {
    selector: ".crdm-modern-nav-image",
    properties: [{ name: "right", postfix: "px" }]
  }
]);

// Customizer - Header Image edit button
liveReload("crdm_modern[header_image_top]", [
  {
    selector: ".customize-partial-edit-shortcut-crdm_modern-header_image",
    properties: [{ name: "top", postfix: "px" }]
  }
]);
liveReload("crdm_modern[header_image_right]", [
  {
    selector: ".customize-partial-edit-shortcut-crdm_modern-header_image",
    properties: [
      {
        name: "right",
        postfix: "px",
        computed: {
          value: (value): string =>
            Math.max(parseInt(value) - 30, 30).toString()
        }
      }
    ]
  }
]);

// Customizer - Typography.
liveReload("crdm_modern[blog_font_weight]", [
  {
    selector: ".crdm-modern-excerpt",
    properties: [{ name: "font-weight" }]
  }
]);
liveReload("crdm_modern[blog_font_size]", [
  {
    selector: ".crdm-modern-excerpt",
    properties: [{ name: "font-size", postfix: "px" }]
  }
]);
liveReload("crdm_modern[blog_font_transform]", [
  {
    selector: ".crdm-modern-excerpt",
    properties: [{ name: "text-transform" }]
  }
]);

// Frontend - Blog.
liveReload("generate_spacing_settings[separator]", [
  {
    selector: ".generate-columns",
    properties: [{ name: "padding-left", postfix: "px" }]
  },
  {
    selector: ".generate-columns-container",
    properties: [{ name: "margin-left", prefix: "-", postfix: "px" }]
  },
  {
    selector: ".crdm-modern-excerpt",
    properties: [{ name: "margin-bottom", postfix: "px" }]
  }
]);
liveReload("generate_spacing_settings[content_element_separator]", [
  {
    selector:
      ".post-image-below-header.post-image-aligned-left .inside-article.crdm-modern-excerpt .post-image," +
      ".post-image-below-header.post-image-aligned-right .inside-article.crdm-modern-excerpt .post-image",
    media: { maxWidth: 768 },
    properties: [
      {
        name: "margin-top",
        postfix: "em",
        computed: {
          value: (value): string =>
            Math.max(2 * parseFloat(value) - 0.5, 0).toString()
        }
      }
    ]
  },
  {
    selector: ".post-image-below-header .crdm-modern-excerpt .entry-summary",
    media: { maxWidth: 768 },
    properties: [
      {
        name: "margin-top",
        postfix: "em",
        computed: {
          value: (value): string =>
            Math.max(parseFloat(value) - 0.5, 0).toString()
        }
      }
    ]
  },
  {
    selector:
      ".post-image-aligned-left .crdm-modern-excerpt .entry-header," +
      ".post-image-aligned-left .crdm-modern-excerpt .entry-summary",
    media: { minWidth: 769 },
    properties: [
      {
        name: "margin-left",
        computed: {
          additionalSettings: ["generate_blog_settings[post_image_width]"],
          value: (value, additionalValues): string =>
            "calc(" + additionalValues[0] + "px + " + value + "em)"
        }
      }
    ]
  },
  {
    selector:
      ".post-image-aligned-right .crdm-modern-excerpt .entry-header," +
      ".post-image-aligned-right .crdm-modern-excerpt .entry-summary",
    media: { minWidth: 769 },
    properties: [
      {
        name: "margin-right",
        computed: {
          additionalSettings: ["generate_blog_settings[post_image_width]"],
          value: (value, additionalValues): string =>
            "calc(" + additionalValues[0] + "px + " + value + "em)"
        }
      }
    ]
  },
  {
    selector:
      ".post-image-aligned-left .generate-columns .crdm-modern-excerpt .entry-header," +
      ".post-image-aligned-left .generate-columns .crdm-modern-excerpt .entry-summary," +
      ".post-image-aligned-right .generate-columns .crdm-modern-excerpt .entry-header," +
      ".post-image-aligned-right .generate-columns .crdm-modern-excerpt .entry-summary",
    media: { minWidth: 769 },
    properties: [
      {
        name: "margin-left",
        postfix: "em"
      },
      {
        name: "margin-right",
        postfix: "em"
      }
    ]
  },
  {
    selector: ".generate-columns .crdm-modern-excerpt .entry-header",
    media: { minWidth: 769 },
    properties: [{ name: "top", postfix: "em" }]
  },
  {
    selector: ".generate-columns .crdm-modern-excerpt .entry-summary",
    media: { minWidth: 769 },
    properties: [
      {
        name: "margin-top",
        postfix: "em",
        computed: {
          value: (value): string => (parseFloat(value) + 0.5).toString()
        }
      }
    ]
  },
  {
    selector:
      ".post-image-below-header.post-image-aligned-center .crdm-modern-excerpt .post-image," +
      ".post-image-below-header .generate-columns .inside-article.crdm-modern-excerpt .post-image",
    properties: [
      {
        name: "margin-top",
        postfix: "em",
        computed: {
          value: (value): string =>
            Math.max(2 * parseFloat(value) - 0.5, 0).toString()
        }
      }
    ]
  },
  {
    selector:
      ".post-image-below-header.post-image-aligned-center .crdm-modern-excerpt .entry-summary," +
      ".post-image-below-header .generate-columns .inside-article.crdm-modern-excerpt .entry-summary",
    properties: [
      {
        name: "margin-top",
        postfix: "em",
        computed: {
          value: (value): string =>
            Math.max(parseFloat(value) - 0.5, 0).toString()
        }
      }
    ]
  },
  {
    selector: ".crdm-modern-excerpt .entry-header",
    properties: [
      {
        name: "margin",
        prefix: "0 ",
        postfix: "em"
      },
      {
        name: "top",
        postfix: "em",
        computed: {
          value: (value): string => (parseFloat(value) - 0.5).toString()
        }
      }
    ]
  },
  {
    selector: ".crdm-modern-excerpt .entry-summary",
    properties: [
      {
        name: "margin",
        computed: {
          value: (value): string =>
            value + "em " + value + "em " + value + "em " + value + "em"
        }
      }
    ]
  }
]);

// Frontend - Title widget.
liveReload("generate_settings[logo_width]", [
  {
    selector: ".crdm-modern-title-widget-image",
    properties: [{ name: "width", postfix: "px" }]
  },
  {
    selector: ".crdm-modern-title-widget-title",
    properties: [
      {
        name: "margin-left",
        postfix: "px",
        computed: {
          value: (value): string => (parseInt(value) + 20).toString()
        }
      }
    ]
  },
  {
    selector: ".crdm-modern-title-widget-tagline",
    properties: [
      {
        name: "margin-left",
        postfix: "px",
        computed: {
          value: (value): string => (parseInt(value) + 20).toString()
        }
      }
    ]
  }
]);
liveReload("generate_settings[site_title_font_size]", [
  {
    selector: ".crdm-modern-title-widget-title",
    properties: [{ name: "font-size", postfix: "px" }]
  }
]);
liveReload("generate_settings[site_title_font_weight]", [
  {
    selector: ".crdm-modern-title-widget-title",
    properties: [{ name: "font-weight" }]
  }
]);
liveReload("generate_settings[site_tagline_font_size]", [
  {
    selector: ".crdm-modern-title-widget-tagline",
    properties: [{ name: "font-size", postfix: "px" }]
  }
]);
liveReload("generate_settings[site_tagline_font_weight]", [
  {
    selector: ".crdm-modern-title-widget-tagline",
    properties: [{ name: "font-weight" }]
  }
]);
