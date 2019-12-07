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
	add_action( 'wp_enqueue_scripts', '\\CrdmModern\\Frontend\\Blog\\enqueue' );
	add_action( 'generate_after_entry_title', '\\CrdmModern\\Frontend\\Blog\\remove_post_meta', 9 );
	add_action( 'generate_before_entry_title', 'generate_post_meta' );
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
}
