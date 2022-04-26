<?php
/**
 * Contains the customizer Typography options.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer\Typography;

/**
 * Registers all the hooks for the customizer section.
 *
 * @return void
 */
function register() {
	add_action( 'customize_register', '\\CrdmModern\\Admin\\Customizer\\Typography\\customize', 1000 );
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Admin\\Customizer\\Typography\\enqueue', 105 );
}

/**
 * Initializes customizer options.
 *
 * Adds the settings and the controls to the customizer.
 *
 * @param \WP_Customize_Manager $wp_customize The WordPress customizer manager.
 *
 * @return void
 */
function customize( \WP_Customize_Manager $wp_customize ) {
	$defaults = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->get_stylesheet_defaults( 'crdm_modern' );

	$wp_customize->add_section(
		'crdm_modern_blog_typography',
		array(
			'priority'    => 51,
			'title'       => __( 'Blog', 'crdm-modern' ),
			'capability'  => 'edit_theme_options',
			'description' => '',
			'panel'       => 'generate_typography_panel',
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[blog_font_weight]',
		array(
			'type'              => 'option',
			'default'           => $defaults['blog_font_weight'],
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[blog_font_transform]',
		array(
			'type'              => 'option',
			'default'           => $defaults['blog_font_transform'],
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \GeneratePress_Pro_Typography_Customize_Control(
			$wp_customize,
			'crdm_modern_blog_font_controls',
			array(
				'section'  => 'crdm_modern_blog_typography',
				'settings' => array(
					'weight'    => 'crdm_modern[blog_font_weight]',
					'transform' => 'crdm_modern[blog_font_transform]',
				),
			)
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[blog_font_size]',
		array(
			'type'              => 'option',
			'default'           => strval( $defaults['blog_font_size'] ),
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \GeneratePress_Pro_Range_Slider_Control(
			$wp_customize,
			'crdm_modern[blog_font_size]',
			array(
				'description' => __( 'Font size', 'crdm-modern' ),
				'section'     => 'crdm_modern_blog_typography',
				'priority'    => 40,
				'settings'    => array(
					'desktop' => 'crdm_modern[blog_font_size]',
				),
				'choices'     => array(
					'desktop' => array(
						'min'  => 6,
						'max'  => 25,
						'step' => 1,
						'edit' => true,
						'unit' => 'px',
					),
				),
			)
		)
	);
}

/**
 * Enqueues all the styles from this section
 *
 * @return void
 */
function enqueue() {
	$css = new \GeneratePress_Pro_CSS();

	$preset   = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset();
	$settings = $preset->get_current_values( 'crdm_modern' );

	$css->set_selector( '.crdm-modern-excerpt' );
	$css->add_property( 'font-weight', esc_attr( $settings['blog_font_weight'] ) );
	$css->add_property( 'font-size', esc_attr( $settings['blog_font_size'] . 'px' ) );
	$css->add_property( 'text-transform', esc_attr( $settings['blog_font_transform'] ) );

	$output = $css->css_output();
	if ( '' !== $output ) {
		wp_add_inline_style( 'crdm_modern_inline', $output );
	}
}
