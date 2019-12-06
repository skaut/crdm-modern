<?php
/**
 * Contains the customizer Site Identity options.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer\Site_Identity;

/**
 * Registers all the hooks for the customizer section.
 */
function register() {
	add_action( 'customize_register', '\\CrdmModern\\Admin\\Customizer\\Site_Identity\\customize', 1000 );
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Admin\\Customizer\\Site_Identity\\enqueue', 105 );
}

/**
 * Initializes customizer options.
 *
 * Adds the settings and the controls to the customizer.
 *
 * @param \WP_Customize_Manager $wp_customize The WordPress customizer manager.
 */
function customize( \WP_Customize_Manager $wp_customize ) {
	$defaults = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->settings['crdm_modern'];

	// Header image
	$wp_customize->add_setting(
		'crdm_modern[header_image]',
		array(
			'type'              => 'option',
			'default'           => $defaults['header_image'],
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new \WP_Customize_Image_Control(
			$wp_customize,
			'crdm_modern[header_image]',
			array(
				'label'       => __( 'Header Image', 'crdm-modern' ),
				'section'     => 'title_tagline',
				'settings'    => 'crdm_modern[header_image]',
			)
		)
	);
}

/**
 * Enqueues all the styles from this section.
 */
function enqueue() {
	$css = new \GeneratePress_Pro_CSS();

	$defaults    = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->settings;
	$crdm_modern_settings = wp_parse_args(
		get_option( 'crdm_modern', array() ),
		$defaults['crdm_modern']
	);

	// Header image.
	$css->set_selector( '.crdm_modern_nav_image' );
	$css->add_property( 'background-image', 'url("' . esc_url( $crdm_modern_settings['header_image'] ) . '")' );

	$output = $css->css_output();
	if ( '' !== $output ) {
		wp_add_inline_style( 'crdm_modern_inline', $output );
	}
}
