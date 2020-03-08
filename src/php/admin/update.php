<?php
/**
 * Registers the WordPress_Github_Updater.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Update;

/**
 * Registers the WordPress_Github_Updater.
 */
function register() {
	new WordPress_Github_Updater( 'crdm-modern', 'skaut/crdm-modern', 'theme' );
}
