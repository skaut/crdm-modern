<?php
/**
 * Contains the code for the theme customizer.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer;

require_once __DIR__ . '/customizer/preset.php';

/**
 * Registers all the hooks for the customizer.
 */
function register() {
	Preset\register();
}
