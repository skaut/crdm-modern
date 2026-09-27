# AGENTS.md

This file provides guidance to coding agents when working with code in this repository.

## What this is

`crdm-modern` is a WordPress **child theme of GeneratePress** (and it hard-requires GeneratePress Premium — `activate()` in `src/php/functions.php` bails and switches back if `GP_PREMIUM_VERSION` is undefined). Almost all of the code exists to extend GeneratePress's customizer with extra settings, presets, and live-preview behaviour.

Everything is written under `src/` and built into `dist/`, which is what actually gets installed as the theme. `dist/` is generated — never edit it.

## Commands

```bash
npm install && composer install   # setup
npm run build                     # gulp build (cleans dist/ first via prebuild)
npm run check                     # after build: es-check + CSS compat check (scripts/check-css-compat.js) over dist/
npm run lint                      # all linters in parallel
npm run lint:eslint               # eslint (TS, JS, JSON, Markdown…)
npm run lint:typecheck            # tsc --noEmit
npm run lint:css                  # stylelint
npm run lint:php                  # phan + phpcs + phpmd + phpstan in parallel
npm run lint:php:phpstan          # a single PHP linter, e.g. when narrowing down a failure
npm run update-translations       # regenerate .pot and update .po files
```

There is no test suite — CI runs exactly three jobs: build (followed by `npm run check`), lint, and a **translation-sync check** that runs `npm run update-translations` and fails if the working tree is then dirty. So after touching any translatable string (`__()`, `esc_html_e()`, …), run `npm run update-translations` and commit the resulting `src/languages/` changes, or CI fails.

Build requires PHP + composer, not just Node: the `build:l10n` and `update-translations` gulp tasks shell out to `./vendor/bin/wp i18n`.

## Build layout (gulpfile.js)

The gulpfile is the mapping from `src/` type-named directories to the theme layout in `dist/`:

| source | destination |
| --- | --- |
| `src/php/*.php` | `dist/` |
| `src/php/admin/`, `src/php/frontend/` | `dist/admin/`, `dist/frontend/` |
| `src/css/style.css` + hand-listed `src/css/frontend/*.css` | lowered/minified with Lightning CSS, concatenated into `dist/style.css` |
| `src/css/admin/*.css` | `dist/admin/css/*.min.css` |
| `src/ts/**` | bundles in `dist/{admin,frontend}/js/*.min.js` |
| images, `src/txt/*`, dripicons webfont | `dist/…` per task |

TypeScript is **not** bundled by a module bundler. `bundle()` in the gulpfile compiles a hand-listed set of `.ts` files with `gulp-typescript`, concatenates the output, and (when the `jQuery` flag is passed — currently every bundle) wraps it in `jQuery(document).ready(function($){ … })`. Consequences:

- Files in a bundle share one global scope and communicate through globals, not `import`/`export`. Cross-file symbols are declared via `src/d.ts/**/*.d.ts` (also fed into every bundle) and marked with `/* exported X */`.
- **Adding a new `.ts` file requires adding it to the relevant `bundle()` call in `gulpfile.js`**, and order within the array matters.
- Localized data passed from PHP via `wp_localize_script` is typed in `src/d.ts/admin/*.d.ts` as `declare const crdmModern…Localize`.

## PHP architecture

Plain namespaced functions, no autoloader: `src/php/functions.php` `require_once`s every module and calls `init()`, which calls each module's `register()`; each `register()` only adds hooks. `src/php/admin/customizer.php` does the same one level down for the customizer sections (`colors.php`, `layout.php`, `preset.php`, `site-identity.php`, `typography.php`).

Naming follows WordPress conventions enforced by phpcs: `class-*.php` files for classes, `Snake_Case` class names, snake_case functions, `CrdmModern` global prefix, `crdm-modern` text domain.

### Presets

`Preset` / `Preset_Registry` (`src/php/admin/customizer/`) hold named sets of default values for both the `generate_settings` and `crdm_modern` option arrays. Customizer sections read their defaults from `Preset_Registry::get_instance()->default_preset()`, so a new setting needs a default in the presets, not a literal. `preset-on-activation.php` shows a thickbox popup offering presets right after theme activation.

### Settings → CSS, twice

Every customizer setting effectively has **two** implementations that must be kept in sync:

1. **Server-side render** — each section's `enqueue()` builds CSS with `GeneratePress_Pro_CSS` (`set_selector()` / `add_property()`) and inlines it into the `crdm_modern_inline` style handle registered in `functions.php`.
2. **Customizer live preview** — `src/ts/admin/customizer.ts` calls `liveReload(setting, targets, additionalSettings?)` with the same selectors and properties; `liveReload.ts` injects a `<style>` into the preview `<head>` keyed by a hash of setting+selector.

When adding or changing a setting, update the PHP `enqueue()` **and** the matching `liveReload()` call, including fallback chains (e.g. widget text color → content text color → text color) which appear as `additionalSettings` on the JS side.

## TypeScript / lint conventions

`tsconfig.json` is maximally strict (`strict`, `exactOptionalPropertyTypes`, `noUnusedLocals`, `noPropertyAccessFromIndexSignature`, …) and `eslint.config.ts` (flat config) layers `strictTypeChecked`/`stylisticTypeChecked` plus a long list of extra rules on top of `@wordpress/eslint-plugin`. Notable ones that shape the code: explicit return types everywhere, `strict-boolean-expressions` (no truthiness checks on strings/numbers), generic `Array<T>` instead of `T[]`, sorted imports (perfectionist), and `eslint-comments/require-description` — every `eslint-disable` needs a `-- reason`. `reportUnusedDisableDirectives` is an error (and stylelint runs with `--report-needless-disables`), so stale disables break the lint.

Browser support is broad on purpose: the `browserslist` in `package.json` (`baseline 2019`, not Edge 18) drives `eslint-plugin-compat` and Lightning CSS targets; `tsconfig.json` targets `es2017`, and `npm run check` verifies the built JS is ES2017 and the built CSS is supported by that browserslist. PHP must stay compatible with 7.0+ and WordPress 5.0+ (enforced by phpcs `PHPCompatibilityWP`).
