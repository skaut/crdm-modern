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

require_once __DIR__ . '/admin/preset-on-activation.php';
require_once __DIR__ . '/admin/customizer.php';
require_once __DIR__ . '/admin/update.php';
require_once __DIR__ . '/frontend/blog.php';
require_once __DIR__ . '/frontend/header-image.php';
require_once __DIR__ . '/frontend/class-title-widget.php';
require_once __DIR__ . '/frontend/widget-icons.php';

/**
 * Initializes the theme
 *
 * @return void
 */
function init() {
	add_action( 'after_switch_theme', '\\CrdmModern\\activate' );
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\enqueue', 11 );
	localize();
	Admin\Customizer\register();
	Admin\Preset_On_Activation\register();
	Admin\Update\register();
	Frontend\Blog\register();
	Frontend\Header_Image\register();
	Frontend\Title_Widget::register();
	Frontend\Widget_Icons\register();
}

/**
 * Loads localization files.
 *
 * @return void
 */
function localize() {
	load_theme_textdomain( 'crdm-modern', get_stylesheet_directory() . '/languages' );
}

/**
 * Theme activation function
 *
 * This function is called on theme activation. It checks all the sxystem requirements and activates some needed GeneratePress packages.
 *
 * @return bool
 */
function activate() {
	if ( ! isset( $GLOBALS['wp_version'] ) || version_compare( $GLOBALS['wp_version'], '5.0.0', '<' ) ) {
		add_action( 'admin_notices', '\\CrdmModern\\notice_wp_version' );
		switch_theme( get_option( 'theme_switched', '' ) );
		return false;
	}
	if ( version_compare( phpversion(), '7.0', '<' ) ) {
		add_action( 'admin_notices', '\\CrdmModern\\notice_php_version' );
		switch_theme( get_option( 'theme_switched', '' ) );
		return false;
	}
	if ( ! defined( 'GP_PREMIUM_VERSION' ) ) {
		add_action( 'admin_notices', '\\CrdmModern\\notice_gp_premium' );
		switch_theme( get_option( 'theme_switched', '' ) );
		return false;
	}

	update_option( 'generate_package_backgrounds', 'activated' );
	update_option( 'generate_package_blog', 'activated' );
	update_option( 'generate_package_colors', 'activated' );
	update_option( 'generate_package_spacing', 'activated' );
	update_option( 'generate_package_typography', 'activated' );

	copy_images();

	Admin\Preset_On_Activation\show_preset_popup();

	return true;
}

/**
 * Copies the theme images to WordPress Media so that the user can use them later.
 *
 * @return void
 */
function copy_images() {
	try {
		// Do not touch titles or the files will be copied again.
		copy_image( get_stylesheet_directory_uri() . '/frontend/images/tent_beige.png', 'CRDM - Modern default header image 1' );
		copy_image( get_stylesheet_directory_uri() . '/frontend/images/tee-pee.png', 'CRDM - Modern default header image 2' );
		copy_image( get_stylesheet_directory_uri() . '/frontend/images/tent_grey.png', 'CRDM - Modern default header image 3' );
		copy_image( get_stylesheet_directory_uri() . '/frontend/images/background.jpg', 'CRDM - Modern default background image' );
	} catch ( \Exception $_ ) {
		add_action( 'admin_notices', '\\CrdmModern\\notice_image_copy_failed' );
	}
}

/**
 * Copies an image to WordPress Media.
 *
 * @param string $path The absolute path to the image.
 * @param string $title The title to use for the image.
 *
 * @throws \Exception Failed to copy the image.
 *
 * @return void
 */
function copy_image( $path, $title ) {
	// Bail if image already exists.
	require_once ABSPATH . 'wp-admin/includes/post.php';
	if ( 0 !== post_exists( $title, '', '', 'attachment' ) ) {
		return;
	}

	$filename      = wp_basename( $path );
	$file_contents = wp_upload_bits( 'crdm_modern_' . $filename, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! isset( $file_contents['file'] ) ) {
		throw new \Exception();
	}
	$mime_type = wp_check_filetype( $filename );
	if ( false === $mime_type['type'] ) {
		throw new \Exception();
	}
	$attachment_args = array(
		'post_mime_type' => $mime_type['type'],
		'post_title'     => $title,
	);
	$attachment_id   = wp_insert_attachment( $attachment_args, $file_contents['file'], 0, true );
	if ( is_wp_error( $attachment_id ) ) {
		throw new \Exception();
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	// @phpstan-ignore-next-line
	$attachment_metadata = wp_generate_attachment_metadata( $attachment_id, $file_contents['file'] );
	// @phpstan-ignore-next-line
	wp_update_attachment_metadata( $attachment_id, $attachment_metadata );
}

/**
 * Registers the inline style.
 *
 * @return void
 */
function enqueue() {
	wp_register_style( 'crdm_modern_inline', false, array(), wp_get_theme()->version );
	wp_enqueue_style( 'crdm_modern_inline' );
}

/**
 * WordPress version notice
 *
 * Prints a notice informing the user they need to update their WordPress version.
 *
 * @return void
 */
function notice_wp_version() {
	echo( '<div class="notice notice-error is-dismissible"><p>' );
	esc_html_e( 'CRDM - Modern theme requires at least WordPress 5.0', 'crdm-modern' );
	echo( '</p></div>' );
}

/**
 * PHP version notice
 *
 * Prints a notice informing the user they need to update their PHP version.
 *
 * @return void
 */
function notice_php_version() {
	echo( '<div class="notice notice-error is-dismissible"><p>' );
	esc_html_e( 'CRDM - Modern theme requires at least PHP 7.0', 'crdm-modern' );
	echo( '</p></div>' );
}

/**
 * GeneratePress Premium notice
 * Prints a notice informing the user they need to get GeneratePress Premium to use this theme.
 *
 * @return void
 */
function notice_gp_premium() {
	echo( '<div class="notice notice-error is-dismissible"><p>' );
	esc_html_e( 'CRDM - Modern theme requires GeneratePress Premium', 'crdm-modern' );
	echo( '</p></div>' );
}

/**
 * Image copy failure notice
 * Prints a notice informing the user that copying the default theme images to WordPress Media failed.
 *
 * @return void
 */
function notice_image_copy_failed() {
	echo( '<div class="notice notice-warning is-dismissible"><p>' );
	esc_html_e( 'Failed to copy CRDM - Modern theme images to WordPress Media. The theme will work fine, but you won\'t be able to re-select the default images outside of the theme presets.', 'crdm-modern' );
	echo( '</p></div>' );
}

/**
 * Registers a script file
 *
 * Registers a script so that it can later be enqueued by `wp_enqueue_script()`.
 *
 * @param string        $handle A unique handle to identify the script with. This handle should be passed to `wp_enqueue_script()`.
 * @param string        $src Path to the file, relative to the theme directory.
 * @param array<string> $deps A list of dependencies of the script. These can be either system dependencies like jquery or other registered scripts. Default [].
 *
 * @return void
 */
function register_script( string $handle, string $src, array $deps = array() ) {
	$file = get_stylesheet_directory() . '/' . $src;
	wp_register_script( $handle, get_stylesheet_directory_uri() . '/' . $src, $deps, file_exists( $file ) ? strval( filemtime( $file ) ) : false, true );
}

/**
 * Enqueues a script file
 *
 * Registers and immediately enqueues a script. Note that you should **not** call this function if you've previously registered the script using `register_script()`.
 *
 * @param string        $handle A unique handle to identify the script with.
 * @param string        $src Path to the file, relative to the theme directory.
 * @param array<string> $deps A list of dependencies of the script. These can be either system dependencies like jquery or other registered scripts. Default [].
 *
 * @return void
 */
function enqueue_script( string $handle, string $src, array $deps = array() ) {
	register_script( $handle, $src, $deps );
	wp_enqueue_script( $handle );
}

/**
 * Registers a style file
 *
 * Registers a style so that it can later be enqueued by `wp_enqueue_style()`.
 *
 * @param string        $handle A unique handle to identify the style with. This handle should be passed to `wp_enqueue_style()`.
 * @param string        $src Path to the file, relative to the theme directory.
 * @param array<string> $deps A list of dependencies of the style. These can be either system dependencies like jquery or other registered style. Default [].
 *
 * @return void
 */
function register_style( string $handle, string $src, array $deps = array() ) {
	$file = get_stylesheet_directory() . '/' . $src;
	wp_register_style( $handle, get_stylesheet_directory_uri() . '/' . $src, $deps, file_exists( $file ) ? strval( filemtime( $file ) ) : false );
}

/**
 * Enqueues a style file
 *
 * Registers and immediately enqueues a style. Note that you should **not** call this function if you've previously registered the style using `register_style()`.
 *
 * @param string        $handle A unique handle to identify the style with.
 * @param string        $src Path to the file, relative to the theme directory.
 * @param array<string> $deps A list of dependencies of the style. These can be either system dependencies like jquery or other registered style. Default [].
 *
 * @return void
 */
function enqueue_style( string $handle, string $src, array $deps = array() ) {
	register_style( $handle, $src, $deps );
	wp_enqueue_style( $handle );
}

init();
