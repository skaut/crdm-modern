declare interface Preset {
  // TODO: Own file
  image: string;
  name: string;
}

declare interface CrdmModernPresetOnActivationLocalize {
  // TODO: Own file
  apply: string;
  intro: string;
  skip: string;
  title: string;
  presets: Record<string, Preset>;
}

declare const crdmModernPresetOnActivationLocalize: CrdmModernPresetOnActivationLocalize;
declare function tb_show( // eslint-disable-line @typescript-eslint/camelcase
  caption: string,
  url: string,
  imageGroup?: string
): void;
