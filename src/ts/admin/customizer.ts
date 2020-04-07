// TODO: Check all live-reload properties
interface LiveReloadProperty {
  name: string;
  prefix?: string;
  postfix?: string;
}

interface LiveReloadTarget {
  selector: string;
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

function setCSSDirectly(target: LiveReloadTarget, value: any): void {
  const el = $(target.selector);
  $.each(target.properties, function(_, property) {
    el.css(
      property.name,
      (property.prefix ?? "") + value + (property.postfix ?? "")
    );
  });
}

function setCSSInHead(
  setting: string,
  target: LiveReloadTarget,
  value: any
): void {
  const targetHash = hash(setting + target.selector);
  $("head style#" + targetHash).remove();
  $("head").append(
    '<style id="' +
      targetHash +
      '">\n' +
      target.selector +
      " {\n" +
      $.map(target.properties, function(property) {
        return (
          "\t" +
          property.name +
          ": " +
          (property.prefix ?? "") +
          value +
          (property.postfix ?? "") +
          ";\n"
        );
      }).join("") +
      "}\n" +
      "</style>"
  );
}

function liveReload(setting: string, targets: Array<LiveReloadTarget>): void {
  wp.customize(setting, function(value: any) {
    value.bind(function(newValue: any) {
      $.each(targets, function(_, target) {
        if ($(target.selector).length > 0) {
          setCSSDirectly(target, newValue);
        } else {
          setCSSInHead(setting, target, newValue);
        }
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
