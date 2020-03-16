<?php
/**
 * Preset application on theme activation
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin;

require_once __DIR__ . '/customizer/class-preset-registry.php';

/**
 * Applies the dafault preset of the theme
 *
 * @return void
 */
function apply_default_preset() {
	$settings = Customizer\Preset_Registry::get_instance()->default_preset()->settings;

	// @phan-suppress-next-line PhanVariableDefinitionCouldBeConstant
	$generate_mods = array(
		'font_body_category',
		'font_body_variants',
		'font_buttons_category',
		'font_buttons_variants',
		'font_footer_category',
		'font_footer_variants',
		'font_heading_1_category',
		'font_heading_1_variants',
		'font_heading_2_category',
		'font_heading_2_variants',
		'font_heading_3_category',
		'font_heading_3_variants',
		'font_heading_4_category',
		'font_heading_4_variants',
		'font_heading_5_category',
		'font_heading_5_variants',
		'font_heading_6_category',
		'font_heading_6_variants',
		'font_navigation_category',
		'font_navigation_variants',
		'font_secondary_navigation_category',
		'font_secondary_navigation_variants',
		'font_site_tagline_category',
		'font_site_tagline_variants',
		'font_site_title_category',
		'font_site_title_variants',
		'font_widget_title_category',
		'font_widget_title_variants',
		'generate_copyright',
	);
	// @phan-suppress-next-line PhanVariableDefinitionCouldBeConstant
	$generate_settings = array(
		'generate_background_settings',
		'generate_blog_settings',
		'generate_dynamic_css_cached_version',
		'generate_dynamic_css_output',
		'generate_hooks',
		'generate_menu_plus_settings',
		'generate_page_header_settings',
		'generate_secondary_nav_settings',
		'generate_settings',
		'generate_spacing_settings',
		'generate_woocommerce_settings',
	);
	// @phan-suppress-next-line PhanVariableDefinitionCouldBeConstant
	$generate_unused_modules = array(
		'generate_package_copyright',
		'generate_package_elements',
		'generate_package_disable_elements',
		'generate_package_hooks',
		'generate_package_menu_plus',
		'generate_package_page_header',
		'generate_package_secondary_nav',
		'generate_package_sections',
		'generate_package_woocommerce',
	);

	// Reset GeneratePress mods.
	foreach ( $generate_mods as $mod ) {
		if ( array_key_exists( $mod, $settings ) ) {
			set_theme_mod( $mod, $settings[ $mod ] );
		} else {
			remove_theme_mod( $mod );
		}
	}

	// Reset GeneratePress settings.
	foreach ( $generate_settings as $setting ) {
		if ( array_key_exists( $setting, $settings ) ) {
			update_option( $setting, $settings[ $setting ] );
		} else {
			delete_option( $setting );
		}
	}

	// Deactivate unused GeneratePress modules.
	foreach ( $generate_unused_modules as $package ) {
		delete_option( $package );
	}

	// Reset crdm-modern settings.
	delete_option( 'crdm_modern' );
}
