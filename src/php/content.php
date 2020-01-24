<?php
/**
 * The template for the blog articles.
 *
 * @package crdm-modern
 */

do_action( 'crdm_modern_before_content' );
require get_template_directory() . '/content.php';
do_action( 'crdm_modern_after_content' );
