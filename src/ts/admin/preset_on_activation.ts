function applyPreset(): void {
  const id = $("input[name=crdm_modern_preset_on_activation]:checked").val();
  $.get(
    crdmModernPresetOnActivationLocalize.ajax_url, {
      _ajax_nonce: crdmModernPresetOnActivationLocalize.nonce, // eslint-disable-line @typescript-eslint/camelcase
      action: "crdm_modern_apply_preset",
      id
    },
    function(response: any) {
      console.log(response);
    }
  );
}

function onActivation(): void {
  let html =
    '<div id="crdm-modern-preset-on-activation-modal">' +
    '<div id="crdm-modern-preset-on-activation-overflow">' +
    "<div>" +
    crdmModernPresetOnActivationLocalize.intro +
    "</div>" +
    "<br>";
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
    "#TB_inline?inlineId=crdm-modern-preset-on-activation-modal"
  );

  $("input[name=crdm_modern_preset_on_activation]").change(function() {
    const applyButton = $("#crdm-modern-preset-on-activation-apply");
    applyButton.removeAttr("disabled");
    applyButton.click(applyPreset);
  });
  $("#crdm-modern-preset-on-activation-skip").click(function() {
    tb_remove();
  });
}

onActivation();
