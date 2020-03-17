function onActivation(): void {
  let html = '<div id="crdm-modern-on-activation-modal"><div>';
  $.each(crdmModernPresetOnActivationLocalize.presets, (id: string, preset) => {
    html +=
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
      "</label>";
  });
  html += "</div></div>";
  $("body").append(html);
  tb_show("Title", "#TB_inline?inlineId=crdm-modern-on-activation-modal");
}

onActivation();
