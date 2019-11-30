<?php
/**
 * Contains the customizer Preset section.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer\Preset;

require_once __DIR__ . '/controls/class-preset-customize-control.php';

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
			array(
				new \CrdmModern\Admin\Customizer\Controls\Preset_Customize_Control\Preset(
					'blue',
					__( 'Blue', 'crdm-modern' ),
					'presets/blue.png',
					array(
						'generate_settings'          => array(
							// Colors.
							'site_title_color'                       => '#00395e',
							'site_tagline_color'                     => '#00395e',
							'navigation_background_color'            => '#007bc2',
							'navigation_background_hover_color'      => '#00395e',
							'navigation_background_current_color'    => '#00395e',
							'subnavigation_background_color'         => '#00395e',
							'subnavigation_background_hover_color'   => '#007bc2',
							'subnavigation_background_current_color' => '#007bc2',
							'sidebar_widget_title_color'             => '#007bc2',
							'footer_widget_background_color'         => '#007bc2',
							'footer_widget_text_color'               => '#ffffff',
							'footer_widget_link_color'               => '#ffffff',
							'footer_widget_title_color'              => '#ffffff',
							// Typography.
							'font_body'                              => 'Source Sans Pro',
							'body_font_weight'                       => '600',
							// TODO: Fix Customizer UI not updating for font sizes.
							'body_font_size'                         => '15',
							'font_site_title'                        => 'Raleway',
							'site_title_font_weight'                 => '900',
							'font_site_tagline'                      => 'Raleway',
							'site_tagline_font_size'                 => '25',
							'navigation_font_weight'                 => 'bold',
							'navigation_font_transform'              => 'uppercase',
							'navigation_font_size'                   => '17',
							'heading_2_weight'                       => '700',
							'heading_2_font_size'                    => '19',
							'widget_title_font_size'                 => '18',
							'widget_title_separator'                 => '5',
							'widget_content_font_size'               => '15',
						),
						// Typography.
						// TODO: Fix Customizer UI not updating for font variants.
						'font_body_variants'         => array( '600', '600italic', '700', '700italic' ), // This needs to be consistent across all places with the same font.
						'font_site_title_variants'   => array( 'regular', '900' ), // This needs to be consistent across all places with the same font.
						'font_site_tagline_variants' => array( 'regular', '900' ), // This needs to be consistent across all places with the same font.
					)
				),
			)
		)
	);
}
