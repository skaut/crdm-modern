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
	add_action( 'generate_header', '\\CrdmModern\\Frontend\\Header_Image\\insert', 11 );
}

/**
 * Inserts the header image into the page
 */
function insert() {
	echo( '<div class="crdm_modern_nav_image_container grid-container"><div class="crdm_modern_nav_image"></div></div>' );
}
