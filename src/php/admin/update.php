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
	WordPress_Github_Updater::set_error_messages_i10n( __( 'The theme %s is not available for updating.', 'crdm-modern' ), __( 'The GitHub API request for updates for the theme %s has failed.', 'crdm-modern' ), __( 'Error message:', 'crdm-modern' ), __( 'The GitHub API response for the theme %s is invalid.', 'crdm-modern' ), __( 'The latest version of the theme %s does not contain an update zip file.', 'crdm-modern' ) );
	// @phan-suppress-next-line PhanNoopNew
	new WordPress_Github_Updater( 'crdm-modern', 'skaut/crdm-modern', 'CRDM - Modern', 'theme' );
}
