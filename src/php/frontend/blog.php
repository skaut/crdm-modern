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
 *
 * @return void
 */
function register() {
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Frontend\\Blog\\enqueue', 51 );
	add_action( 'generate_after_entry_title', '\\CrdmModern\\Frontend\\Blog\\remove_post_meta', 9 );
	add_action( 'generate_before_entry_title', '\\CrdmModern\\Frontend\\Blog\\add_post_meta', 9 );
	add_action( 'crdmmodern_before_content', '\\CrdmModern\\Frontend\\Blog\\before_content' );
	add_action( 'crdmmodern_after_content', '\\CrdmModern\\Frontend\\Blog\\after_content' );
	add_filter( 'post_class', '\\CrdmModern\\Frontend\\Blog\\post_classes' );
	add_filter( 'generate_featured_image_output', '\\CrdmModern\\Frontend\\Blog\\featured_image' );
}

/**
 * Removes posted-on date from after the excerpt title in the blog.
 *
 * @return void
 */
function remove_post_meta() {
	if ( ! \is_singular() ) {
		remove_action( 'generate_after_entry_title', 'generate_post_meta' );
	}
}

/**
 * Adds posted-on date before the excerpt title in the blog.
 *
 * @return void
 */
function add_post_meta() {
	if ( ! \is_singular() ) {
		add_action( 'generate_before_entry_title', 'generate_post_meta' );
	}
}

/**
 * Registers the script for the blog.
 *
 * @return void
 */
function enqueue() {
	\CrdmModern\enqueue_script( 'crdm_modern_blog', 'frontend/js/blog.min.js', array( 'jquery' ) );
	wp_add_inline_style( 'crdm_modern_inline', blog_css() );
	$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$version = defined( 'GENERATE_BLOG_VERSION' ) ? GENERATE_BLOG_VERSION : null;
	wp_enqueue_style( 'generate-blog', plugins_url() . "/gp-premium/blog/functions/css/style{$suffix}.css", array(), $version );
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

	$separator                   = absint( $spacing_settings['separator'] );
	$content_separator           = abs( floatval( $spacing_settings['content_element_separator'] ) );
	$features_image_aspect_ratio = strval( round( 100 * $blog_settings['post_image_height'] / $blog_settings['post_image_width'], 2 ) );

	return '.generate-columns {' .
		'padding-left: ' . $separator . 'px;' .
		'}' .
		'.generate-columns-container {' .
		'margin-bottom: ' . $separator . 'px;' .
		'margin-left: -' . $separator . 'px;' .
		'}' .
		'.crdm-modern-excerpt {' .
		'margin-bottom: ' . $separator . 'px;' .
		'}' .
		'.post-image-aligned-center .crdm-modern-excerpt .post-image,' .
		'.generate-columns .crdm-modern-excerpt .post-image {' .
		'padding-top: ' . $features_image_aspect_ratio . '%;' .
		'}' .
		'@media (max-width: 768px) {' .
		'.crdm-modern-excerpt .post-image {' .
		'padding-top: ' . $features_image_aspect_ratio . '%;' .
		'}' .
		'.post-image-below-header.post-image-aligned-left .inside-article.crdm-modern-excerpt .post-image,' .
		'.post-image-below-header.post-image-aligned-right .inside-article.crdm-modern-excerpt .post-image {' .
		'margin-top: ' . max( 2 * $content_separator - 0.5, 0 ) . 'em;' .
		'}' .
		'.post-image-below-header .crdm-modern-excerpt .entry-summary {' .
		'margin-top: ' . max( $content_separator - 0.5, 0 ) . 'em;' .
		'}' .
		'}' .
		'@media (min-width: 769px) {' .
		'body:not(.post-image-aligned-center) article:not(.generate-columns) .crdm-modern-excerpt .wp-post-image {' .
		'height: ' . $blog_settings['post_image_height'] . 'px;' .
		'width: ' . $blog_settings['post_image_width'] . 'px;' .
		'}' .
		'.post-image-aligned-left .crdm-modern-excerpt .entry-header,' .
		'.post-image-aligned-left .crdm-modern-excerpt .entry-summary {' .
		'margin-left: calc(' . $blog_settings['post_image_width'] . 'px + ' . $content_separator . 'em);' .
		'}' .
		'.post-image-aligned-right .crdm-modern-excerpt .entry-header,' .
		'.post-image-aligned-right .crdm-modern-excerpt .entry-summary {' .
		'margin-right: calc(' . $blog_settings['post_image_width'] . 'px + ' . $content_separator . 'em);' .
		'}' .
		'.post-image-aligned-left .generate-columns .crdm-modern-excerpt .entry-header,' .
		'.post-image-aligned-left .generate-columns .crdm-modern-excerpt .entry-summary,' .
		'.post-image-aligned-right .generate-columns .crdm-modern-excerpt .entry-header,' .
		'.post-image-aligned-right .generate-columns .crdm-modern-excerpt .entry-summary {' .
		'margin-left: ' . $content_separator . 'em;' .
		'margin-right: ' . $content_separator . 'em;' .
		'}' .
		'.generate-columns .crdm-modern-excerpt .entry-header {' .
		'top: ' . $content_separator . 'em;' .
		'}' .
		'.generate-columns .crdm-modern-excerpt .entry-summary {' .
		'margin-top: ' . ( $content_separator + 0.5 ) . 'em;' .
		'}' .
		'}' .
		'.post-image-below-header.post-image-aligned-center .crdm-modern-excerpt .post-image,' .
		'.post-image-below-header .generate-columns .inside-article.crdm-modern-excerpt .post-image {' .
		'margin-top: ' . max( 2 * $content_separator - 0.5, 0 ) . 'em;' .
		'}' .
		'.post-image-below-header.post-image-aligned-center .crdm-modern-excerpt .entry-summary,' .
		'.post-image-below-header .generate-columns .inside-article.crdm-modern-excerpt .entry-summary {' .
		'margin-top: ' . max( $content_separator - 0.5, 0 ) . 'em;' .
		'}' .
		'.crdm-modern-excerpt .entry-header {' .
		'margin: 0 ' . $content_separator . 'em;' .
		'top: ' . ( $content_separator - 0.5 ) . 'em;' .
		'}' .
		'.crdm-modern-excerpt .entry-summary {' .
		'margin: ' . $content_separator . 'em ' . $content_separator . 'em ' . $content_separator . 'em ' . $content_separator . 'em;' .
		'}';
}

/**
 * Returns whether the number of featured posts.
 *
 * @return int The total number of featured images.
 */
function get_featured_post_count() {
	$preset               = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset();
	$crdm_modern_settings = $preset->get_current_values( 'crdm_modern' );
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
	++$counts[ $function_name ];

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
 *
 * @return void
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
 * @param array<string> $classes A list of post classes.
 *
 * @return array<string> The updated list of post classes.
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
 * Returns the approximate width of the content area.
 *
 * @return int The approximate width.
 */
function content_width() {
	$preset              = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset();
	$gp_settings         = $preset->get_current_values( 'generate_settings' );
	$gp_spacing_settings = $preset->get_current_values( 'generate_spacing_settings' );

	switch ( $gp_settings['blog_layout_setting'] ) {
		case 'left-sidebar':
			$left_sidebar  = true;
			$right_sidebar = false;
			break;
		case 'right-sidebar':
			$left_sidebar  = false;
			$right_sidebar = true;
			break;
		case 'both-sidebars':
		case 'both-left':
		case 'both-right':
			$left_sidebar  = true;
			$right_sidebar = true;
			break;
		default:
			$left_sidebar  = false;
			$right_sidebar = false;
	}
	$sidebar_width = 0;
	if ( $left_sidebar ) {
		$sidebar_width += $gp_spacing_settings['left_sidebar_width'];
	}
	if ( $right_sidebar ) {
		$sidebar_width += $gp_spacing_settings['right_sidebar_width'];
	}
	return intval( $gp_settings['container_width'] - $sidebar_width );
}

/**
 * Returns the (aproximate) maximum width of a featured post featured images.
 *
 * @return int The approximate width.
 */
function featured_post_image_width() {
	$preset               = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset();
	$crdm_modern_settings = $preset->get_current_values( 'crdm_modern' );

	$featured_post_count = intval( $crdm_modern_settings['featured_post_count'] );
	return 0 !== $featured_post_count ? intval( content_width() / $featured_post_count ) : 0;
}

/**
 * Returns the (aproximate) maximum width of a normal post featured images.
 *
 * @return int The approximate width.
 */
function featured_image_width() {
	$preset           = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset();
	$gp_blog_settings = $preset->get_current_values( 'generate_blog_settings' );

	switch ( $gp_blog_settings['post_image_alignment'] ) {
		case 'post-image-aligned-center':
			return content_width();
		default:
			return $gp_blog_settings['post_image_width'];
	}
}

/**
 * Returns the (approximate) width and height for featured images.
 *
 * @return array{0: int, 1: int} The approximate width and height.
 */
function featured_image_size() {
	$preset           = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset();
	$gp_blog_settings = $preset->get_current_values( 'generate_blog_settings' );

	$width  = max( 768, featured_post_image_width(), featured_image_width() );
	$height = $width / $gp_blog_settings['post_image_width'] * $gp_blog_settings['post_image_height'];
	return array( $width, $height );
}

/**
 * Makes the featured images full-sized in the multi-column blog layout for the featured posts
 *
 * @return string the updated featured image HTML.
 */
function featured_image() {
	$post_ID = get_the_ID();
	if ( false === $post_ID ) {
		return '';
	}

	$image_html = get_the_post_thumbnail(
		$post_ID,
		featured_image_size(),
		array(
			'itemprop' => 'image',
		)
	);
	$permalink  = get_permalink( $post_ID );
	if ( false === $permalink ) {
		return '';
	}

	return '<div class="post-image">' .
			apply_filters( 'generate_inside_featured_image_output', '' ) . // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'<a href="' . esc_url( $permalink ) . '">' .
			$image_html .
			'</a>' .
		'</div>';
}

/**
 * Fires after each article on the blog. Closes the multi-column layout when appropriate
 *
 * @return void
 */
function after_content() {
	if ( ! should_run( 'after_content', 'end' ) ) {
		return;
	}
	echo( '</div><!-- .generate-columns-contaier -->' );
}
