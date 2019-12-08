<?php
/**
 * Contains the Title_Widget class.
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Frontend;

class Title_Widget extends \WP_Widget {
	/**
	 * Registers the widget with WordPress.
	 */
	public static function register() {
		register_widget( '\\CrdmModern\\Frontend\\Title_Widget' );
	}

	/**
	 * Title_Widget class constructor
	 */
	public function __construct() {
		parent::__construct( 'crdm_modern_title_widget', __( 'Title Widget' , 'crdm-modern') );
	}

	/**
	 * Echoes the widget content.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$gp_settings = wp_parse_args(
			get_option( 'generate_settings', array() ),
			generate_get_defaults()
		);
		$title       = get_option( 'blogname', '' );
		$tagline     = get_option( 'blogdescription', '' );

		if ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) {
		   $logo_url = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
		}
		if ( $logo_url ) {
			$logo_url = $logo_url[0];
		} else {
			$logo_url = $gp_settings['logo'];
		}
		$logo_url = esc_url( $logo_url );

		echo( '<aside class="crdm_modern_title_widget">' );
		echo( '<img src="' );
		echo( $logo_url );
		echo( '">' );
		echo( '<div class="crdm_modern_title_widget_title">' );
		echo( $title );
		echo( '</div>' );
		echo( '<div class="crdm_modern_title_widget_tagline">' );
		echo( $tagline );
		echo( '</div>' );
		echo( '</aside>' );
	}
}
