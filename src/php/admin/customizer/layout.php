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
	// Floating navigation spacing.
	$wp_customize->add_setting(
		'crdm_modern[primary_navigation_spacing]',
		array(
			'type'              => 'option',
			'default'           => '40', // TODO: Add to preset.
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage'
		),
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
						'unit' => 'px'
					),
				),
				'priority' => 215,
			)
		)
	);
}

/**
 * Enqueues all the styles from this section.
 */
function enqueue() {
	$css = new \GeneratePress_Pro_CSS;

	$generate_color_settings = wp_parse_args( get_option( 'generate_settings', array() ), generate_get_color_defaults() );
	// TODO: Add default values to preset.
	$crdm_modern_settings = wp_parse_args( get_option( 'crdm_modern', array() ), array( 'primary_navigation_spacing' => '40' ) );

	// Floating navigation spacing.
	$css->set_selector( '.main-navigation' );
	$css->add_property( 'background-color', esc_attr( $generate_color_settings[ 'header_background_color' ] ) );

	$css->set_selector( '.main-navigation .inside-navigation' );
	$css->add_property( 'margin-left', absint( $crdm_modern_settings[ 'primary_navigation_spacing' ] ), false, 'px' );
	$css->add_property( 'margin-right', absint( $crdm_modern_settings[ 'primary_navigation_spacing' ] ), false, 'px' );
	$css->add_property( 'background-color', esc_attr( $generate_color_settings[ 'navigation_background_color' ] ) );

	$output = $css->css_output();
	if ( '' !== $output ) {
		wp_add_inline_style( 'crdm_modern_inline', $output );
	}
}
