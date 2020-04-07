interface LiveReloadComputedProperty {
  additionalSettings: Array<string>;
  value: (value: any, additionalValues: Array<any>) => string;
}

interface LiveReloadProperty {
  name: string;
  prefix?: string;
  postfix?: string;
  computed?: LiveReloadComputedProperty;
}

interface MediaRules {
  minWidth?: number;
  maxWidth?: number;
}

interface LiveReloadTarget {
  selector: string;
  media?: MediaRules;
  properties: Array<LiveReloadProperty>;
}

function hash(str: string): string {
  if (str.length === 0) {
    return "";
  }
  let ret = 0;
  for (let i = 0; i < str.length; i++) {
    ret = (ret << 5) - ret + str.charCodeAt(i); // eslint-disable-line no-bitwise
    ret |= 0; // eslint-disable-line no-bitwise
  }
  return ret.toString();
}

function setCSSInHead(
  setting: string,
  target: LiveReloadTarget,
  value: any
): void {
  const targetHash = hash(setting + target.selector);
  let mediaBegin = "";
  let mediaEnd = "";
  if (target.media) {
    mediaBegin = "@media (";
    if (target.media.minWidth) {
      mediaBegin += "min-width: " + target.media.minWidth;
    } else if (target.media.maxWidth) {
      mediaBegin += "max-width: " + target.media.maxWidth;
    }
    mediaBegin += "px) {\n";
    mediaEnd = "}\n";
  }
  $("head style#" + targetHash).remove();
  $("head").append(
    '<style id="' +
      targetHash +
      '">\n' +
      mediaBegin +
      target.selector +
      " {\n" +
      $.map(target.properties, function(property) {
        let computedValue = value;
        if (property.computed) {
          computedValue = property.computed.value(
            value,
            $.map(property.computed.additionalSettings, additionalSetting =>
              wp.customize(additionalSetting).get()
            )
          );
        }
        return (
          "\t" +
          property.name +
          ": " +
          (property.prefix ?? "") +
          computedValue +
          (property.postfix ?? "") +
          ";\n"
        );
      }).join("") +
      "}\n" +
      mediaEnd +
      "</style>"
  );
}

function liveReload(setting: string, targets: Array<LiveReloadTarget>): void {
  wp.customize(setting, function(value: any) {
    value.bind(function(newValue: any) {
      $.each(targets, function(_, target) {
        setCSSInHead(setting, target, newValue);
      });
    });
  });
}

// Site Identity.
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

// Colors.
liveReload("generate_settings[sidebar_widget_background_color]", [
  {
    selector: ".sidebar .widget_search .search-field",
    properties: [{ name: "background-color" }]
  }
]);
liveReload("generate_settings[sidebar_widget_text_color]", [
  {
    selector: ".sidebar .widget_search .search-field",
    properties: [{ name: "border-color" }]
  },
  {
    selector: ".sidebar .widget_search .search-field",
    properties: [{ name: "color" }]
  }
]);
liveReload("generate_settings[sidebar_widget_link_color]", [
  {
    selector: ".sidebar .widget_search .search-field:focus",
    properties: [{ name: "border-color" }]
  }
]);

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

// Blog.
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
      ".post-image-aligned-left .crdm-modern-excerpt .entry-header, .post-image-aligned-left .crdm-modern-excerpt .entry-summary",
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
      ".post-image-aligned-right .crdm-modern-excerpt .entry-header, .post-image-aligned-right .crdm-modern-excerpt .entry-summary",
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
      ".post-image-aligned-left .generate-columns .crdm-modern-excerpt .entry-header, .post-image-aligned-left .generate-columns .crdm-modern-excerpt .entry-summary, .post-image-aligned-right .generate-columns .crdm-modern-excerpt .entry-header, .post-image-aligned-right .generate-columns .crdm-modern-excerpt .entry-summary",
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
          additionalSettings: [],
          value: (value): string => (parseFloat(value) + 0.5).toString()
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
          additionalSettings: [],
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
          additionalSettings: [],
          value: (value): string =>
            value + "em " + value + "em " + value + "em " + value + "em"
        }
      }
    ]
  }
]);

// Layout.
liveReload("generate_settings[header_background_color]", [
  {
    selector: ".main-navigation",
    properties: [{ name: "background-color" }]
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

// Typography.
liveReload("crdm_modern[blog_font_size]", [
  {
    selector: ".crdm-modern-excerpt",
    properties: [{ name: "font-size", postfix: "px" }]
  }
]);

// Title widget.
liveReload("generate_settings[logo_width]", [
  {
    selector: ".widget_crdm_modern_title_widget img",
    properties: [{ name: "width" }]
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
