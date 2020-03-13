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

require_once __DIR__ . '/admin/customizer.php';
require_once __DIR__ . '/admin/update.php';
require_once __DIR__ . '/frontend/blog.php';
require_once __DIR__ . '/frontend/header-image.php';
require_once __DIR__ . '/frontend/class-title-widget.php';
require_once __DIR__ . '/frontend/widget-icons.php';

/**
 * Initializes the theme
 */
function init() {
	add_action( 'after_switch_theme', '\\CrdmModern\\activate' );
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\enqueue', 11 );
	localize();
	Admin\Customizer\register();
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

	apply_default_preset();
	
	return true;
}

/**
 * Registers the inline style.
 */
function enqueue() {
	wp_register_style( 'crdm_modern_inline', false, array(), wp_get_theme()->version );
	wp_enqueue_style( 'crdm_modern_inline' );
}

/**
 * Applies the dafault preset of the theme
 *
 * @return void
 */
function apply_default_preset() {
	$settings = Admin\Customizer\Preset_Registry::get_instance()->default_preset()->settings;

	$generate_mods = array(
		'font_body_category',
		'font_body_variants',
		'font_buttons_category',
		'font_buttons_variants',
		'font_footer_category',
		'font_footer_variants',
		'font_heading_1_category',
		'font_heading_1_variants',
		'font_heading_2_category',
		'font_heading_2_variants',
		'font_heading_3_category',
		'font_heading_3_variants',
		'font_heading_4_category',
		'font_heading_4_variants',
		'font_heading_5_category',
		'font_heading_5_variants',
		'font_heading_6_category',
		'font_heading_6_variants',
		'font_navigation_category',
		'font_navigation_variants',
		'font_secondary_navigation_category',
		'font_secondary_navigation_variants',
		'font_site_tagline_category',
		'font_site_tagline_variants',
		'font_site_title_category',
		'font_site_title_variants',
		'font_widget_title_category',
		'font_widget_title_variants',
		'generate_copyright',
	);
	$generate_settings = array(
		'generate_background_settings',
		'generate_blog_settings',
		'generate_dynamic_css_cached_version',
		'generate_dynamic_css_output',
		'generate_hooks',
		'generate_menu_plus_settings',
		'generate_page_header_settings',
		'generate_secondary_nav_settings',
		'generate_settings',
		'generate_spacing_settings',
		'generate_woocommerce_settings',
	);
	$generate_unused_modules = array(
		'generate_package_copyright',
		'generate_package_elements',
		'generate_package_disable_elements',
		'generate_package_hooks',
		'generate_package_menu_plus',
		'generate_package_page_header',
		'generate_package_secondary_nav',
		'generate_package_sections',
		'generate_package_woocommerce',
	);

	// Reset GeneratePress mods
	foreach( $generate_mods as $mod ) {
		if ( array_key_exists( $mod, $settings ) ) {
			set_theme_mod( $mod, $settings[ $mod ] );
		} else {
			remove_theme_mod( $mod );
		}
	}

	// Reset GeneratePress settings
	foreach( $generate_settings as $setting ) {
		if ( array_key_exists( $setting, $settings ) ) {
			update_option( $setting, $settings[ $setting ] );
		} else {
			delete_option( $setting );
		}
	}

	// Deactivate unused GeneratePress modules
	foreach( $generate_unused_modules as $package ) {
		delete_option( $package );
	}

	// Reset crdm-modern settings
	delete_option( 'crdm_modern' );
}

/**
 * WordPress version notice
 *
 * Prints a notice informing the user they need to update their WordPress version.
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
 */
function notice_php_version() {
	echo( '<div class="notice notice-error is-dismissible"><p>' );
	esc_html_e( 'CRDM - Modern theme requires at least PHP 7.0', 'crdm-modern' );
	echo( '</p></div>' );
}

/**
 * GeneratePress Premium notice
 * Prints a notice informing the user they need to get GeneratePress Premium to use this theme.
 */
function notice_gp_premium() {
	echo( '<div class="notice notice-error is-dismissible"><p>' );
	esc_html_e( 'CRDM - Modern theme requires GeneratePress Premium', 'crdm-modern' );
	echo( '</p></div>' );
}

/**
 * Registers a script file
 *
 * Registers a script so that it can later be enqueued by `wp_enqueue_script()`.
 *
 * @param string $handle A unique handle to identify the script with. This handle should be passed to `wp_enqueue_script()`.
 * @param string $src Path to the file, relative to the theme directory.
 * @param array  $deps A list of dependencies of the script. These can be either system dependencies like jquery or other registered scripts. Default [].
 */
function register_script( string $handle, string $src, array $deps = array() ) {
	$file = get_stylesheet_directory() . '/' . $src;
	wp_register_script( $handle, get_stylesheet_directory_uri() . '/' . $src, $deps, file_exists( $file ) ? filemtime( $file ) : false, true );
}

/**
 * Enqueues a script file
 *
 * Registers and immediately enqueues a script. Note that you should **not** call this function if you've previously registered the script using `register_script()`.
 *
 * @param string $handle A unique handle to identify the script with.
 * @param string $src Path to the file, relative to the theme directory.
 * @param array  $deps A list of dependencies of the script. These can be either system dependencies like jquery or other registered scripts. Default [].
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
 * @param string $handle A unique handle to identify the style with. This handle should be passed to `wp_enqueue_style()`.
 * @param string $src Path to the file, relative to the theme directory.
 * @param array  $deps A list of dependencies of the style. These can be either system dependencies like jquery or other registered style. Default [].
 */
function register_style( string $handle, string $src, array $deps = array() ) {
	$file = get_stylesheet_directory() . '/' . $src;
	wp_register_style( $handle, get_stylesheet_directory_uri() . '/' . $src, $deps, file_exists( $file ) ? filemtime( $file ) : false );
}

/**
 * Enqueues a style file
 *
 * Registers and immediately enqueues a style. Note that you should **not** call this function if you've previously registered the style using `register_style()`.
 *
 * @param string $handle A unique handle to identify the style with.
 * @param string $src Path to the file, relative to the theme directory.
 * @param array  $deps A list of dependencies of the style. These can be either system dependencies like jquery or other registered style. Default [].
 */
function enqueue_style( string $handle, string $src, array $deps = array() ) {
	register_style( $handle, $src, $deps );
	wp_enqueue_style( $handle );
}

init();
