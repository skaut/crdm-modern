<?php
/**
 * Contains the functions for the header image.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Frontend\Header_Image;

/**
 * Registers all the hooks for the header image.
 */
function register() {
	add_action( 'generate_header', '\\CrdmModern\\Frontend\\Header_Image\\insert', 9 );
}

/**
 * Inserts the header image into the page
 */
function insert() {
	$preset               = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset();
	$crdm_modern_settings = $preset->get_current_values( 'crdm_modern' );

	if ( isset( $crdm_modern_settings['header_image'] ) ) {
		echo( '<div class="crdm-modern-nav-image-container grid-container"><img class="crdm-modern-nav-image" src="' );
		echo( esc_url( $crdm_modern_settings['header_image'] ) );
		echo( '"></div>' );
	}
}
