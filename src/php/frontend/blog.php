<?php
/**
 * Contains the functions for the blog.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Frontend\Blog;

/**
 * Registers all the hooks for the blog.
 */
function register() {
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Frontend\\Blog\\enqueue', 51 );
	add_action( 'generate_after_entry_title', '\\CrdmModern\\Frontend\\Blog\\remove_post_meta', 9 );
	add_action( 'generate_before_entry_title', '\\CrdmModern\\Frontend\\Blog\\add_post_meta', 9 );
	add_action( 'crdmmodern_before_content', '\\CrdmModern\\Frontend\\Blog\\before_content' );
	add_action( 'crdmmodern_after_content', '\\CrdmModern\\Frontend\\Blog\\after_content' );
	add_filter( 'post_class', '\\CrdmModern\\Frontend\\Blog\\post_classes' );
	add_filter( 'generate_resized_featured_image_output', '\\CrdmModern\\Frontend\\Blog\\featured_image' );
}

/**
 * Removes posted-on date from after the excerpt title in the blog.
 */
function remove_post_meta() {
	if ( ! \is_singular() ) {
		remove_action( 'generate_after_entry_title', 'generate_post_meta' );
	}
}

/**
 * Adds posted-on date before the excerpt title in the blog.
 */
function add_post_meta() {
	if ( ! \is_singular() ) {
		add_action( 'generate_before_entry_title', 'generate_post_meta' );
	}
}

/**
 * Registers the script for the blog.
 */
function enqueue() {
	\CrdmModern\enqueue_script( 'crdm_modern_blog', 'frontend/js/blog.min.js', array( 'jquery' ) );
	if ( ! \generate_blog_get_columns() ) {
		wp_add_inline_style( 'crdm_modern_inline', blog_css() );
	}
}

/**
 * Returns the CSS for the blog layout
 *
 * @return string The CSS for the multi-column blog layout
 */
function blog_css() {
	$spacing_settings = wp_parse_args(
		get_option( 'generate_spacing_settings', array() ),
		\generate_spacing_get_defaults()
	);
	$blog_settings    = wp_parse_args(
		get_option( 'generate_blog_settings', array() ),
		\generate_blog_get_defaults()
	);

	$separator = absint( $spacing_settings['separator'] );
	$features_image_aspect_ratio = strval( round( 100 * $blog_settings['post_image_height'] / $blog_settings['post_image_width'], 2 ) );

	$ret  = '.generate-columns {margin-bottom: ' . $separator . 'px;padding-left: ' . $separator . 'px;}';
	$ret .= '.generate-columns-container {margin-left: -' . $separator . 'px;}';
	$ret .= '.generate-columns .crdm-modern-excerpt .post-image {padding-top: ' . $features_image_aspect_ratio . '%;}';
	$ret .= '@media (max-width: 768px) {.crdm-modern-excerpt .post-image {padding-top: ' . $features_image_aspect_ratio . '%;}}';
	$ret .= '@media (min-width: 769px) {article:not(.generate-columns) .crdm-modern-excerpt .wp-post-image {height: ' . $blog_settings['post_image_height'] . 'px; width: ' . $blog_settings['post_image_width'] . 'px;}}';

	return $ret;
}

/**
 * Returns whether the number of featured posts.
 *
 * @return int The total number of featured images.
 */
function get_featured_post_count() {
	$defaults             = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->settings;
	$crdm_modern_settings = wp_parse_args(
		get_option( 'crdm_modern', array() ),
		$defaults['crdm_modern']
	);
	return intval( $crdm_modern_settings['featured_post_count'] );
}

/**
 * Returns whether a featured-post function should run.
 *
 * @param string $function_name The name of the function.
 * @param string $condition Where the function should run - on the first featured image, on all or on the last one. One of `begin`, `all`, `end`.
 *
 * @return bool Whether the function should run.
 */
function should_run( $function_name, $condition ) {
	static $counts = array(
		'before_content' => 0,
		'post_classes'   => 0,
		'after_content'  => 0,
	);
	$counts[ $function_name ]++;

	$featured_post_count = get_featured_post_count();
	if ( 0 === $featured_post_count || \is_singular() || \generate_blog_get_columns() ) {
		return false;
	}

	switch ( $condition ) {
		case 'begin':
			return 1 === $counts[ $function_name ];
		case 'all':
			return $counts[ $function_name ] <= $featured_post_count;
		case 'end':
			return $counts[ $function_name ] === $featured_post_count;
		default:
			return false;
	}
}

/**
 * Fires before each article on the blog. Initializes the multi-column layout when appropriate
 */
function before_content() {
	if ( ! should_run( 'before_content', 'begin' ) ) {
		return;
	}
	echo( '<div class="generate-columns-container">' );
}

/**
 * Adds the multi-column blog layout classes to the featured posts.
 *
 * @param string[] $classes A list of post classes.
 *
 * @return string[] The updated list of post classes.
 */
function post_classes( $classes ) {
	if ( ! should_run( 'post_classes', 'all' ) ) {
		return $classes;
	}
	$featured_post_count = get_featured_post_count();
	$classes[]           = 'generate-columns';
	$classes[]           = 'tablet-grid-50';
	$classes[]           = 'mobile-grid-100';
	$classes[]           = 'grid-parent';
	$classes[]           = 2 === $featured_post_count ? 'grid-50' : 'grid-33';
	return $classes;
}

/**
 * Makes the featured images full-sized in the multi-column blog layout for the featured posts
 *
 * @return string the updated featured image HTML.
 */
function featured_image() {
	// TODO Dynamically select the best size depending on whether it is in columns.
	$post_ID = get_the_ID();
	return '<div class="post-image">' .
			apply_filters( 'generate_inside_featured_image_output', '' ) // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			. '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">' .
				get_the_post_thumbnail(
					$post_ID,
					apply_filters( 'generate_page_header_default_size', 'full' ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
					array(
						'itemprop' => 'image',
					)
				)
			. '</a>
		</div>';
}

/**
 * Fires after each article on the blog. Closes the multi-column layout when appropriate
 */
function after_content() {
	if ( ! should_run( 'after_content', 'end' ) ) {
		return;
	}
	echo( '</div><!-- .generate-columns-contaier -->' );
}
