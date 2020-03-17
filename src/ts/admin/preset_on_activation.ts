function onActivation(): void {
  let html =
    '<div id="crdm-modern-on-activation-modal">' +
    '<div id="crdm-modern-preset-on-activation-overflow">';
  $.each(crdmModernPresetOnActivationLocalize.presets, (id: string, preset) => {
    html +=
      '<div class="crdm-modern-preset-on-activation-preset">' +
      "<label>" +
      '<input type="radio" name="crdm_modern_preset_on_activation" value="' +
      id +
      '">' +
      preset.name +
      '<img src="' +
      preset.image +
      '" alt="' +
      preset.name +
      '" class="crdm-modern-preset-on-activation-image"">' +
      "</label>" +
      "</div>";
  });
  html +=
    "</div>" +
    '<div id="crdm-modern-preset-on-activation-footer">' +
    '<a id="crdm-modern-preset-on-activation-apply" class="button button-primary" disabled>' +
    crdmModernPresetOnActivationLocalize.apply +
    "</a>" +
    '<a id="crdm-modern-preset-on-activation-skip" class="button">' +
    crdmModernPresetOnActivationLocalize.skip +
    "</a>" +
    "</div>" +
    "</div>";
  $("body").append(html);
  tb_show(
    crdmModernPresetOnActivationLocalize.title,
    "#TB_inline?inlineId=crdm-modern-on-activation-modal"
  );
}

onActivation();
