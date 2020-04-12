<?php
/**
 * Preset application on theme activation
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Preset_On_Activation;

require_once __DIR__ . '/customizer/class-preset-registry.php';

/**
 * Registers the AJAX callback for preset selection on theme activation.
 *
 * @return void
 */
function register() {
	add_action( 'wp_ajax_crdm_modern_apply_preset', '\\CrdmModern\\Admin\\Preset_On_Activation\\handle_ajax' );
}

/**
 * Shows the preset selection popup after activating the theme.
 *
 * @return void
 */
function show_preset_popup() {
	add_action( 'admin_enqueue_scripts', '\\CrdmModern\\Admin\\Preset_On_Activation\\enqueue' );
}

/**
 * Enqueues the JavaScript for the popup and adds thickbox to the page.
 *
 * @return void
 */
function enqueue() {
	\add_thickbox();
	\CrdmModern\enqueue_style( 'crdm_modern_preset_on_activation', 'admin/css/preset_on_activation.min.css' );
	\CrdmModern\enqueue_script( 'crdm_modern_preset_on_activation', 'admin/js/preset_on_activation.min.js', array( 'jquery' ) );

	$presets = array();
	foreach ( \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->presets as $id => $preset ) {
		$presets[ $id ] = array(
			'name'  => $preset->name,
			'image' => esc_attr( get_stylesheet_directory_uri() . '/admin/' . $preset->image ),
		);
	}
	wp_localize_script(
		'crdm_modern_preset_on_activation',
		'crdmModernPresetOnActivationLocalize',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'apply'    => esc_html__( 'Apply', 'crdm-modern' ),
			'dismiss'  => esc_html__( 'Dismiss this notice.', 'crdm-modern' ),
			'error'    => esc_html__( 'Applying the preset failed.', 'crdm-modern' ),
			'intro'    => esc_html__( 'You can choose to apply one of the presets of this theme as a starting point for your website. This will change a lot of the settings of this theme and of GeneratePress, so it is advised to do this only if you are starting fresh. This can be done later in the Customizer as well.', 'crdm-modern' ),
			'nonce'    => wp_create_nonce( 'crdm_modern_preset_on_activation' ),
			'skip'     => esc_html__( 'Skip', 'crdm-modern' ),
			'success'  => esc_html__( 'Successfully applied the preset.', 'crdm-modern' ),
			'title'    => esc_html__( 'Theme preset selection', 'crdm-modern' ),
			'presets'  => $presets,
		)
	);
}

/**
 * Handles the AJAX request for preset application.
 *
 * @return void
 */
function handle_ajax() {
	check_ajax_referer( 'crdm_modern_preset_on_activation' );
	if ( ! isset( $_GET['id'] ) ) {
		wp_send_json( 'error' );
	}
	$presets   = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->presets;
	$preset_id = sanitize_text_field( wp_unslash( $_GET['id'] ) );
	if ( ! array_key_exists( $preset_id, $presets ) ) {
		wp_send_json( 'error' );
	}
	$preset = $presets[ $preset_id ];
	apply_preset( $preset );
	wp_send_json( 'success' );
}

/**
 * Applies a preset
 *
 * @param \CrdmModern\Admin\Customizer\Preset $preset The preset to apply.
 *
 * @return void
 */
function apply_preset( $preset ) {
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

	// Deactivate unused GeneratePress modules.
	foreach ( $generate_unused_modules as $package ) {
		delete_option( $package );
	}

	// Flush cached CSS.
	delete_option( 'generate_dynamic_css_output' );

	// Reset GeneratePress theme mods.
	foreach ( $preset->theme_mods() as $mod ) {
		$value = $preset->get_stylesheet_defaults( $mod );
		if ( is_null( $value ) ) {
			remove_theme_mod( $mod );
			continue;
		}
		if ( is_array( $value ) ) {
			$value = implode( ',', $value );
		}
		set_theme_mod( $mod, $value );
	}

	// Reset GeneratePress options.
	foreach ( $preset->options() as $option ) {
		echo $option;
		$value = $preset->get_stylesheet_defaults( $option );
		echo json_encode($value);
		echo "\n";
		if ( is_null( $value ) ) {
			delete_option( $option );
			continue;
		}
		update_option( $option, $value );
	}
}
