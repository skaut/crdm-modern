<?php
/**
 * Contains the customizer Layout options.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer\Layout;

/**
 * Registers all the hooks for the customizer section.
 */
function register() {
	add_action( 'customize_register', '\\CrdmModern\\Admin\\Customizer\\Layout\\customize', 1000 );
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Admin\\Customizer\\Layout\\enqueue', 105 );
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

	// Floating navigation spacing.
	$wp_customize->add_setting(
		'crdm_modern[primary_navigation_spacing]',
		array(
			'type'              => 'option',
			'default'           => $defaults['primary_navigation_spacing'],
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \GeneratePress_Pro_Range_Slider_Control(
			$wp_customize,
			'crdm_modern[primary_navigation_spacing]',
			array(
				'label'    => __( 'Navigation spacing', 'crdm-modern' ),
				'section'  => 'generate_layout_navigation',
				'settings' => array(
					'desktop' => 'crdm_modern[primary_navigation_spacing]',
				),
				'choices'  => array(
					'desktop' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
						'edit' => true,
						'unit' => 'px',
					),
				),
				'priority' => 215,
			)
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[primary_navigation_shadow]',
		array(
			'type'      => 'option',
			'default'   => $defaults['primary_navigation_shadow'],
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'crdm_modern[primary_navigation_shadow]',
		array(
			'type'     => 'text',
			'label'    => __( 'Navigation shadow', 'crdm-modern' ),
			'section'  => 'generate_layout_navigation',
			'priority' => 216,
		)
	);
}

/**
 * Enqueues all the styles from this section.
 */
function enqueue() {
	$css = new \GeneratePress_Pro_CSS();

	$defaults             = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->settings;
	$gp_settings    = wp_parse_args(
		get_option( 'generate_settings', array() ),
		array_merge( generate_get_color_defaults(), $defaults['generate_settings'] )
	);
	$crdm_modern_settings = wp_parse_args(
		get_option( 'crdm_modern', array() ),
		$defaults['crdm_modern']
	);

	// Floating navigation spacing.
	$css->set_selector( '.main-navigation' );
	$css->add_property( 'background-color', esc_attr( $gp_settings['header_background_color'] ) );

	$css->set_selector( '.main-navigation .inside-navigation' );
	$css->add_property( 'margin-left', strval( absint( $crdm_modern_settings['primary_navigation_spacing'] ) ), false, 'px' );
	$css->add_property( 'margin-right', strval( absint( $crdm_modern_settings['primary_navigation_spacing'] ) ), false, 'px' );
	$css->add_property( 'box-shadow', esc_attr( $crdm_modern_settings['primary_navigation_shadow'] ) );
	$css->add_property( 'background-color', esc_attr( $gp_settings['navigation_background_color'] ) );

	$output = $css->css_output();
	if ( '' !== $output ) {
		wp_add_inline_style( 'crdm_modern_inline', $output );
	}
}
