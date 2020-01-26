<?php
/**
 * Contains the customizer Colors options.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer\Colors;

/**
 * Registers all the hooks for the customizer section.
 */
function register() {
	add_action( 'customize_register', '\\CrdmModern\\Admin\\Customizer\\Colors\\customize', 1000 );
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Admin\\Customizer\\Colors\\enqueue', 105 );
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

	// Sidebar widget separators.
	$wp_customize->add_setting(
		'crdm_modern[sidebar_widget_separator_color]',
		array(
			'type'              => 'option',
			'default'           => $defaults['sidebar_widget_separator_color'],
			'sanitize_callback' => 'generate_premium_sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \WP_Customize_Color_Control(
			$wp_customize,
			'crdm_modern[sidebar_widget_separator_color]',
			array(
				'label'    => __( 'Widget Separator', 'crdm-modern' ),
				'section'  => 'sidebar_widget_color_section',
				'settings' => 'crdm_modern[sidebar_widget_separator_color]',
				'priority' => 6,
			)
		)
	);

	// Excerpt border.
	$wp_customize->add_setting(
		'crdm_modern[excerpt_border_color]',
		array(
			'type'              => 'option',
			'default'           => $defaults['excerpt_border_color'],
			'sanitize_callback' => 'generate_premium_sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \WP_Customize_Color_Control(
			$wp_customize,
			'crdm_modern[excerpt_border_color]',
			array(
				'label'    => __( 'Excerpt Border', 'crdm-modern' ),
				'section'  => 'content_color_section',
				'settings' => 'crdm_modern[excerpt_border_color]',
				'priority' => 4,
			)
		)
	);


	// Excerpt hover background.
	$wp_customize->add_setting(
		'crdm_modern[excerpt_hover_background_color]',
		array(
			'type'              => 'option',
			'default'           => $defaults['excerpt_hover_background_color'],
			'sanitize_callback' => 'generate_premium_sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new \WP_Customize_Color_Control(
			$wp_customize,
			'crdm_modern[excerpt_hover_background_color]',
			array(
				'label'    => __( 'Excerpt Background Hover', 'crdm-modern' ),
				'section'  => 'content_color_section',
				'settings' => 'crdm_modern[excerpt_hover_background_color]',
				'priority' => 4,
			)
		)
	);
}

/**
 * Enqueues all the styles from this section.
 */
function enqueue() {
	$css = new \GeneratePress_Pro_CSS();

	$defaults             = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->settings;
	$gp_settings          = wp_parse_args(
		get_option( 'generate_settings', array() ),
		array_merge( generate_get_defaults(), generate_get_color_defaults(), $defaults['generate_settings'] )
	);
	$crdm_modern_settings = wp_parse_args(
		get_option( 'crdm_modern', array() ),
		$defaults['crdm_modern']
	);

	// Search widget colors.
	$css->set_selector( '.sidebar .widget_search .search-field' );
	$css->add_property( 'background-color', esc_attr( $gp_settings['sidebar_widget_background_color'] ) );
	$widget_text_color = $gp_settings['sidebar_widget_text_color'];
	if ( '' === $widget_text_color ) {
		$widget_text_color = $gp_settings['content_text_color'];
	}
	if ( '' === $widget_text_color ) {
		$widget_text_color = $gp_settings['text_color'];
	}
	$css->add_property( 'border-color', esc_attr( $widget_text_color ) );
	$css->add_property( 'color', esc_attr( $widget_text_color ) );

	$widget_link_color = $gp_settings['sidebar_widget_link_color'];
	if ( '' === $widget_link_color ) {
		$widget_link_color = $gp_settings['content_link_color'];
	}
	if ( '' === $widget_link_color ) {
		$widget_link_color = $gp_settings['link_color'];
	}
	$css->set_selector( '.sidebar .widget_search .search-field:focus' );
	$css->add_property( 'border-color', esc_attr( $widget_link_color ) );

	// Sidebar widget separators.
	$css->set_selector( '.sidebar .inside-right-sidebar .widget' );
	$css->add_property( 'border-left-color', esc_attr( $crdm_modern_settings['sidebar_widget_separator_color'] ) );

	// Excerpt border.
	$css->set_selector( '.crdm_modern_excerpt' );
	$css->add_property( 'border-color', esc_attr( $crdm_modern_settings['excerpt_border_color'] ) );

	// Excerpt hover background color.
	$css->set_selector( '.crdm_modern_excerpt:hover' );
	$css->add_property( 'background-color', esc_attr( $crdm_modern_settings['excerpt_hover_background_color'] ) );
	$css->set_selector( '.crdm_modern_excerpt:hover .entry-title a' );
	$css->add_property( 'color', esc_attr( $gp_settings['blog_post_title_hover_color'] ) );
	$css->set_selector( '.crdm_modern_excerpt:hover .entry-meta a' );
	$css->add_property( 'color', esc_attr( $gp_settings['entry_meta_link_color_hover'] ) );

	$output = $css->css_output();
	if ( '' !== $output ) {
		wp_add_inline_style( 'crdm_modern_inline', $output );
	}
}
