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
		add_action( 'wp_enqueue_scripts', array( '\\CrdmModern\\Frontend\\Title_Widget', 'enqueue' ), 105 );
	}

	/**
	 * Title_Widget class constructor
	 */
	public function __construct() {
		parent::__construct( 'crdm_modern_title_widget', __( 'Title Widget' , 'crdm-modern') );
	}

	/**
	 * Enqueues all the styles for the widget.
	 */
	public static function enqueue() {
		$css = new \GeneratePress_Pro_CSS();

		$defaults             = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset()->settings;
		$gp_settings          = wp_parse_args(
			get_option( 'generate_settings', array() ),
			array_merge( generate_get_defaults(), generate_get_default_fonts(), $defaults['generate_settings'] )
		);
		$site_title_family = generate_get_font_family_css( 'font_site_title', 'generate_settings', generate_get_default_fonts() );

		$css->set_selector( '.crdm_modern_title_widget img' );
		$css->add_property( 'width', strval( absint( $gp_settings['logo_width'] ) ), false, 'px' );

		$css->set_selector( '.crdm_modern_title_widget' );
		$css->add_property( 'font-family', 'inherit' !== $gp_settings['font_site_title'] ? $site_title_family : null );

		$css->set_selector( '.crdm_modern_title_widget_title' );
		$css->add_property( 'font-size', strval( absint( $gp_settings['site_title_font_size'] ) ), false, 'px' );
		$css->add_property( 'font-weight', strval( absint( $gp_settings['site_title_font_weight'] ) ), false, 'px' );

		$css->set_selector( '.crdm_modern_title_widget_tagline' );
		$css->add_property( 'font-size', strval( absint( $gp_settings['site_tagline_font_size'] ) ), false, 'px' );
		$css->add_property( 'font-weight', strval( absint( $gp_settings['site_tagline_font_weight'] ) ), false, 'px' );

		$output = $css->css_output();
		if ( '' !== $output ) {
			wp_add_inline_style( 'crdm_modern_inline', $output );
		}
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
