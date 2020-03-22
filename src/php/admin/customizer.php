<?php
/**
 * Contains the code for the theme customizer.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer;

require_once __DIR__ . '/customizer/class-preset.php';
require_once __DIR__ . '/customizer/class-preset-registry.php';
require_once __DIR__ . '/customizer/colors.php';
require_once __DIR__ . '/customizer/preset.php';
require_once __DIR__ . '/customizer/layout.php';
require_once __DIR__ . '/customizer/site-identity.php';
require_once __DIR__ . '/customizer/controls/class-external-image-control.php';

/**
 * Registers all the hooks for the customizer.
 */
function register() {
	Colors\register();
	Preset\register();
	Layout\register();
	Site_Identity\register();
	add_action( 'customize_register', '\\CrdmModern\\Admin\\Customizer\\fix_images', 1000 );
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Admin\\Customizer\\enqueue', 11 );
}

function fix_images( \WP_Customize_Manager $wp_customize ) {
	$wp_customize->remove_control( 'generate_backgrounds-body-image' );
	$wp_customize->add_control(
		new Controls\External_Image_Control(
			$wp_customize,
			'generate_backgrounds-body-image',
			array(
				'section'  => 'generate_backgrounds_body',
				'settings' => 'generate_background_settings[body_image]',
				'label'    => __( 'Body', 'crdm-modern' ),
				'priority' => 5,
			)
		)
	);
}

/**
 * Registers customizer live preview script
 */
function enqueue() {
	\CrdmModern\enqueue_script( 'crdm_modern_customizer', 'admin/js/customizer.min.js', array( 'generate-spacing-customizer' ) );
}
