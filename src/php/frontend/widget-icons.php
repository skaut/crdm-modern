<?php
/**
 * Enqueues the frontend styles.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Frontend\Widget_Icons;

/**
 * Registers all the hooks for the header image.
 */
function register() {
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Frontend\\Widget_Icons\\enqueue' );
	add_filter( 'widget_title', '\\CrdmModern\\Frontend\\Widget_Icons\\add_icon', 10, 3 );
}

/**
 * Enqueues the dripicons font.
 */
function enqueue() {
	\CrdmModern\enqueue_style( 'crdm_modern_dripicons', 'frontend/dripicons/webfont.css' );
}

/**
 * Adds the icon to the widget title
 *
 * @param string $title The title of the widget.
 * @param array  $_ Unused.
 * @param string $id_base The ID of the widget.
 *
 * @return string The new title.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function add_icon( string $title, array $_, string $id_base ) {
	switch ( $id_base ) {
		case 'text':
			$icon = 'message';
			break;
		case 'recent-posts':
			$icon = 'pencil';
			break;
		case 'recent-comments':
			$icon = 'inbox';
			break;
		case 'archives':
			$icon = 'box';
			break;
		default:
			$icon = '';
	}
	if ( '' !== $icon ) {
		$icon = '<span class="icon dripicons-' . $icon . ' crdm_modern_inline_icon"></span>';
	}
	return $icon . $title;
}
