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
	add_action( 'generate_before_entry_title', 'generate_post_meta' );
	add_action( 'crdm_modern_before_content', '\\CrdmModern\\Frontend\\Blog\\before_content', 9 );
	add_action( 'crdm_modern_after_content', '\\CrdmModern\\Frontend\\Blog\\after_content', 9 );
	add_filter( 'post_class', '\\CrdmModern\\Frontend\\Blog\\post_classes' );
}

/**
 * Removes posted-on date from after the excerpt title.
 */
function remove_post_meta() {
	remove_action( 'generate_after_entry_title', 'generate_post_meta' );
}

/**
 * Registers the script for the blog.
 */
function enqueue() {
	\CrdmModern\enqueue_script( 'crdm_modern_blog', 'frontend/js/blog.min.js', array( 'jquery' ) );
	if ( ! \generate_blog_get_columns() ) {
		wp_add_inline_style( 'crdm_modern_inline', columns_css() );
	}
}

function columns_css() {
	$spacing_settings = wp_parse_args(
		get_option( 'generate_spacing_settings', array() ),
		\generate_spacing_get_defaults()
	);

	$separator = absint( $spacing_settings['separator'] );

	$ret = '.generate-columns {margin-bottom: ' . $separator . 'px;padding-left: ' . $separator . 'px;}';
	$ret .= '.generate-columns-container {margin-left: -' . $separator . 'px;}';
	$ret .= '.page-header {margin-bottom: ' . $separator . 'px;margin-left: ' . $separator . 'px}';
	$ret .= '.generate-columns-container > .paging-navigation {margin-left: ' . $separator . 'px;}';

	return $ret;
}

function before_content() {
	static $count = -1;
	$count++;
	if ( $count !== 0 || \generate_blog_get_columns() ) {
		return;
	}
	echo( '<div class="generate-columns-container">' );
}

function post_classes( $classes ) {
	static $count = -1;
	$count++;
	if ( $count > 1 || \generate_blog_get_columns() ) {
		return $classes;
	}
	$classes[] = 'generate-columns';
	$classes[] = 'tablet-grid-50';
	$classes[] = 'mobile-grid-100';
	$classes[] = 'grid-parent';
	$classes[] = 'grid-50';

	return $classes;
}

function after_content() {
	static $count = -1;
	$count++;
	if ( $count !== 1 || \generate_blog_get_columns() ) {
		return;
	}
	echo( '</div><!-- .generate-columns-contaier -->' );
}
