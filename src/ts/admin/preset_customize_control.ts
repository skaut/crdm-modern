function applyPreset(control: any): void {
  const chosen = control.container
    .find("input[name=crdm_modern_preset]:checked")
    .val();
  if (!chosen) {
    return;
  }

  const preset = crdmModernPresetCustomizeControlLocalize[chosen];
  $.each(preset, function(key, value) {
    const setting = wp.customize(key);
    if (!setting) {
      return;
    }
    setting.set(value);
  });

  $(".generatepress-font-variant select").trigger("change");
}

wp.customize.control("crdm_modern_preset", function(control: any) {
  control.container.find("input[name=crdm_modern_preset]").change(function() {
    control.container.find(".button").prop("disabled", false);
  });

  control.container.find(".button").click(function() {
    applyPreset(control);
  });
});
