<?php
/**
 * Contains the code for the theme customizer.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer;

require_once __DIR__ . '/customizer/preset.php';
require_once __DIR__ . '/customizer/layout.php';

/**
 * Registers all the hooks for the customizer.
 */
function register() {
	Preset\register();
	Layout\register();
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Admin\\Customizer\\enqueue' );
}

/**
 * Registers the inline style to be used by the customizer options.
 */
function enqueue() {
	wp_register_style( 'crdm_modern_inline', false, array(), wp_get_theme()->version );
	wp_enqueue_style( 'crdm_modern_inline' );
}
