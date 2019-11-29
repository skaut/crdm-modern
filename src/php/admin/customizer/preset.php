<?php
/**
 * Contains the customizer Preset section.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer\Preset;

require_once __DIR__ . '/controls/class-preset-customize-control.php';

const PRESETS = array(
	'blue' => array(),
);

/**
 * Registers all the hooks for the customizer section.
 */
function register() {
	add_action( 'customize_register', '\\CrdmModern\\Admin\\Customizer\\Preset\\customize', 1000 );
}

/**
 * Initializes customizer options.
 *
 * Adds the panel and the control to the customizer
 *
 * @param \WP_Customize_Manager $wp_customize The WordPress customizer manager.
 */
function customize( \WP_Customize_Manager $wp_customize ) {
	$wp_customize->add_section(
		'crdm_modern_preset',
		array(
			'priority' => 21,
			'title'    => __( 'Preset', 'crdm-modern' ),
		)
	);

	$wp_customize->add_control(
		new \CrdmModern\Admin\Customizer\Controls\Preset_Customize_Control(
			$wp_customize,
			'crdm_modern_preset',
			array(
				'settings' => array(),
				'section'  => 'crdm_modern_preset',
			),
			PRESETS
		)
	);
}
