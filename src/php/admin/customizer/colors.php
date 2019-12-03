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
}

/**
 * Enqueues all the styles from this section.
 */
function enqueue() {
	$css = new \GeneratePress_Pro_CSS();

	$defaults             = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->settings;
	$gp_settings    = wp_parse_args(
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

	$output = $css->css_output();
	if ( '' !== $output ) {
		wp_add_inline_style( 'crdm_modern_inline', $output );
	}
}
