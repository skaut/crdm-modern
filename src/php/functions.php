<?php
/**
 * Main theme php file
 *
 * Contains the init function and activation logic.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern;

function init() {
	add_action( 'after_switch_theme', '\\CrdmModern\\activate' );
}

function activate( $old_name ) {
	if( ! isset( $GLOBALS['wp_version'] ) || version_compare( $GLOBALS['wp_version'], '5.0.0', '<' ) ) {
		add_action( 'admin_notices', '\\CrdmModern\\notice_wp_version' );
		switch_theme( get_option( 'theme_switched', '' ) );
		return false;
	}
	if(  version_compare( phpversion(), '7.0', '<' ) ) {
		add_action( 'admin_notices', '\\CrdmModern\\notice_php_version' );
		switch_theme( get_option( 'theme_switched', '' ) );
		return false;
	}
	if( ! defined( 'GP_PREMIUM_VERSION' ) ) {
		add_action( 'admin_notices', '\\CrdmModern\\notice_gp_premium' );
		switch_theme( get_option( 'theme_switched', '' ) );
		return false;
	}
	return true;
}

function notice_wp_version() {
	echo( '<div class="notice notice-error is-dismissible"><p>' );
	esc_html_e( 'CRDM - Modern theme requires at least WordPress 5.0', 'crdm-modern' );
	echo( '</p></div>' );
}

function notice_php_version() {
	echo( '<div class="notice notice-error is-dismissible"><p>' );
	esc_html_e( 'CRDM - Modern theme requires at least PHP 7.0', 'crdm-modern' );
	echo( '</p></div>' );
}

function notice_gp_premium() {
	echo( '<div class="notice notice-error is-dismissible"><p>' );
	esc_html_e( 'CRDM - Modern theme requires GeneratePress Premium', 'crdm-modern' );
	echo( '</p></div>' );
}

init();
