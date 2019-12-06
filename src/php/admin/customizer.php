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

/**
 * Registers all the hooks for the customizer.
 */
function register() {
	Colors\register();
	Preset\register();
	Layout\register();
	Site_Identity\register();
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Admin\\Customizer\\enqueue', 11 );
}

/**
 * Registers the inline style to be used by the customizer options.
 */
function enqueue() {
	\CrdmModern\enqueue_script( 'crdm_modern_customizer', 'admin/js/customizer.min.js', array( 'generate-spacing-customizer' ) );
	wp_register_style( 'crdm_modern_inline', false, array(), wp_get_theme()->version );
	wp_enqueue_style( 'crdm_modern_inline' );
}
