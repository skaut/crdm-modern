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
 *
 * @return void
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
 *
 * @return void
 */
function customize( \WP_Customize_Manager $wp_customize ) {
	$defaults = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->get_stylesheet_defaults( 'crdm_modern' );

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

	// Sidebar widget separators.
	$wp_customize->add_setting(
		'crdm_modern[sidebar_widget_separator_width]',
		array(
			'type'              => 'option',
			'default'           => $defaults['sidebar_widget_separator_width'],
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \GeneratePress_Pro_Range_Slider_Control(
			$wp_customize,
			'crdm_modern[sidebar_widget_separator_width]',
			array(
				'label'    => __( 'Sidebar widget separator width', 'crdm-modern' ),
				'section'  => 'generate_layout_sidebars',
				'settings' => array(
					'desktop' => 'crdm_modern[sidebar_widget_separator_width]',
				),
				'choices'  => array(
					'desktop' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
						'edit' => true,
						'unit' => 'px',
					),
				),
				'priority' => 130,
			)
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[sidebar_widget_separator_spacing]',
		array(
			'type'              => 'option',
			'default'           => $defaults['sidebar_widget_separator_spacing'],
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \GeneratePress_Pro_Range_Slider_Control(
			$wp_customize,
			'crdm_modern[sidebar_widget_separator_spacing]',
			array(
				'label'    => __( 'Sidebar widget separator spacing', 'crdm-modern' ),
				'section'  => 'generate_layout_sidebars',
				'settings' => array(
					'desktop' => 'crdm_modern[sidebar_widget_separator_spacing]',
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
				'priority' => 131,
			)
		)
	);

	// Featured posts.
	$wp_customize->add_control(
		new \GeneratePress_Title_Customize_Control(
			$wp_customize,
			'crdm_modern_fetured_posts_title',
			array(
				'section'  => 'generate_blog_section',
				'title'    => __( 'Featured Posts', 'crdm-modern' ),
				'settings' => array(),
			)
		)
	);

	$wp_customize->add_setting(
		'crdm_modern[featured_post_count]',
		array(
			'type'              => 'option',
			'default'           => $defaults['featured_post_count'],
			'sanitize_callback' => 'generate_premium_sanitize_choices',
		)
	);

	$wp_customize->add_control(
		'crdm_modern[featured_post_count]',
		array(
			'type'     => 'select',
			'label'    => __( 'Number of featured posts', 'crdm-modern' ),
			'section'  => 'generate_blog_section',
			'choices'  => array(
				'0' => 'Disabled',
				'2' => '2',
				'3' => '3',
			),
			'settings' => 'crdm_modern[featured_post_count]',
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
	$gp_settings          = $preset->get_current_values( 'generate_settings' );
	$crdm_modern_settings = $preset->get_current_values( 'crdm_modern' );

	// Floating navigation spacing.
	$css->set_selector( '.main-navigation' );
	$css->add_property( 'background-color', esc_attr( $gp_settings['header_background_color'] ) . ' !important' );

	$css->set_selector( '.main-navigation .inside-navigation' );
	$css->add_property( 'margin-left', strval( absint( $crdm_modern_settings['primary_navigation_spacing'] ) ), false, 'px' );
	$css->add_property( 'margin-right', strval( absint( $crdm_modern_settings['primary_navigation_spacing'] ) ), false, 'px' );
	$css->add_property( 'box-shadow', esc_attr( $crdm_modern_settings['primary_navigation_shadow'] ) );
	$css->add_property( 'background-color', esc_attr( $gp_settings['navigation_background_color'] ) );

	// Sidebar widget separators.
	$css->set_selector( '.sidebar .inside-right-sidebar .widget' );
	$css->add_property( 'border-left-width', strval( absint( $crdm_modern_settings['sidebar_widget_separator_width'] ) ), false, 'px' );
	$css->add_property( 'padding-left', strval( absint( $crdm_modern_settings['sidebar_widget_separator_spacing'] ) ), false, 'px' );

	$output = $css->css_output();
	if ( '' !== $output ) {
		wp_add_inline_style( 'crdm_modern_inline', $output );
	}
}
