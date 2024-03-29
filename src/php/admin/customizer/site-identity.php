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
 *
 * @return void
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
 *
 * @return void
 */
function customize( \WP_Customize_Manager $wp_customize ) {
	$defaults = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->get_stylesheet_defaults( 'crdm_modern' );

	if ( is_null( $defaults ) ) {
		return;
	}

	// Header Image.
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
				'label'    => __( 'Header Image', 'crdm-modern' ),
				'section'  => 'title_tagline',
				'settings' => 'crdm_modern[header_image]',
			)
		)
	);

	// Header Image edit button.
	$wp_customize->selective_refresh->add_partial(
		'crdm_modern[header_image]',
		array(
			'selector' => '.crdm-modern-nav-image-container',
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[header_image_height]',
		array(
			'type'              => 'option',
			'default'           => $defaults['header_image_height'],
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \GeneratePress_Pro_Range_Slider_Control(
			$wp_customize,
			'crdm_modern[header_image_height]',
			array(
				'label'    => __( 'Header Image Height', 'crdm-modern' ),
				'section'  => 'title_tagline',
				'settings' => array(
					'desktop' => 'crdm_modern[header_image_height]',
				),
				'choices'  => array(
					'desktop' => array(
						'min'  => '0',
						'max'  => '500',
						'step' => '1',
						'edit' => true,
						'unit' => 'px',
					),
				),
			)
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[header_image_top]',
		array(
			'type'              => 'option',
			'default'           => $defaults['header_image_top'],
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \GeneratePress_Pro_Range_Slider_Control(
			$wp_customize,
			'crdm_modern[header_image_top]',
			array(
				'label'    => __( 'Header Image Top Position', 'crdm-modern' ),
				'section'  => 'title_tagline',
				'settings' => array(
					'desktop' => 'crdm_modern[header_image_top]',
				),
				'choices'  => array(
					'desktop' => array(
						'min'  => '0',
						'max'  => '300',
						'step' => '1',
						'edit' => true,
						'unit' => 'px',
					),
				),
			)
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[header_image_right]',
		array(
			'type'              => 'option',
			'default'           => $defaults['header_image_right'],
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \GeneratePress_Pro_Range_Slider_Control(
			$wp_customize,
			'crdm_modern[header_image_right]',
			array(
				'label'    => __( 'Header Image Right Position', 'crdm-modern' ),
				'section'  => 'title_tagline',
				'settings' => array(
					'desktop' => 'crdm_modern[header_image_right]',
				),
				'choices'  => array(
					'desktop' => array(
						'min'  => '0',
						'max'  => '300',
						'step' => '1',
						'edit' => true,
						'unit' => 'px',
					),
				),
			)
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[header_image_min_width]',
		array(
			'type'              => 'option',
			'default'           => $defaults['header_image_min_width'],
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new \GeneratePress_Pro_Range_Slider_Control(
			$wp_customize,
			'crdm_modern[header_image_min_width]',
			array(
				'label'    => __( 'Minimum Width to Show Header Image', 'crdm-modern' ),
				'section'  => 'title_tagline',
				'settings' => array(
					'desktop' => 'crdm_modern[header_image_min_width]',
				),
				'choices'  => array(
					'desktop' => array(
						'min'  => '769',
						'max'  => '2000',
						'step' => '1',
						'edit' => true,
						'unit' => 'px',
					),
				),
			)
		)
	);
}

/**
 * Enqueues all the styles from this section.
 *
 * @return void
 */
function enqueue() {
	$css = new \GeneratePress_Pro_CSS();

	$preset               = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset();
	$crdm_modern_settings = $preset->get_current_values( 'crdm_modern' );

	// Header image.
	$css->set_selector( '.crdm-modern-nav-image' );
	$css->add_property( 'max-height', strval( absint( $crdm_modern_settings['header_image_height'] ) ), false, 'px' );
	$css->add_property( 'top', strval( absint( $crdm_modern_settings['header_image_top'] ) ), false, 'px' );
	$css->add_property( 'right', strval( absint( $crdm_modern_settings['header_image_right'] ) ), false, 'px' );

	// Header Image edit button.
	$css->set_selector( '.customize-partial-edit-shortcut-crdm_modern-header_image' );
	$css->add_property( 'top', strval( absint( $crdm_modern_settings['header_image_top'] ) ), false, 'px' );
	$css->add_property( 'right', strval( max( intval( $crdm_modern_settings['header_image_right'] ) - 30, 30 ) ), false, 'px' );

	$css->start_media_query( '(max-width: ' . strval( absint( $crdm_modern_settings['header_image_min_width'] ) ) . 'px)' );
	$css->set_selector( '.crdm-modern-nav-image' );
	$css->add_property( 'display', 'none' );
	$css->stop_media_query();

	$output = $css->css_output();
	if ( '' !== $output ) {
		wp_add_inline_style( 'crdm_modern_inline', $output );
	}
}
