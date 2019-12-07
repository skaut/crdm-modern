<?php
/**
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Frontend\Blog;

function register() {
	add_action( 'generate_after_entry_title', '\\CrdmModern\\Frontend\\Blog\\remove_post_meta', 9 );
	add_action( 'generate_before_entry_title', 'generate_post_meta' );
}

function remove_post_meta() {
	remove_action( 'generate_after_entry_title', 'generate_post_meta' );
}
