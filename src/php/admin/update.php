<?php
/**
 * Registers the WordPress_Github_Updater.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Update;

require_once __DIR__ . '/class-wordpress-github-updater.php';

/**
 * Registers the WordPress_Github_Updater.
 */
function register() {
	// @phan-suppress-next-line PhanNoopNew
	new WordPress_Github_Updater( 'crdm-modern', 'skaut/crdm-modern', 'theme' );
}
