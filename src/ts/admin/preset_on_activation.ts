function onActivation(): void {
  const html =
    '<div id="crdm-modern-on-activation-modal"><div>HELLO</div></div>';
  $("body").append(html);
  tb_show("Title", "#TB_inline?inlineId=crdm-modern-on-activation-modal");
}

onActivation();
